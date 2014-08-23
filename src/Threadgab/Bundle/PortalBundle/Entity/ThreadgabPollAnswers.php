<?php

namespace Threadgab\Bundle\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPollAnswers
 *
 * @ORM\Table(name="threadgab_poll_answers", indexes={@ORM\Index(name="fk_question_id_idx", columns={"poll_question_id"})})
 * @ORM\Entity
 */
class ThreadgabPollAnswers
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
     * @ORM\Column(name="answer_body", type="string", length=5000, nullable=false)
     */
    private $answerBody;

    /**
     * @var integer
     *
     * @ORM\Column(name="votes", type="bigint", nullable=false)
     */
    private $votes;

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
     * @var \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll
     *
     * @ORM\ManyToOne(targetEntity="Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="poll_question_id", referencedColumnName="id")
     * })
     */
    private $pollQuestion;



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
     * Set answerBody
     *
     * @param string $answerBody
     * @return ThreadgabPollAnswers
     */
    public function setAnswerBody($answerBody)
    {
        $this->answerBody = $answerBody;

        return $this;
    }

    /**
     * Get answerBody
     *
     * @return string 
     */
    public function getAnswerBody()
    {
        return $this->answerBody;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     * @return ThreadgabPollAnswers
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes
     *
     * @return integer 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ThreadgabPollAnswers
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
     * @return ThreadgabPollAnswers
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
     * Set pollQuestion
     *
     * @param \Threadgab\Bundle\PortalBundle\Entity\ThreadgabPoll $pollQuestion
     * @return ThreadgabPollAnswers
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
}
