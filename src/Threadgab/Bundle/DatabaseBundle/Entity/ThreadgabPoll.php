<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPoll
 */
class ThreadgabPoll
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $pollBody;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pollBody
     *
     * @param string $pollBody
     * @return ThreadgabPoll
     */
    public function setPollBody($pollBody)
    {
        $this->pollBody = $pollBody;

        return $this;
    }

    /**
     * Get pollBody
     *
     * @return string 
     */
    public function getPollBody()
    {
        return $this->pollBody;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ThreadgabPoll
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
     * @return ThreadgabPoll
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
     * @return ThreadgabPoll
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
}
