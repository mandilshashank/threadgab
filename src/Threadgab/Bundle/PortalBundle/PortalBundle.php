<?php

namespace Threadgab\Bundle\PortalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPollAnswers;

class PortalBundle extends Bundle
{
    public static function getSubscribedUsers($userid, $em){
        //Get the users subscribed by the current user
        $query_subscribed = $em->createQuery(
            "SELECT s
            FROM Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubscriptions s
            INNER JOIN Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser u
            WITH s.subscriber = u.id
            WHERE u.id = " . $userid
        );
        $subscribed_users = array();
        $subscribed_subscriptions=$query_subscribed->getResult();
        foreach ($subscribed_subscriptions as $subscription){
            array_push($subscribed_users, $subscription->getSubscribee());
        }

        return $subscribed_users;
    }

	public static function createAndPersistThread($subforums, $thread_reach, $em, $selectedForum, $user,
		$thd_subject, $thd_desc, $thd_is_poll, $thd_label)
    {
        $new_thread=new ThreadgabThread();
        $new_thread->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
        $new_thread->setUpdatedAt(date_create(date("Y-m-d H:i:s", time())));
        $new_thread->setThdCreator($user);
        $new_thread->setThdSubject($thd_subject);
        $new_thread->setThdDesc($thd_desc);
        $new_thread->setIsPoll($thd_is_poll);
        $new_thread->setThdLabel($thd_label);
        $new_thread->setNumReply('0');
        
        if($thread_reach == 'friends') {
            $new_thread->setThdIsfriend('1');
            $new_thread->setThdIscommunity('0');
            $new_thread->setThdIsglobal('0');
            $new_thread->setThdIssubscribed('0');
        } else if ($thread_reach == 'community') {
            $new_thread->setThdIsfriend('0');
            $new_thread->setThdIscommunity('1');
            $new_thread->setThdIsglobal('0');
            $new_thread->setThdIssubscribed('0');
        } else if ($thread_reach == 'global') {
            $new_thread->setThdIsfriend('0');
            $new_thread->setThdIscommunity('0');
            $new_thread->setThdIsglobal('1');
            $new_thread->setThdIssubscribed('0');
        } else if ($thread_reach == 'subscribed') {
            $new_thread->setThdIsfriend('0');
            $new_thread->setThdIscommunity('0');
            $new_thread->setThdIsglobal('0');
            $new_thread->setThdIssubscribed('1');
        } 

        foreach ($subforums as $subforum) {
            if($subforum->getId()==$selectedForum) {
                $new_thread->setThdSubforum($subforum);
                break;
            }
        }

        $em->persist($new_thread);
        $em->flush();

        return $new_thread;
    }

    public static function createThreadPolls($new_thread,$em)
    {
        //Check if the poll property is set to Poll
        if($_POST['thread_is_poll']==1)
        {
            //Add the poll to the database
            $new_poll = new ThreadgabPoll();
            $new_poll->setPollBody($new_thread->getThdDesc());
            $new_poll->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
            $new_poll->setUpdatedAt(date_create(date("Y-m-d H:i:s", time())));
            $new_poll->setThd($new_thread);

            $em->persist($new_poll);
            $em->flush();

            foreach( $_POST as $key => $val )
            {
                //Add the possible answers to the database
                if (strpos($key,'poll_input_') !== false) {
                    $new_poll_answer = new ThreadgabPollAnswers();
                    $new_poll_answer->setCreatedAt(date_create(date("Y-m-d H:i:s", time())));
                    $new_poll_answer->setUpdatedAt(date_create(date("Y-m-d H:i:s", time())));
                    $new_poll_answer->setPollQuestion($new_poll);
                    $new_poll_answer->setVotes(0);
                    $new_poll_answer->setAnswerBody($val);

                    $em->persist($new_poll_answer);
                    $em->flush();
                }
            }
        } 
    }
}
