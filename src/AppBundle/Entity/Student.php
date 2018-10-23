<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Repository\StudentRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student
{
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=25, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=25, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=10, nullable=true)
     * @Assert\Regex(
     *     pattern = "/^0[1-79][0-9]{8}$/",
     *     message = "Le numéro de téléphone n'est pas valide.")
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=true)
     * @Assert\Email(message = "L'email '{{ value }}' n'est pas valide.")
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $isDeleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="Classs", inversedBy="students")
     */
    private $class;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="student")
     */
    private $participations;

    /**
     * @ORM\ManyToOne(targetEntity="Island", inversedBy="students")
     */
    private $island;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="student")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="SkillStudent", mappedBy="student")
     */
    private $skills;

    /**
     * @ORM\OneToMany(targetEntity="Grade", mappedBy="student")
     */
    private $grades;

    /**
     * @ORM\OneToMany(targetEntity="Absence", mappedBy="student")
     */
    private $absences;

    /**
     * @ORM\OneToMany(targetEntity="Sanction", mappedBy="student")
     */
    private $sanctions;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Student
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Student
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add participation
     *
     * @param Participation $participation
     *
     * @return Student
     */
    public function addParticipation(Participation $participation)
    {
        $this->participations[] = $participation;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param Participation $participation
     */
    public function removeParticipation(Participation $participation)
    {
        $this->participations->removeElement($participation);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

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
     * Constructor
     */
    public function __construct()
    {
        $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set island
     *
     * @param \AppBundle\Entity\Island $island
     *
     * @return Student
     */
    public function setIsland(Island $island = null)
    {
        $this->island = $island;

        return $this;
    }

    /**
     * Get island
     *
     * @return \AppBundle\Entity\Island
     */
    public function getIsland()
    {
        return $this->island;
    }


    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Student
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Student
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted
     *
     * @param boolean isDeleted
     *
     * @return Student
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Add skill
     *
     * @param \AppBundle\Entity\SkillStudent $skill
     *
     * @return Student
     */
    public function addSkill(\AppBundle\Entity\SkillStudent $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \AppBundle\Entity\SkillStudent $skill
     */
    public function removeSkill(\AppBundle\Entity\SkillStudent $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add grade
     *
     * @param \AppBundle\Entity\Grade $grade
     *
     * @return Student
     */
    public function addGrade(\AppBundle\Entity\Grade $grade)
    {
        $this->grades[] = $grade;

        return $this;
    }

    /**
     * Remove grade
     *
     * @param \AppBundle\Entity\Grade $grade
     */
    public function removeGrade(\AppBundle\Entity\Grade $grade)
    {
        $this->grades->removeElement($grade);
    }

    /**
     * Get grades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGrades()
    {
        return $this->grades;
    }


    /**
     * Add absence
     *
     * @param \AppBundle\Entity\Absence $absence
     *
     * @return Student
     */
    public function addAbsence(\AppBundle\Entity\Absence $absence)
    {
        $this->absences[] = $absence;

        return $this;
    }

    /**
     * Remove absence
     *
     * @param \AppBundle\Entity\Absence $absence
     */
    public function removeAbsence(\AppBundle\Entity\Absence $absence)
    {
        $this->absences->removeElement($absence);
    }

    /**
     * Get absences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbsences()
    {
        return $this->absences;
    }

    /**
     * Add sanction
     *
     * @param \AppBundle\Entity\Sanction $sanction
     *
     * @return Student
     */
    public function addSanction(\AppBundle\Entity\Sanction $sanction)
    {
        $this->sanctions[] = $sanction;

        return $this;
    }

    /**
     * Remove sanction
     *
     * @param \AppBundle\Entity\Sanction $sanction
     */
    public function removeSanction(\AppBundle\Entity\Sanction $sanction)
    {
        $this->sanctions->removeElement($sanction);
    }

    /**
     * Get sanctions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSanctions()
    {
        return $this->sanctions;
    }

    public function __toString()
    {
        return $this->getLastName() . ' ' . $this->getFirstName();
    }
}
