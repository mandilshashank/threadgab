<?php

namespace Threadgab\Bundle\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;
use Symfony\Component\HttpFoundation\Response;
use Threadgab\Bundle\LoginBundle\ThreadgabLoginBundle;

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
		  	$_SESSION['fb_token'] = $session->getToken();
		  	//If we dont get an exception here it means that the sessoin has been active and we are
		  	//now ready to redirect the user either to the user information page or to the main 
		  	//discussion forum.

		  	//We will be deciding to transfer to a forum or the user information page by making a 
		  	//database query for the user to see if he/she already exists in the database.
		  	//If they already exist to transfer them to the forum page otherwise we transfer them to the
		  	//user information page.
		  	if($session) {

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

    public function showformAction()
    {
    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {
    		return new Response($session->getToken());
    	} else {
    		return new Response("Not available");
    	}
    }
}
