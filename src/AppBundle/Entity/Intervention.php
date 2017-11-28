<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Intervention
 *
 * @ORM\Table(name="intervention")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InterventionRepository")
 */
class Intervention
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="intervention_date", type="datetime", nullable=false)
     */
    private $interventionDate = 'CURRENT_TIMESTAMP';

    /**
     *
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="interventions")
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
     * Set intervention
     *
     * @param \DateTime $intervention
     *
     * @return Intervention
     */
    public function setIntervention($intervention)
    {
        $this->intervention = $intervention;

        return $this;
    }

    /**
     * Get intervention
     *
     * @return \DateTime
     */
    public function getIntervention()
    {
        return $this->intervention;
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
     * Set interventionDate
     *
     * @param \DateTime $interventionDate
     *
     * @return Intervention
     */
    public function setInterventionDate($interventionDate)
    {
        $this->interventionDate = $interventionDate;

        return $this;
    }

    /**
     * Get interventionDate
     *
     * @return \DateTime
     */
    public function getInterventionDate()
    {
        return $this->interventionDate;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Students $student
     *
     * @return Intervention
     */
    public function setStudent(\AppBundle\Entity\Students $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Students
     */
    public function getStudent()
    {
        return $this->student;
    }
}
