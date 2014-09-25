<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabReply
 *
 * @ORM\Table(name="threadgab_reply", indexes={@ORM\Index(name="fk_reply_user_id_idx", columns={"reply_user"}), @ORM\Index(name="fk_reply_thd_id_idx", columns={"thd_id"})})
 * @ORM\Entity
 */
class ThreadgabReply
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="reply_to", type="bigint", nullable=false)
     */
    private $replyTo=0;

    /**
     * @var string
     *
     * @ORM\Column(name="reply_data", type="string", length=5000, nullable=false)
     */
    private $replyData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabThread")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="thd_id", referencedColumnName="id")
     * })
     */
    private $thd;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reply_user", referencedColumnName="id")
     * })
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

    public function readData(){
        $reply_data = '';
        while(!feof($this->getReplyData())){
            $reply_data.= fread($this->getReplyData(), 1024);
        }
        rewind($this->getReplyData());
        return $reply_data;
    }
}
