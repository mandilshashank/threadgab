<?php

namespace Threadgab\Bundle\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;
use Symfony\Component\HttpFoundation\Response;
use Threadgab\Bundle\LoginBundle\ThreadgabLoginBundle;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser;
use Symfony\Component\HttpFoundation\Request;

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
ini_set('display_errors',"1");

class LoginController extends Controller
{
    public function indexAction()
    {
    	$baseURL = $this->container->getParameter('base_url');
    	$redirectUrl=$baseURL.$this->generateUrl('threadgab_login_redirect');

    	//Redirect to Facebook login URL for logging in
    	$loginUrl = ThreadgabLoginBundle::getLoginUrl($redirectUrl);

		$content = $this->renderView(
		    'ThreadgabLoginBundle:Login:index.html.twig',
		    array('loginUrl' => $loginUrl)
		);

		return new Response($content);
    }

	public function getsessionAction()
    {
    	$baseURL = $this->container->getParameter('base_url');
    	$redirectUrl=$baseURL.$this->generateUrl('threadgab_login_redirect');

    	//Catch the redirected url and find out the session information
		try {

			//Get the session id for the user
		  	$session = ThreadgabLoginBundle::getSession($redirectUrl);
		  	
		  	//If we dont get an exception here it means that the sessoin has been active and we are
		  	//now ready to redirect the user either to the user information page or to the main 
		  	//discussion forum.

		  	//We will be deciding to transfer to a forum or the user information page by making a 
		  	//database query for the user to see if he/she already exists in the database.
		  	//If they already exist to transfer them to the forum page otherwise we transfer them to the
		  	//user information page.
		  	if($session) {
		  		$_SESSION['fb_token'] = $session->getToken();	
				try {
					//Redirect to a link here with the session variable using generateUrl

					$url = $this->generateUrl('threadgab_userinfo_redirect');
					return $this->redirect($url);			
				} catch(FacebookRequestException $e) {

					//Create an error page for this scenario	

					//echo "Exception occured, code: " . $e->getCode();
					//echo " with message: " . $e->getMessage();
				}   
			}

		} catch(FacebookRequestException $ex) {
		 	// When Facebook returns an error
			return new Response('Got FacebookRequestException');
		} catch(Exception $ex) {
		  	// When validation fails or other local issues
			return new Response('Got Some Exception');
		}

		return new Response('Session is not found');
    }  

    public function showformAction(Request $request)
    {
    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
            $user_raw_profile = ThreadgabLoginBundle::getFacebookRawProfile($session)->asArray();
    		$user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 100, 100)->asArray();

    		//Check in database if the user with this FacebookId already
    		//exists, other wise create a new user

			$repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');

			$query = $repository->createQueryBuilder('p')
							->where('p.facebookid = :facebookId')
							->setParameter('facebookId', (string)$user_profile->getId())
							->getQuery();

			$users = $query->getResult();

			if(!$users) {
				$user = new ThreadgabUser();

                //Add the lat and long for a user based on his/her current location

	    		//This is the facebook Id. This Id will be used for matching the user to
	    		// the Threadgab Id when the user logs in through facebook.	
		        $user->setFacebookId($user_profile->getId());
                $user->setName($user_profile->getName());
		        $user->setCreationDate(date_create(date("Y-m-d H:i:s", time())));
		        $user->setPhotoUrl($user_profile_photo["url"]);
		        $user->setNumSub('0');
                $user->setEmailid($user_raw_profile["email"]);

                $lat_lng = ThreadgabLoginBundle::getLatLong();
                $user->setLat(floatval($lat_lng['lat']));
                $user->setLng(floatval($lat_lng['lng']));

                $ip = $_SERVER['REMOTE_ADDR'];
                $details = json_decode(file_get_contents("http://www.telize.com/geoip"));
                $zipcode = $details->postal_code;

                $user->setZipcode($zipcode);

		        $form = $this->createFormBuilder($user)
		            ->add('emailid', 'text', array('label' => 'Email Id', 'attr' => array('class' => 'form-control')))
		            ->add('zipcode', 'text', array('label' => 'Zip Code', 'attr' => array('class' => 'form-control')))
		            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'form-control')))
		            ->getForm();

		        $form->handleRequest($request);

			    if ($form->isValid()) {
			        //Persist the user to the database
			        $em = $this->getDoctrine()->getManager();
				    $em->persist($user);
				    $em->flush();

                    //Add the user as subscriber to all his friends present in the system
                    //Also add the friends as subscribers to the user

                    $user_friends = ThreadgabLoginBundle::getFacebookFriends($session)
                        ->getProperty('data')->asArray();
                    $facebook_ids = array();

                        //Get the facebookid entry for each friend matching to the friend id
                    foreach ($user_friends as $friend){
                        array_push($facebook_ids, $friend->id);
                    }

                    $em = $this->getDoctrine()->getManager();

                        //Get the friend thread for a specific user friends and a specific subforum
                    $query = $em->createQuery(
                        "SELECT u
                        FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                        WHERE u.facebookid in (".implode(',', $facebook_ids).")
                    ");

                    $friends = $query->getResult();

                        //Add 1 to the number to the number of subscribers for the friends of the user
                    foreach($friends as $friend){
                        //Add the user to the subscriber number of the friend
                        $friend->setNumSub($friend->getNumSub()+1);
                        $em->persist($friend);
                        $em->flush();

                        // Add the friend to the subscriber number of the user
                        $user->setNumSub($user->getNumSub()+1);
                        $em->persist($user);
                        $em->flush();
                    }

                    //End Add the user as subscriber to all his friends present in the system

				    $query_subforum = $em->createQuery(
		                "SELECT t
		                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
		                ORDER BY t.id"
		            );

		            $subforum  = $query_subforum->getResult();

				    //After adding user to database take to the main portal page
				    $url = $this->generateUrl('portal_homepage', array('currentforum' => $subforum[0]->getSubForumName()));
					return $this->redirect($url);	
			    }
		        
		        //Render the form
		        return $this->render('ThreadgabLoginBundle:Login:userinfo.html.twig', array(
		           'form' => $form->createView()
		        ));
			} else {
				$users[0]->setPhotoUrl($user_profile_photo["url"]);
                $users[0]->setName($user_profile->getName());

                if($users[0]->getLat()==0 || $users[0]->getLng()==0){
                    $lat_lng = ThreadgabLoginBundle::getLatLong();
                    $users[0]->setLat(floatval($lat_lng['lat']));
                    $users[0]->setLng(floatval($lat_lng['lng']));
                }

				$em = $this->getDoctrine()->getManager();
			    $em->persist($users[0]);
			    $em->flush();

				$em = $this->getDoctrine()->getManager();
				   
			    $query_subforum = $em->createQuery(
	                "SELECT t
	                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
	                ORDER BY t.id"
	            );

	            $subforum  = $query_subforum->getResult();

				//User already exists. Redirect to the main page of the forum.
				//$url = $this->generateUrl('portal_homepage', array('currentforum' => $subforum[0]->getSubForumName()));
				//	return $this->redirect($url);

                $url = $this->generateUrl('portal_frontpage', array('currentforum' => $subforum[0]->getSubForumName()));
                return $this->redirect($url);
			}

    	} else {
    		return new Response("Not available");
    	}
    }
}
