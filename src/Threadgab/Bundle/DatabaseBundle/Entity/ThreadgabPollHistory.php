<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabPollHistory
 */
class ThreadgabPollHistory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPollAnswers
     */
    private $pollAnswer;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll
     */
    private $pollQuestion;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
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
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPollAnswers $pollAnswer
     * @return ThreadgabPollHistory
     */
    public function setPollAnswer(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPollAnswers $pollAnswer = null)
    {
        $this->pollAnswer = $pollAnswer;

        return $this;
    }

    /**
     * Get pollAnswer
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPollAnswers 
     */
    public function getPollAnswer()
    {
        return $this->pollAnswer;
    }

    /**
     * Set pollQuestion
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabPoll $pollQuestion
     * @return ThreadgabPollHistory
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

    /**
     * Set user
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $user
     * @return ThreadgabPollHistory
     */
    public function setUser(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser 
     */
    public function getUser()
    {
        return $this->user;
    }
}
