<?php

namespace Threadgab\Bundle\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPollHistory
 *
 * @ORM\Table(name="threadgab_poll_history", indexes={@ORM\Index(name="fk_answer_id_idx", columns={"poll_answer_id"}), @ORM\Index(name="fk_user_id_idx", columns={"user_id"}), @ORM\Index(name="fk_question_id_idx", columns={"poll_question_id"})})
 * @ORM\Entity
 */
class ThreadgabPollHistory
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
     * @var \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPollAnswers
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\PortalBundle\Entity\ThreadgabPollAnswers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="poll_answer_id", referencedColumnName="id")
     * })
     */
    private $pollAnswer;

    /**
     * @var \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="poll_question_id", referencedColumnName="id")
     * })
     */
    private $pollQuestion;

    /**
     * @var \Threadgab\Bundle\PortalBundle\Entity\ThreadgabUser
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\PortalBundle\Entity\ThreadgabUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ThreadgabPollHistory
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
     * @return ThreadgabPollHistory
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
     * Set pollAnswer
     *
     * @param \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPollAnswers $pollAnswer
     * @return ThreadgabPollHistory
     */
    public function setPollAnswer(\Threadgab\Bundle\PortalBundle\Entity\ThreadgabPollAnswers $pollAnswer = null)
    {
        $this->pollAnswer = $pollAnswer;

        return $this;
    }

    /**
     * Get pollAnswer
     *
     * @return \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPollAnswers 
     */
    public function getPollAnswer()
    {
        return $this->pollAnswer;
    }

    /**
     * Set pollQuestion
     *
     * @param \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll $pollQuestion
     * @return ThreadgabPollHistory
     */
    public function setPollQuestion(\Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll $pollQuestion = null)
    {
        $this->pollQuestion = $pollQuestion;

        return $this;
    }

    /**
     * Get pollQuestion
     *
     * @return \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll 
     */
    public function getPollQuestion()
    {
        return $this->pollQuestion;
    }

    /**
     * Set user
     *
     * @param \Threadgab\Bundle\PortalBundle\Entity\ThreadgabUser $user
     * @return ThreadgabPollHistory
     */
    public function setUser(\Threadgab\Bundle\PortalBundle\Entity\ThreadgabUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Threadgab\Bundle\PortalBundle\Entity\ThreadgabUser 
     */
    public function getUser()
    {
        return $this->user;
    }
}
