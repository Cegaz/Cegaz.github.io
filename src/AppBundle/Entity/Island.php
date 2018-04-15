<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Island
 *
 * @ORM\Table(name="island")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IslandRepository")
 */
class Island
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="classs", inversedBy="islands")
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=32)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="island")
     */
    private $students;


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
     * Set class
     *
     * @param integer $class
     *
     * @return Island
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return int
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Island
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->label;
    }

    /**
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Island
     */
    public function addStudent(\AppBundle\Entity\Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(\AppBundle\Entity\Student $student)
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
