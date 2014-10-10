<?php

namespace Threadgab\Bundle\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread;
use Symfony\Component\HttpFoundation\Response;
use Threadgab\Bundle\LoginBundle\ThreadgabLoginBundle;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Symfony\Component\HttpFoundation\Request;
use Threadgab\Bundle\PortalBundle\PortalBundle;

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

        //Check if the fb_token session variable is available and then proceed is it is.
        if(!isset($_SESSION['fb_token']))
        {
            //Session not found. Take to a common error page
            return new Response("FB Token not found at the Main portal Page. Please relogin into Facebook at the main website page");
        }

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
                    and (t.thdIsfriend='1')
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
                    and (t.thdIscommunity='1')
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
                WHERE t.thdIsglobal='1' 
                    and w.subForumName='".$currentforum."'
                ORDER BY t.createdAt"
            );

            //Query to get all the subforums from the database
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

    public function subscribedAction($currentforum)
    {
    	//Write code for the subscribed to be shown here

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

            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubscriptions u
                WITH t.thdCreator = u.subscribee
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser v 
                WITH u.subscriber = v.id
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum w
                WITH t.thdSubforum = w.id    
                WHERE v.facebookid = '".$user_profile->getId()."'  
                    and (t.thdIssubscribed='1')
                    and w.subForumName='".$currentforum."'
                ORDER BY t.createdAt"
            );

            $threads = $query->getResult();

            $subforum  = $query_subforum->getResult();

    		return $this->render('PortalBundle:Portal:subscribed.html.twig', 
                array('threads' => $threads,'subforum' => $subforum,
                 'currentforum' => $currentforum));
		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the subscribed portal Page.");
		}  
    }

    public function profileAction()
    {
        //Write code for the subscribed to be shown here

        //Get the user data using the fb_token session variable

        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookRawProfile($session)->asArray();
            $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 100, 100)->asArray();

            $repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');
            $query = $repository->createQueryBuilder('p')
                            ->where('p.facebookid = :facebookId')
                            ->setParameter('facebookId', $user_profile['id'])
                            ->getQuery();
            $users = $query->getResult();

            if($users!=null) {

                if(isset($_POST['user_signature'])) {
                    $users[0]->setSignature($_POST['user_signature']);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($users[0]);
                    $em->flush();                    
                }

                return $this->render('PortalBundle:Portal:profilepage.html.twig', 
                array('user_profile_photo' => $user_profile_photo, 'user' => $users[0]));
            }

            return new Response('User with current facebook session not found');
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the subscribed portal Page.");
        }  
    }

    public function viewprofileAction($userid)
    {
        //Get the current user
        $repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');
        $query = $repository->createQueryBuilder('p')
                        ->where('p.id = :userId')
                        ->setParameter('userId', $userid)
                        ->getQuery();
        $users = $query->getResult();

        if($users != null) {
            return $this->render('PortalBundle:Portal:viewprofilepage.html.twig', 
                array('user' => $users[0]));
        }
    }


    public function threadviewAction(Request $request,$threadid,$currentforum)
    {
        //Write code for the subscribed to be shown here

        //Get the user data using the fb_token session variable

        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
            $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 50, 50)->asArray();
        
            //Get the current user
            $repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');
            $query = $repository->createQueryBuilder('p')
                            ->where('p.facebookid = :facebookId')
                            ->setParameter('facebookId', (string)$user_profile->getId())
                            ->getQuery();
            $users = $query->getResult();

            $em = $this->getDoctrine()->getManager();
            $query_thread = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                WHERE t.id=".$threadid
            );

            $thread  = $query_thread->getResult();

            //if($thread==null or $users==null or $thread[0]->getThdCreator()!=$users[0]) {
            //    $url = $this->generateUrl('portal_homepage', array('currentforum' => $currentforum));
            //    return $this->redirect($url);   
            //}

            $query_replies = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.replyUser=u.id
                WHERE t.thd=".$threadid.
                " ORDER BY t.replyTo,t.id ASC"

            );

            $final_reply_array=array();
            $final_reply_array[0]=array();
            $reply_id_check_array = array();

            $replies  = $query_replies->getResult();

            //return new Response("Noresponse");

            foreach ($replies as $reply) {
                if(!in_array($reply->getId(), $reply_id_check_array)) {
                   $final_reply_array[$reply->getId()]=array();
                   array_push($reply_id_check_array, $reply->getId());
                }
                array_push($final_reply_array[$reply->getReplyTo()], $reply); 
            } 

            $new_reply=new ThreadgabReply();
            $new_reply->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
            $new_reply->setThd($thread[0]);
            $new_reply->setReplyUser($users[0]);


            $form = $this->createFormBuilder($new_reply)
                    ->add('replyData', 'textarea', array('label' => 'Reply to Thread', 'attr' => array('class' => 'tinymce')))
                    ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'form-control')))
                    ->getForm();

            if ($request->isMethod('POST')) {
                $form->submit($request->request->get($form->getName()));
                
                if ($form->isValid()) {
                    //Persist the user to the database
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($new_reply);
                    $em->flush();

                    //Return back to the same thread page
                    $url = $this->generateUrl('portal_thread', 
                        array('threadid'=>$threadid,'currentforum'=>$currentforum));
                    return $this->redirect($url."?id=0");
                }   
            }

            if(isset($_POST) && isset($_GET['id'])){
                $form_id=$_GET['id'];
                if($form_id!=0) {
                $dynamic_reply=new ThreadgabReply();
                $dynamic_reply->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
                $dynamic_reply->setThd($thread[0]);
                $dynamic_reply->setReplyUser($users[0]);
                $dynamic_reply->setReplyTo($_POST['reply_to_'.$form_id]);
                $dynamic_reply->setReplyData($_POST['textarea_'.$form_id]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($dynamic_reply);
                $em->flush();

                $url = $this->generateUrl('portal_thread', 
                        array('threadid'=>$threadid,'currentforum'=>$currentforum));
                return $this->redirect($url."?id=0");
                }
            }

            //Get all the replies to this thread and also the replies to those replies

            //If a thread is a poll we need to show different kind of visualization

            return $this->render('PortalBundle:Portal:threadview.html.twig', 
                array('threads' => $thread[0],'currentforum' => $currentforum,
                    'user_profile_photo' => $user_profile_photo, 'replies' => $final_reply_array,
                    'form' => $form->createView()));
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the subscribed portal Page.");
        } 

        //return new Response("Nothing in this page right now... Need to build the backend queries and the frontend threadview 
        //   page for this.. Make this page according to the thread view page skeleton provided by Mark"); 
    }

    public function threadcreateAction(Request $request,$currentforum)
    {

        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        $final_thread;
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
            $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 50, 50)->asArray();
        
            //Get the current user
            $repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');
            $query = $repository->createQueryBuilder('p')
                            ->where('p.facebookid = :facebookId')
                            ->setParameter('facebookId', (string)$user_profile->getId())
                            ->getQuery();
            $users = $query->getResult();

            $query_subforum = $this->getDoctrine()->getManager()->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );
            $subforums = $query_subforum->getResult();

            /*$form = $this->createFormBuilder($new_thread)
                    ->add('thdSubject', 'text', array('label'=> 'Thread Subject', 'attr' => array('class' => 'form-control')))
                    ->add('thdDesc', 'textarea', array('label' => 'Thread Description', 'attr' => array('class' => 'tinymce')))
                    ->add('isPoll', 'choice', array('choices' => array(0 => 'No', 1 => 'Yes'),'multiple' => false,
                        'expanded' => false, 'required' => true,'label' => 'Is it a Poll Thread'))
                    ->add('thdSubforum','entity',array('class' => 'ThreadgabDatabaseBundle:ThreadgabSubforum', 
                        'property' => 'subForumName', 'multiple'=>false, 'expanded'=>false, 'label' => 'Thread Subforum',
                        'attr' => array('class' => 'form-control')))
                    ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'form-control')))
                    ->getForm();

            if ($request->isMethod('POST')) {
                $form->submit($request->request->get($form->getName()));

                if ($form->isValid()) {
                        //Persist the user to the database
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($new_thread);
                        $em->flush();

                        //Return back to the same thread page
                        $url = $this->generateUrl('portal_thread', 
                            array('threadid'=>$new_thread->getId(),
                                'currentforum'=>$new_thread->getThdSubforum()->getSubForumName()));
                        return $this->redirect($url);   
                } 
            }*/

            if(isset($_POST['submit_button'])){
                if(!isset($_POST['thd_subject']) or !isset($_POST['thd_desc']) or trim($_POST['thd_subject'])=="" 
                    or trim(strip_tags($_POST['thd_desc']))=="") {
                    return $this->render('PortalBundle:Portal:threadcreate.html.twig', 
                        array('subforums' => $subforums, 'currentforum'=>$currentforum ,'error'=>'true'));
                }                

                $em = $this->getDoctrine()->getManager();

                if(isset($_POST['thd_rch_subscribed'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'subscribed', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                } 
                if(isset($_POST['thd_rch_friend'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'friends', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                }
                if(isset($_POST['thd_rch_community'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'community', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                }
                if(isset($_POST['thd_rch_global'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'global', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                }
                                                               
                
                //Unset the post variables before redirect
                unset($_POST);

                //Return back to the same thread page
                $url = $this->generateUrl('portal_homepage', 
                            array('currentforum'=>$final_thread->getThdSubforum()->getSubForumName()));
                return $this->redirect($url);   
            }

            //Get all the replies to this thread and also the replies to those replies

            //If a thread is a poll we need to show different kind of visualization

            return $this->render('PortalBundle:Portal:threadcreate.html.twig', 
                array('subforums' => $subforums,'currentforum'=>$currentforum));
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the subscribed portal Page.");
        } 
    }
}
