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
    	//Redirect to Facebook login URL for logging in
    	$loginUrl = ThreadgabLoginBundle::getLoginUrl();

		$content = $this->renderView(
		    'ThreadgabLoginBundle:Login:index.html.twig',
		    array('loginUrl' => $loginUrl)
		);

		return new Response($content);
    }

	public function getsessionAction()
    {
    	//Catch the redirected url and find out the session information
		try {

			//Get the session id for the user
		  	$session = ThreadgabLoginBundle::getSession();
		  	
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
    		 $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 50, 50)->asArray();

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

	    		//This is the facebook Id. This Id will be used for matching the user to
	    		// the Threadgab Id when the user logs in through facebook.	
		        $user->setFacebookId($user_profile->getId());
		        $user->setCreationDate(date_create(date("Y-m-d H:i:s", time())));
		        $user->setPhotoUrl($user_profile_photo->url);

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
				$url = $this->generateUrl('portal_homepage', array('currentforum' => $subforum[0]->getSubForumName()));
					return $this->redirect($url);	
			}

    	} else {
    		return new Response("Not available");
    	}
    }
}
