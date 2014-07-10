<?php

namespace Threadgab\Bundle\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Symfony\Component\HttpFoundation\Response;

ini_set('display_errors',"1");

class LoginController extends Controller
{

	const REDIRECT_URL = "http://shashank.local/app_dev.php/redirect";
	const appId = "709774645726735";
	const appSecret = "9a2e31247640bca7eec80185703427ac";

	function __construct() {
		//Initialize the Facebook Session
    	FacebookSession::setDefaultApplication(self::appId, self::appSecret);
   	}

    public function indexAction()
    {
    	//Redirect to Facebook login URL for logging in
    	$helper = new FacebookRedirectLoginHelper(self::REDIRECT_URL);
		$loginUrl = $helper->getLoginUrl(array( 'email', 'user_friends', 'public_profile' ));

		$content = $this->renderView(
		    'ThreadgabLoginBundle:Login:index.html.twig',
		    array('loginUrl' => $loginUrl)
		);

		return new Response($content);

		//return new Response("<a href='".$loginUrl."'>Login using Facebook</a>");
    }

	public function getsessionAction()
    {
    	//Catch the redirected url and find out the session information
    	$helper = new FacebookRedirectLoginHelper(self::REDIRECT_URL);
    	
		try {
		  	$session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
		 	// When Facebook returns an error
			return new Response('Got FacebookRequestException');
		} catch(Exception $ex) {
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
