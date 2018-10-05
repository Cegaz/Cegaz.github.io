<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\SanctionReason;

/**
 * Sanction
 *
 * @ORM\Table(name="sanction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SanctionRepository")
 */
class Sanction
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
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="sanctions")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="SanctionReason", inversedBy="sanctions")
     */
    private $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="alertDate", type="datetime", nullable=true)
     */
    private $alertDate;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="string", length=255, nullable=true)
     */
    private $details;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set student
     *
     * @param integer $student
     *
     * @return Sanction
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return int
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set alertDate
     *
     * @param \DateTime $alertDate
     *
     * @return Sanction
     */
    public function setAlertDate($alertDate)
    {
        $this->alertDate = $alertDate;

        return $this;
    }

    /**
     * Get alertDate
     *
     * @return \DateTime
     */
    public function getAlertDate()
    {
        return $this->alertDate;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return Sanction
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Sanction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reason
     *
     * @param SanctionReason $reason
     *
     * @return Sanction
     */
    public function setReason(SanctionReason $reason = null)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return SanctionReason
     */
    public function getReason()
    {
        return $this->reason;
    }
}
