<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabReply
 */
class ThreadgabReply
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $replyTo;

    /**
     * @var string
     */
    private $replyData;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread
     */
    private $thd;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
     */
    private $replyUser;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set replyTo
     *
     * @param integer $replyTo
     * @return ThreadgabReply
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Get replyTo
     *
     * @return integer 
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set replyData
     *
     * @param string $replyData
     * @return ThreadgabReply
     */
    public function setReplyData($replyData)
    {
        $this->replyData = $replyData;

        return $this;
    }

    /**
     * Get replyData
     *
     * @return string 
     */
    public function getReplyData()
    {
        return $this->replyData;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ThreadgabReply
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ThreadgabReply
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set thd
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread $thd
     * @return ThreadgabReply
     */
    public function setThd(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread $thd = null)
    {
        $this->thd = $thd;

        return $this;
    }

    /**
     * Get thd
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread 
     */
    public function getThd()
    {
        return $this->thd;
    }

    /**
     * Set replyUser
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $replyUser
     * @return ThreadgabReply
     */
    public function setReplyUser(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $replyUser = null)
    {
        $this->replyUser = $replyUser;

        return $this;
    }

    /**
     * Get replyUser
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser 
     */
    public function getReplyUser()
    {
        return $this->replyUser;
    }
}
