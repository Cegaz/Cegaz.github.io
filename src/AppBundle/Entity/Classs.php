<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Repository\ClasssRepository;

/**
 * Classs
 *
 * @ORM\Table(name="classs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClasssRepository")
 */
class Classs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=16)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="class")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="Island", mappedBy="class")
     */
    private $islands;


    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Classs
     */
    public function addStudent(Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(Student $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add island
     *
     * @param \AppBundle\Entity\Island $island
     *
     * @return Classs
     */
    public function addIsland(Island $island)
    {
        $this->islands[] = $island;

        return $this;
    }

    /**
     * Remove island
     *
     * @param Island $island
     */
    public function removeIsland(Island $island)
    {
        $this->islands->removeElement($island);
    }

    /**
     * Get islands
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIslands()
    {
        return $this->islands;
    }


    /**
     * Set label
     *
     * @param string $label
     *
     * @return Classs
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
