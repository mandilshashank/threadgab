<?php

// src/Threadgab/Bundle/LoginBundle/User.php
namespace Threadgab\Bundle\LoginBundle;

class User
{
    protected $facebookId;
    protected $threadgabId;
    protected $emailId;
    protected $zipcode;

    public function getFacebookId()
    {
        return $this->facebookId;
    }

    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    public function getThreadgabId()
    {
        return $this->threadgabId;
    }

    public function setThreadgabId($threadgabId)
    {
        $this->threadgabId = $threadgabId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }

    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }
}
