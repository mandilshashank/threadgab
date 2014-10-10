<?php

namespace Threadgab\Bundle\PortalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabReply;
use Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread;

class PortalBundle extends Bundle
{
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
}
