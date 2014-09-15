<?php

namespace Threadgab\Bundle\DatabaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadgabSubforum
 *
 * @ORM\Table(name="threadgab_subforum")
 * @ORM\Entity
 */
class ThreadgabSubforum
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_forum_name", type="string", length=100, nullable=false)
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
