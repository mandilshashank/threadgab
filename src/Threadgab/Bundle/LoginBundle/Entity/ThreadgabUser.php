<?php

namespace Threadgab\Bundle\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabUser
 *
 * @ORM\Table(name="threadgab_user")
 * @ORM\Entity
 */
class ThreadgabUser
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
     * @ORM\Column(name="facebookId", type="string", length=50, nullable=false)
     */
    private $facebookid;

    /**
     * @var string
     *
     * @ORM\Column(name="emailId", type="string", length=100, nullable=false)
     */
    private $emailid;

    /**
     * @var integer
     *
     * @ORM\Column(name="zipcode", type="integer", nullable=false)
     */
    private $zipcode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
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
