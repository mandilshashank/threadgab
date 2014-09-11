<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabThread
 */
class ThreadgabThread
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $thdSubject;

    /**
     * @var string
     */
    private $thdDesc;

    /**
     * @var boolean
     */
    private $isPoll;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $thdType;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
     */
    private $thdCreator;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum
     */
    private $thdSubforum;


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
     * Set thdSubject
     *
     * @param string $thdSubject
     * @return ThreadgabThread
     */
    public function setThdSubject($thdSubject)
    {
        $this->thdSubject = $thdSubject;

        return $this;
    }

    /**
     * Get thdSubject
     *
     * @return string 
     */
    public function getThdSubject()
    {
        return $this->thdSubject;
    }

    /**
     * Set thdDesc
     *
     * @param string $thdDesc
     * @return ThreadgabThread
     */
    public function setThdDesc($thdDesc)
    {
        $this->thdDesc = $thdDesc;

        return $this;
    }

    /**
     * Get thdDesc
     *
     * @return string 
     */
    public function getThdDesc()
    {
        return $this->thdDesc;
    }

    /**
     * Set isPoll
     *
     * @param boolean $isPoll
     * @return ThreadgabThread
     */
    public function setIsPoll($isPoll)
    {
        $this->isPoll = $isPoll;

        return $this;
    }

    /**
     * Get isPoll
     *
     * @return boolean 
     */
    public function getIsPoll()
    {
        return $this->isPoll;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ThreadgabThread
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
     * @return ThreadgabThread
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
     * Set thdType
     *
     * @param string $thdType
     * @return ThreadgabThread
     */
    public function setThdType($thdType)
    {
        $this->thdType = $thdType;

        return $this;
    }

    /**
     * Get thdType
     *
     * @return string 
     */
    public function getThdType()
    {
        return $this->thdType;
    }

    /**
     * Set thdCreator
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $thdCreator
     * @return ThreadgabThread
     */
    public function setThdCreator(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $thdCreator = null)
    {
        $this->thdCreator = $thdCreator;

        return $this;
    }

    /**
     * Get thdCreator
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser 
     */
    public function getThdCreator()
    {
        return $this->thdCreator;
    }

    /**
     * Set thdSubforum
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum $thdSubforum
     * @return ThreadgabThread
     */
    public function setThdSubforum(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum $thdSubforum = null)
    {
        $this->thdSubforum = $thdSubforum;

        return $this;
    }

    /**
     * Get thdSubforum
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabSubforum 
     */
    public function getThdSubforum()
    {
        return $this->thdSubforum;
    }
}
