<?php

namespace Threadgab\Bundle\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Symfony\Component\HttpFoundation\Response;

ini_set('display_errors',"1");

class DefaultController extends Controller
{

	const REDIRECT_URL = 'www.google.com';
	const appId = '709774645726735';
	const appSecret = '9a2e31247640bca7eec80185703427ac';

    public function indexAction()
    {
    	if(!isset($_SESSION)) 
    	{ 
       		session_start(); 
    	}  

    	//Redirect to Facebook login URL for logging in
    	$helper = new FacebookRedirectLoginHelper(
    		self::REDIRECT_URL,
      		self::appId,
      		self::appSecret);
		$loginUrl = $helper->getLoginUrl(array( 'email', 'user_friends' ));

		return new Response('<a href="' . $loginUrl . '">Login with Facebook</a>');
    }

	public function getsessionAction()
    {
    	//Redirect to Facebook login URL for logging in
    	$helper = new FacebookRedirectLoginHelper(
    		self::REDIRECT_URL,
      		self::appId,
      		self::appSecret);
    	
		try {
		  $session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
		  // When Facebook returns an error
			return new Response('Got FacebookRequestException');
		} catch(\Exception $ex) {
		  // When validation fails or other local issues
			return new Response('Got Some Exception');
		}
		if ($session) {
		  // Logged in
			return new Response('Awesome !! Session token is  '.$session->getToken());
		}

		return new Response('Session is not found');
    }    
}
