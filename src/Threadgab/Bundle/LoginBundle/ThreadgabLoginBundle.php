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
	  const appId = "709757379061795";
	  const appSecret = "4c042d1893188398229315625fb309eb";

	  function __construct() {
  		  //Initialize the Facebook Session
      	FacebookSession::setDefaultApplication(self::appId, self::appSecret);
   	}

   	public static function getLoginUrl() {
     		//Redirect to Facebook login URL for logging in
      	$helper = new FacebookRedirectLoginHelper(self::REDIRECT_URL);
  		  $loginUrl = $helper->getLoginUrl(array('email', 'user_friends', 'public_profile', 'user_photos'));

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

    public static function getFacebookFriends($session) {
        $user_friends = (new FacebookRequest(
          $session, 'GET', '/me/friends'
        ))->execute()->getGraphObject();
    
        return $user_friends;
    }

    public static function getFacebookProfile($session) {
        $user_profile = (new FacebookRequest(
          $session, 'GET', '/me'
        ))->execute()->getGraphObject(GraphUser::className());
    
        return $user_profile;
    }

    public static function getFacebookRawProfile($session) {
        $user_profile = (new FacebookRequest(
          $session, 'GET', '/me'
        ))->execute()->getGraphObject();
    
        return $user_profile;
    }

    public static function getFacebookPhoto($session) {
        $user_profile_photo = (new FacebookRequest(
          $session,
          'GET',
          '/me/picture',
          array (
            'redirect' => false,
            'height' => '200',
            'type' => 'normal',
            'width' => '200',
          )
        ))->execute()->getGraphObject();

        return $user_profile_photo;
    }
}
