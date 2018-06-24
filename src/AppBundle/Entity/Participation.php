<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Student;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="participation_date", type="datetime", nullable=false)
     */
    private $participationDate = 'CURRENT_TIMESTAMP';

    /**
     *
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="participations")
     */
    private $student;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


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
     * Set participationDate
     *
     * @param \DateTime $participationDate
     *
     * @return Participation
     */
    public function setParticipationDate($participationDate)
    {
        $this->participationDate = $participationDate;

        return $this;
    }

    /**
     * Get participationDate
     *
     * @return \DateTime
     */
    public function getParticipationDate()
    {
        return $this->participationDate;
    }

    /**
     * Set student
     *
     * @param Student $student
     *
     * @return Participation
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
