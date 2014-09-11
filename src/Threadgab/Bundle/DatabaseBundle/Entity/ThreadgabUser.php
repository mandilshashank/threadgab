<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

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
    private $facebookid;

    /**
     * @var string
     */
    private $emailid;

    /**
     * @var integer
     */
    private $zipcode;

    /**
     * @var \DateTime
     */
    private $creationdate;


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
     * Set facebookid
     *
     * @param string $facebookid
     * @return ThreadgabUser
     */
    public function setFacebookid($facebookid)
    {
        $this->facebookid = $facebookid;

        return $this;
    }

    /**
     * Get facebookid
     *
     * @return string 
     */
    public function getFacebookid()
    {
        return $this->facebookid;
    }

    /**
     * Set emailid
     *
     * @param string $emailid
     * @return ThreadgabUser
     */
    public function setEmailid($emailid)
    {
        $this->emailid = $emailid;

        return $this;
    }

    /**
     * Get emailid
     *
     * @return string 
     */
    public function getEmailid()
    {
        return $this->emailid;
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

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     * @return ThreadgabUser
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * Get creationdate
     *
     * @return \DateTime 
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }
}
