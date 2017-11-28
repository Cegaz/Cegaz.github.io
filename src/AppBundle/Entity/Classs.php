<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="class_letter", type="string", length=2)
     */
    private $classLetter;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="class")
     */
    private $students;


    public function __toString()
    {
        return $this->getClassLetter();
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
     * Set classLetter
     *
     * @param string $classLetter
     *
     * @return Classs
     */
    public function setClassLetter($classLetter)
    {
        $this->classLetter = $classLetter;

        return $this;
    }

    /**
     * Get classLetter
     *
     * @return string
     */
    public function getClassLetter()
    {
        return $this->classLetter;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add student
     *
     * @param \AppBundle\Entity\Students $student
     *
     * @return Classs
     */
    public function addStudent(\AppBundle\Entity\Students $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Students $student
     */
    public function removeStudent(\AppBundle\Entity\Students $student)
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
}
