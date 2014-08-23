<?php

namespace Threadgab\Bundle\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    		$user_profile = (new FacebookRequest(
		      $session, 'GET', '/me'
		    ))->execute()->getGraphObject(GraphUser::className());
		
    		return $this->render('PortalBundle:Portal:main.html.twig', array('name' => (string)$user_profile->getFirstName()));
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

    		$user_profile = (new FacebookRequest(
		      $session, 'GET', '/me'
		    ))->execute()->getGraphObject(GraphUser::className());

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

    		$user_profile = (new FacebookRequest(
		      $session, 'GET', '/me'
		    ))->execute()->getGraphObject(GraphUser::className());
		
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

    		$user_profile = (new FacebookRequest(
		      $session, 'GET', '/me'
		    ))->execute()->getGraphObject(GraphUser::className());
		
    		return $this->render('PortalBundle:Portal:groups.html.twig', array('name' => $user_profile->getFirstName()));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Groups portal Page.");
		}  
    }
}
