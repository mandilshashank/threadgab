<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabSubforum
 */
class ThreadgabSubforum
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subForumName;


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
     * Set subForumName
     *
     * @param string $subForumName
     * @return ThreadgabSubforum
     */
    public function setSubForumName($subForumName)
    {
        $this->subForumName = $subForumName;

        return $this;
    }

    /**
     * Get subForumName
     *
     * @return string 
     */
    public function getSubForumName()
    {
        return $this->subForumName;
    }
}
