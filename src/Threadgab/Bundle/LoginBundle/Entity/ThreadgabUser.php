<?php

namespace Threadgab\Bundle\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabUser
 */
class ThreadgabUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $facebookId;

    /**
     * @var string
     */
    private $emailId;

    /**
     * @var integer
     */
    private $zipcode;


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
     * Set facebookId
     *
     * @param string $facebookId
     * @return ThreadgabUser
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set emailId
     *
     * @param string $emailId
     * @return ThreadgabUser
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;

        return $this;
    }

    /**
     * Get emailId
     *
     * @return string 
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     * @return ThreadgabUser
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
}
