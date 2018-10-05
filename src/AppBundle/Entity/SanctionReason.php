<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SanctionReason
 *
 * @ORM\Table(name="sanction_reason")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SanctionReasonRepository")
 */
class SanctionReason
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
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Sanction", mappedBy="reason")
     */
    private $sanctions;


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
     * Set name
     *
     * @param string $name
     *
     * @return SanctionReason
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sanctions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sanction
     *
     * @param Sanction $sanction
     *
     * @return SanctionReason
     */
    public function addSanction(Sanction $sanction)
    {
        $this->sanctions[] = $sanction;

        return $this;
    }

    /**
     * Remove sanction
     *
     * @param Sanction $sanction
     */
    public function removeSanction(Sanction $sanction)
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
        return $this->getName();
    }
}
