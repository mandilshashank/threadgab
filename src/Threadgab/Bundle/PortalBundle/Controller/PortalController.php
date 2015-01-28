<?php

namespace Threadgab\Bundle\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubscriptions;
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

            $em = $this->getDoctrine()->getManager();
            
            //Get the friend thread for a specific user friends and a specific subforum
            $query = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.thdCreator = u.id
                WHERE u.facebookid in (".implode(',', $facebook_ids).")
                    and (t.thdIsfriend='1')
                ORDER BY t.updatedAt desc"
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
                array('threads' => $threads, 'subforum' => $subforum
                    , 'currentforum'=>$currentforum));

		} else {
			//Session not found. Take to a common error page
			return new Response("Session not found at the Main portal Page.");
		}      
    }

    public function frontpageAction($currentforum)
    {
        //Get the user data using the fb_token session variable

        //Check if the fb_token session variable is available and then proceed is it is.
        if(!isset($_SESSION['fb_token']))
        {
            //Session not found. Take to a common error page
            return new Response("FB Token not found at the Front Page. Please relogin into Facebook at the main website page");
        }

        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookProfile($session);

            $em = $this->getDoctrine()->getManager();

            //Get the friend thread for a specific user friends and a specific subforum
            $query = $em->createQuery(
                "SELECT t, count(t) as thread_count
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.thdCreator = u.id
                LEFT OUTER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply r
                WITH r.thd = t.id
                WHERE t.thdIsglobal='1'
                GROUP BY t
                order by thread_count desc, t.updatedAt desc"
            );

            $query_subforum = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum t
                ORDER BY t.id"
            );

            $threads = $query->getResult();
            $subforum  = $query_subforum->getResult();

            //Render the friends thread for each of the friends and yourself
            return $this->render('PortalBundle:Portal:frontpage.html.twig',
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
                ORDER BY t.updatedAt desc"
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
                    ORDER BY t.updatedAt desc"
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
                ORDER BY t.updatedAt desc"
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

    public function threadviewAction(Request $request,$threadid,$currentforum,$frompage)
    {
        //Get the user data using the fb_token session variable
        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        if($session) {

            $user_profile = ThreadgabLoginBundle::getFacebookProfile($session);
            $user_profile_photo = ThreadgabLoginBundle::getFacebookPhoto($session, 50, 50)->asArray();
            $user_friends = ThreadgabLoginBundle::getFacebookFriends($session)
                ->getProperty('data')->asArray();
            $friend_facebook_ids = array();

            //Get the facebookid entry for each friend matching to the friend id
            foreach ($user_friends as $friend){
                array_push($friend_facebook_ids, $friend->id);
            }

            //var_dump($user_profile);

            //Get the current user
            $repository = $this->getDoctrine()->getRepository('ThreadgabDatabaseBundle:ThreadgabUser');
            $query = $repository->createQueryBuilder('p')
                            ->where('p.facebookid = :facebookId')
                            ->setParameter('facebookId', (string)$user_profile->getId())
                            ->getQuery();
            $users = $query->getResult();

            //Get the thread entity for the passed threadid
            $em = $this->getDoctrine()->getManager();
            $query_thread = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread t
                WHERE t.id=".$threadid
            );

            $thread  = $query_thread->getResult();

            //Get the users subscribed by the current user
            $subscribed_users = PortalBundle::getSubscribedUsers($users[0]->getId(), $em);

            //Delete the current thread if you receive confirmation from the thread owner to do it
            //Redirect to the page from where the user came to this threadview page
            if(isset($_POST['button_delete_thread'])){
                $em->remove($thread[0]);
                $em->flush();

                //Redirect
                $url = $this->generateUrl('portal_'.$frompage,array('currentforum'=>$currentforum));
                return $this->redirect($url);
            }

            if(isset($_POST['input_thd_title']) and $_POST['input_thd_title']!=""){
                $thread[0]->setThdSubject($_POST['input_thd_title']);
                $em->persist($thread[0]);
                $em->flush();
            }

            if(isset($_POST['textarea_desc']) and $_POST['textarea_desc']!=""){
                $thread[0]->setThdDesc($_POST['textarea_desc']);
                $em->persist($thread[0]);
                $em->flush();
            }

            if(isset($_POST) and isset($_GET['rid']) and isset($_GET['uid'])) {
                $reply_id = $_GET['rid'];
                $user_id = $_GET['uid'];

                if(isset($_POST['button_'.$reply_id.'_'.$user_id])) {

                    $query_users = $em->createQuery(
                        "SELECT u
                        FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                        WHERE u.id=" . $user_id
                    );

                    $user_sig_change = $query_users->getResult();
                    if ($user_sig_change[0] != null) {
                        $user_sig_change[0]->setSignature($_POST['ta_sig_' . $reply_id . '_' . $user_id]);
                        $em->persist($user_sig_change[0]);
                        $em->flush();
                    }
                }
            }

            $query_replies = $em->createQuery(
                "SELECT t
                FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply t
                INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                WITH t.replyUser=u.id
                WHERE t.thd=".$threadid.
                " ORDER BY t.id asc"

            );

            $replies  = $query_replies->getResult();

            $new_reply=new ThreadgabReply();
            $new_reply->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
            $new_reply->setThd($thread[0]);
            $new_reply->setReplyUser($users[0]);

            $form = $this->createFormBuilder($new_reply)
                    ->add('replyData', 'textarea', array('label' => 'Reply to Thread', 'attr' => array('class' => 'tinymce')))
                    ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'form-control')))
                    ->getForm();

            //Check if the subscribe button is pressed and do necesssary action
            if(isset($_POST['button_subscribe']) and $_POST['button_subscribe']=='Subscribe') {
                $query_new_subscribee_user = $em->createQuery(
                    "SELECT u
                    FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
                    WHERE u.id=".$_POST['subscribee']
                );
                $new_subscribee_user=$query_new_subscribee_user->getResult();

                $new_subscription = new ThreadgabSubscriptions();
                $new_subscription->setSubscriber($users[0]);
                $new_subscription->setSubscribee($new_subscribee_user[0]);

                $em->persist($new_subscription);
                $em->flush();

                $subscribed_users = PortalBundle::getSubscribedUsers($users[0]->getId(), $em);

                //Return back to the same thread page
                return $this->render('PortalBundle:Portal:threadview.html.twig',
                    array('threads' => $thread[0],'currentforum' => $currentforum,
                        'user_profile_photo' => $user_profile_photo, 'replies' => $replies,
                        'form' => $form->createView(), 'user_profile'=>$user_profile, 'subscribed_to'=> $subscribed_users,
                        'friend_facebook_ids'=>$friend_facebook_ids, 'frompage'=>$frompage));
            }

            //Check if the unsubscribed button is pressed and do necesssary action
            if(isset($_POST['button_unsubscribe']) and $_POST['button_unsubscribe']=='Unsubscribe') {
                $query_delete_subscription = $em->createQuery(
                    "SELECT s
                    FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubscriptions s
                    WHERE s.subscribee=".$_POST['subscribee'].
                    "and  s.subscriber=".$users[0]->getId()
                );
                $delete_subscription=$query_delete_subscription->getResult();

                $em->remove($delete_subscription[0]);
                $em->flush();

                $subscribed_users = PortalBundle::getSubscribedUsers($users[0]->getId(), $em);

                //Return back to the same thread page
                return $this->render('PortalBundle:Portal:threadview.html.twig',
                    array('threads' => $thread[0],'currentforum' => $currentforum,
                        'user_profile_photo' => $user_profile_photo, 'replies' => $replies,
                        'form' => $form->createView(), 'user_profile'=>$user_profile, 'subscribed_to'=> $subscribed_users,
                        'friend_facebook_ids'=>$friend_facebook_ids, 'frompage'=>$frompage));
            }

            if ($request->isMethod('POST') and isset($_GET['mainid'])) {
                $form->submit($request->request->get($form->getName()));
                
                if ($form->isValid()) {

                    //If the user has intended to upload a file
                    if($_FILES["fileToUpload"]["name"]!="") {

                        //Generate a new unique filename for the upload
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/documents/";
                        $target_file = $target_dir . $_FILES["fileToUpload"]["name"];
                        $new_image_name = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . pathinfo($target_file, PATHINFO_EXTENSION);
                        //End Generate a new unique filename for the image

                        //Upload the document
                        $final_target_file = $target_dir . $new_image_name;
                        $uploadOk = 1;
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        // Check if image file is a actual image or fake image
                        if (isset($_POST["submit"])) {
                            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                            if ($check !== false) {
//                                var_dump( "File is an image - " . $check["mime"] . ".");
                                $uploadOk = 1;
                            } else {
//                                var_dump( "File is not an image.");
                                $uploadOk = 0;
                            }
                        }

                        // Check if file already exists
                        if (file_exists($final_target_file)) {
//                            var_dump( "Sorry, file already exists.");
                            $uploadOk = 0;
                        }
                        // Check file size
                        if ($_FILES["fileToUpload"]["size"] > 50000000) {
//                            var_dump( "Sorry, your file is too large.");
                            $uploadOk = 0;
                        }
                        // Allow certain file formats
                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif"
                        ) {
//                            var_dump( "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                            $uploadOk = 0;
                        }

                        //Todo : Convert all errors into meaningful display messages

                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $final_target_file)) {
//                                var_dump( "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
                            } else {
//                                var_dump( "Sorry, there was an error uploading your file.");
                            }
                        }
                        //End Upload the document

                        //Save the new filepath into the reply database
                        $new_reply->setReplyImagePath("/uploads/documents/".$new_image_name);
                    }

                    //Persist the reply to the database
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($new_reply);
                    $em->flush();

                    //Return back to the same thread page
                    $url = $this->generateUrl('portal_thread', 
                        array('threadid'=>$threadid,'currentforum'=>$currentforum, 'user_profile'=>$user_profile
                        , 'subscribed_to'=> $subscribed_users, 'friend_facebook_ids'=>$friend_facebook_ids, 'frompage'=>$frompage));
                    return $this->redirect($url."?id=0");
                }   
            }

            if(isset($_POST) && isset($_GET['id'])){
                //Check if the edit_reply post variable is present
                //If not its a quoted reply request
                if(isset($_POST['edit_reply'])){
                    //Edit a reply
                    $form_id=$_GET['id'];
                    if($form_id!=0) {
                        //Get the reply element first
                        $query_reply_edit = $em->createQuery(
                            "SELECT r
                            FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply r
                            WHERE r.id=".$form_id
                        );

                        $replies  = $query_reply_edit->getResult();
                        //Check if the reply exists
                        if(sizeof($replies)!=0)
                        {
                            $replies[0]->setReplyData($_POST['textarea_' . $form_id . '_edit']);
                        }

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($replies[0]);
                        $em->flush();
                    }
                } else {
                    //Create a new quoted reply
                    $form_id=$_GET['id'];
                    if($form_id!=0) {
                        $dynamic_reply = new ThreadgabReply();
                        $dynamic_reply->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
                        $dynamic_reply->setThd($thread[0]);
                        $dynamic_reply->setReplyUser($users[0]);
                        $dynamic_reply->setReplyTo($_POST['reply_to_' . $form_id]);
                        $dynamic_reply->setReplyData($_POST['textarea_' . $form_id]);

                        if($_FILES["fileToUpload_".$form_id]["name"]!="") {

                            //Generate a new unique filename for the upload
                            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/documents/";
                            $target_file = $target_dir . $_FILES["fileToUpload_".$form_id]["name"];
                            $new_image_name = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . pathinfo($target_file, PATHINFO_EXTENSION);
                            //End Generate a new unique filename for the image

                            //Upload the document
                            $final_target_file = $target_dir . $new_image_name;
                            $uploadOk = 1;
                            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                            // Check if image file is a actual image or fake image
                            if (isset($_POST["submit"])) {
                                $check = getimagesize($_FILES["fileToUpload_".$form_id]["tmp_name"]);
                                if ($check !== false) {
//                                var_dump( "File is an image - " . $check["mime"] . ".");
                                    $uploadOk = 1;
                                } else {
//                                var_dump( "File is not an image.");
                                    $uploadOk = 0;
                                }
                            }

                            // Check if file already exists
                            if (file_exists($final_target_file)) {
//                            var_dump( "Sorry, file already exists.");
                                $uploadOk = 0;
                            }
                            // Check file size
                            if ($_FILES["fileToUpload_".$form_id]["size"] > 50000000) {
//                            var_dump( "Sorry, your file is too large.");
                                $uploadOk = 0;
                            }
                            // Allow certain file formats
                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif"
                            ) {
//                            var_dump( "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                                $uploadOk = 0;
                            }

                            //Todo : Convert all errors into meaningful display messages

                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 1) {
                                if (move_uploaded_file($_FILES["fileToUpload_".$form_id]["tmp_name"], $final_target_file)) {
//                                var_dump( "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
                                } else {
//                                var_dump( "Sorry, there was an error uploading your file.");
                                }
                            }
                            //End Upload the document

                            //Save the new filepath into the reply database
                            $dynamic_reply->setReplyImagePath("/uploads/documents/".$new_image_name);
                        }

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($dynamic_reply);
                        $em->flush();
                    }
                }

                $url = $this->generateUrl('portal_thread',
                        array('threadid'=>$threadid,'currentforum'=>$currentforum, 'user_profile'=>$user_profile
                        , 'subscribed_to'=> $subscribed_users, 'friend_facebook_ids'=>$friend_facebook_ids, 'frompage'=>$frompage));
                return $this->redirect($url."?id=0");

            }

            //If a thread is a poll we need to show different kind of visualization

            return $this->render('PortalBundle:Portal:threadview.html.twig', 
                array('threads' => $thread[0],'currentforum' => $currentforum,
                    'user_profile_photo' => $user_profile_photo, 'replies' => $replies,
                    'form' => $form->createView(), 'user_profile'=>$user_profile, 'subscribed_to'=> $subscribed_users,
                    'friend_facebook_ids'=>$friend_facebook_ids, 'frompage'=>$frompage));
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the subscribed portal Page.");
        }
    }

    public function threadcreateAction(Request $request,$currentforum)
    {
        $session = ThreadgabLoginBundle::getSessionFromToken($_SESSION['fb_token']);
        $final_thread="";
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

            if(isset($_POST['submit_button'])){
                if(!isset($_POST['thd_subject']) or !isset($_POST['thd_desc']) or trim($_POST['thd_subject'])=="" 
                    or trim(strip_tags($_POST['thd_desc']))=="") {
                    return $this->render('PortalBundle:Portal:threadcreate.html.twig', 
                        array('subforums' => $subforums, 'currentforum'=>$currentforum ,'error'=>'Either the Thread Subject 
                            or Description not specified','POST'=>$_POST));
                }

                //This means it is a poll thread and we need to check if there are any answers present or not before submitting
                               

                $em = $this->getDoctrine()->getManager();

                if($_POST['thread_is_poll']==1)
                {
                    $isAnswerPresent=false;
                    foreach( $_POST as $key => $val )
                    {
                        //Check if any postvariables are present or not
                        if (strpos($key,'poll_input_') !== false and $val!="") {
                            $isAnswerPresent=true;
                        }
                    }
                    if(!$isAnswerPresent){
                        return $this->render('PortalBundle:Portal:threadcreate.html.twig', 
                        array('subforums' => $subforums, 'currentforum'=>$currentforum ,'error'=>'No Answers or empty answers were provided
                            for the Poll type thread','POST'=>$_POST));
                    } 
                }

                if(isset($_POST['thd_rch_subscribed'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'subscribed', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                    PortalBundle::createThreadPolls($final_thread,$em);
                } 
                if(isset($_POST['thd_rch_friend'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'friends', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                    PortalBundle::createThreadPolls($final_thread,$em);
                }
                if(isset($_POST['thd_rch_community'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'community', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                    PortalBundle::createThreadPolls($final_thread,$em);
                }
                if(isset($_POST['thd_rch_global'])) {
                    $final_thread = PortalBundle::createAndPersistThread($subforums, 'global', $em, $_POST['subforum'],
                        $users[0], $_POST['thd_subject'], $_POST['thd_desc'], $_POST['thread_is_poll'], $_POST['thd_label']);
                    PortalBundle::createThreadPolls($final_thread,$em);
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
            if(isset($_POST['thd_label'])){
                return $this->render('PortalBundle:Portal:threadcreate.html.twig',
                    array('subforums' => $subforums,'currentforum'=>$currentforum,'POST'=>$_POST));
            } else {
                return $this->render('PortalBundle:Portal:threadcreate.html.twig',
                    array('subforums' => $subforums,'currentforum'=>$currentforum));
            }
        } else {
            //Session not found. Take to a common error page
            return new Response("Session not found at the subscribed portal Page.");
        } 
    }
}
