<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPoll
 *
 * @ORM\Table(name="threadgab_poll", indexes={@ORM\Index(name="fk_thd_id_idx", columns={"thd_id"})})
 * @ORM\Entity
 */
class ThreadgabPoll
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
     * @ORM\Column(name="poll_body", type="string", length=5000, nullable=false)
     */
    private $pollBody;

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
