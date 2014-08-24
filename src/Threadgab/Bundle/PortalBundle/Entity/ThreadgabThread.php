<?php

namespace Threadgab\Bundle\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabThread
 *
 * @ORM\Table(name="threadgab_thread", indexes={@ORM\Index(name="fk_thd_creator_id_idx", columns={"thd_creator_id"})})
 * @ORM\Entity
 */
class ThreadgabThread
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="thd_subject", type="string", length=200, nullable=false)
     */
    private $thdSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="thd_desc", type="string", length=5000, nullable=false)
     */
    private $thdDesc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_poll", type="boolean", nullable=false)
     */
    private $isPoll;

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
     * @var string
     *
     * @ORM\Column(name="thd_type", type="string", length=10, nullable=false)
     */
    private $thdType;

    /**
     * @var \Threadgab/Bundle/oginbundle/ntity/ThreadgabUser
     *
     * @ORM\ManyToOne(targetEntity="Threadgab/Bundle/oginbundle/ntity/ThreadgabUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="thd_creator_id", referencedColumnName="id")
     * })
     */
    private $thdCreator;



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
     * @param Threadgab\Bundle\LoginBundle\Entity\ThreadgabUser $thdCreator
     * @return ThreadgabThread
     */
    public function setThdCreator(Threadgab\Bundle\LoginBundle\Entity\ThreadgabUser $thdCreator = null)
    {
        $this->thdCreator = $thdCreator;

        return $this;
    }

    /**
     * Get thdCreator
     *
     * @return Threadgab\Bundle\LoginBundle\Entity\ThreadgabUser
     */
    public function getThdCreator()
    {
        return $this->thdCreator;
    }
}
