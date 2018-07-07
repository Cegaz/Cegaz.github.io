<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=16, nullable=true)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="class_id", type="integer", nullable=true)
     */
    private $classId;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set class
     *
     * @param int $classId
     *
     * @return User
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

    /**
     * Get class
     *
     * @return int
     */
    public function getClassId()
    {
        return $this->classId;
    }
}
