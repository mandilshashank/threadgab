<?php

namespace Threadgab\Bundle\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser;
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
    public function mainAction($currentforum)
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

            //TBD : Check why this doesnt work and the following works
            /*$em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.thdCreator = u.id
                WHERE u.facebookid in (:fdId)
                ORDER BY t.createdAt"
            );
            $query->setParameter("fbId",implode(',', $facebook_ids));*/

            $em = $this->getDoctrine()->getManager();
            
            //Get the friend thread for a specific user friends and a specific subforum
            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.thdCreator = u.id
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum v
                WITH t.thdSubforum = v.id
                WHERE u.facebookid in (".implode(',', $facebook_ids).")  
                    and (t.thdType='friend' or t.thdType='global')
                    and v.subForumName='".$currentforum."'
                ORDER BY t.createdAt"
            );

            $query_subforum = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );

            $threads = $query->getResult();
            $subforum  = $query_subforum->getResult();

            //Render the friends thread for each of the friends and yourself
    		return $this->render('PortalBundle:Portal:main.html.twig', 
                array('threads' => $threads, 'subforum' => $subforum,
                    'currentforum' => $currentforum));

		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Main portal Page.");
		}      
    }

    public function communityAction($currentforum)
    {
    	//Write code for the community threads to be shown here  

    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.thdCreator = u.id
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum w
                WITH t.thdSubforum = w.id
                WHERE u.zipcode=
                (   select v.zipcode from
                    Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser v
                    WHERE v.facebookid='".$user_profile->getId()."'
                )
                    and (t.thdType='community' or t.thdType='global')
                    and w.subForumName='".$currentforum."'
                ORDER BY t.createdAt"
            );

            $query_subforum = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );

            $threads = $query->getResult();
            $subforum  = $query_subforum->getResult();

            //return new Response($query->getSql());

    		//Render the community thread for each of the friends and yourself
            return $this->render('PortalBundle:Portal:community.html.twig', 
                array('threads' => $threads, 'subforum' => $subforum,
                    'currentforum' => $currentforum));
        } else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Community portal Page.");
		}  
    }

    public function globalAction($currentforum)
    {
    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
		
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum w
                WITH t.thdSubforum = w.id
                WHERE t.thdType='global' 
                    and w.subForumName='".$currentforum."'
                ORDER BY t.createdAt"
            );

            $query_subforum = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );

            $threads = $query->getResult();
            $subforum  = $query_subforum->getResult();

    		return $this->render('PortalBundle:Portal:global.html.twig', 
                array('threads' => $threads, 'subforum' => $subforum,
                    'currentforum' => $currentforum));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Global portal Page.");
		}  
    }

    public function groupsAction($currentforum)
    {
    	//Write code for the groups to be shown here

    	//Get the user data using the fb_token session variable

    	$session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
    	if($session) {

    		$user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
		
            $em = $this->getDoctrine()->getManager();
            $query_subforum = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );

            $subforum  = $query_subforum->getResult();

    		return $this->render('PortalBundle:Portal:groups.html.twig', 
                array('name' => $user_profile->getFirstName(),'subforum' => $subforum,
                 'currentforum' => $currentforum));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Groups portal Page.");
		}  
    }

    public function profileAction()
    {
        //Write code for the groups to be shown here

        //Get the user data using the fb_token session variable

        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookRawProfile($session)->asArray();
            $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 100, 100)->asArray();

            return $this->render('PortalBundle:Portal:profilepage.html.twig', 
                array('user_profile' => $user_profile, 
                    'user_profile_photo' => $user_profile_photo));
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the Groups portal Page.");
        }  
    }
}
