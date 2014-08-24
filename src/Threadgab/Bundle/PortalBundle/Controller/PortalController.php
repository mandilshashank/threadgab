<?php

namespace Threadgab\Bundle\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Threadgab\Bundle\LoginBundle\Entity\ThreadgabUser;
use Symfony\Component\HttpFoundation\Response;
use Threadgab\Bundle\LoginBundle\ThreadgabLoginBundle;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;


if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
ini_set('display_errors',"1");

class PortalController extends Controller
{
    public function mainAction()
    {
    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
		    $user_friends = ThreadgabLoginBundle::getFacebookFriends($session)
                                    ->getProperty('data')->asArray();  


            $facebook_ids = array();
        
            //Get the facebookid entry for each friend matching to the friend id
            foreach ($user_friends as $friend){
                array_push($facebook_ids, $friend->id);
            }

            //Add self facebookid to the list
            array_push($facebook_ids, $user_profile->getId());

            //Execute the query to select the thread entities which are created by users with
            //the facebook ids present in the above facebook_ids array   

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT t
                FROM Threadgab\Bundle\PortalBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\LoginBundle\Entity\ThreadgabUser u
                WHERE u.facebookid in (:facebookId)
                ORDER BY t.createdAt'
            )->setParameter('facebookId', implode(',', $facebook_ids));

            $threads = $query->getResult();

            //Render the friends thread for each of the friends and yourself
    		return $this->render('PortalBundle:Portal:main.html.twig', array('threads' => $threads));

		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Main portal Page.");
		}      
    }

    public function communityAction()
    {
    	//Write code for the community threads to be shown here  

    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);

    		return $this->render('PortalBundle:Portal:community.html.twig', array('name' => $user_profile->getFirstName()));
        } else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Community portal Page.");
		}  
    }

    public function globalAction()
    {
    	//Write code for the global threads to be shown here

    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
		
    		return $this->render('PortalBundle:Portal:global.html.twig', array('name' => $user_profile->getFirstName()));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Global portal Page.");
		}  
    }

    public function groupsAction()
    {
    	//Write code for the groups to be shown here

    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
		
    		return $this->render('PortalBundle:Portal:groups.html.twig', array('name' => $user_profile->getFirstName()));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Groups portal Page.");
		}  
    }
}
