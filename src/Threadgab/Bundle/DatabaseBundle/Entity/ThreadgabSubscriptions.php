<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabSubscriptions
 */
class ThreadgabSubscriptions
{
    /**
     * @var integer
     */
    private $subscriptionId;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
     */
    private $subscribee;

    /**
     * @var \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser
     */
    private $subscriber;


    /**
     * Get subscriptionId
     *
     * @return integer 
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * Set subscribee
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $subscribee
     * @return ThreadgabSubscriptions
     */
    public function setSubscribee(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $subscribee = null)
    {
        $this->subscribee = $subscribee;

        return $this;
    }

    /**
     * Get subscribee
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser 
     */
    public function getSubscribee()
    {
        return $this->subscribee;
    }

    /**
     * Set subscriber
     *
     * @param \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $subscriber
     * @return ThreadgabSubscriptions
     */
    public function setSubscriber(\Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser $subscriber = null)
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return \Threadgab\Bundle\DatabaseBundle\Entity\ThreadgabUser 
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
}
