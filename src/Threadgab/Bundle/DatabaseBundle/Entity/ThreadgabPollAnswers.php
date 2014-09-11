<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPollAnswers
 */
class ThreadgabPollAnswers
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $answerBody;

    /**
     * @var integer
     */
    private $votes;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll
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
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll $pollQuestion
     * @return ThreadgabPollAnswers
     */
    public function setPollQuestion(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll $pollQuestion = null)
    {
        $this->pollQuestion = $pollQuestion;

        return $this;
    }

    /**
     * Get pollQuestion
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll 
     */
    public function getPollQuestion()
    {
        return $this->pollQuestion;
    }
}
