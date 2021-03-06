<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkillStudent
 *
 * @ORM\Table(name="skill_student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SkillStudentRepository")
 */
class SkillStudent
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
     * @ORM\Column(name="grade", type="integer")
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="skills")
     */
    private $student;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Skill", inversedBy="students")
     */
    private $skill;


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
     * Set grade
     *
     * @param integer $grade
     *
     * @return SkillStudent
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set student
     *
     * @param string $student
     *
     * @return SkillStudent
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return string
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set skill
     *
     * @param string $skill
     *
     * @return SkillStudent
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return string
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
