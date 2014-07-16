<?php

namespace Threadgab\Bundle\LoginBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;

class ThreadgabLoginBundle extends Bundle
{
	const REDIRECT_URL = "http://shashank.local/app_dev.php/redirect";
	const appId = "709774645726735";
	const appSecret = "9a2e31247640bca7eec80185703427ac";

	function __construct() {
		//Initialize the Facebook Session
    	FacebookSession::setDefaultApplication(self::appId, self::appSecret);
   	}

   	public static function getLoginUrl() {
   		//Redirect to Facebook login URL for logging in
    	$helper = new FacebookRedirectLoginHelper(self::REDIRECT_URL);
		$loginUrl = $helper->getLoginUrl(array( 'email', 'user_friends', 'public_profile' ));

		return $loginUrl;
   	}

   	public static function getSession() {
   		$helper = new FacebookRedirectLoginHelper(self::REDIRECT_URL);

		//Get the session id for the user
	  	$session = $helper->getSessionFromRedirect();

	  	return $session;
   	}

   	public static function getSessionFromToken($token) {
   		return new FacebookSession($token);
   	}
}
