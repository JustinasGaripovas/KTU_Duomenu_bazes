<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LdapUserRepository")
 */
class LdapUser
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subunit", inversedBy="ldapUsers")
     */
    private $Subunit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\Column(type="integer")
     */
    private $Inspection;

    /**
     * @ORM\Column(type="integer")
     */
    private $DoneJobs;

    /**
     * @ORM\Column(type="integer")
     */
    private $Restrictions;

    /**
     * @ORM\Column(type="integer")
     */
    private $Winter;

    /**
     * @ORM\Column(type="integer")
     */
    private $Ldap;

    /**
     * @ORM\Column(type="integer")
     */
    private $Mechanism;

    /**
     * @ORM\Column(type="integer")
     */
    private $RoadSection;

    /**
     * @ORM\Column(type="integer")
     */
    private $Job;

    /**
     * @ORM\Column(type="integer")
     */
    private $InsuredEvent;

    /**
     * @ORM\Column(type="integer")
     */
    private $Reports;

    public function getInspection(): ?int
    {
        return $this->Inspection;
    }

    public function setInspection(int $Inspection): self
    {
        $this->Inspection = $Inspection;

        return $this;
    }

    public function getDoneJobs(): ?int
    {
        return $this->DoneJobs;
    }

    public function setDoneJobs(int $DoneJobs): self
    {
        $this->DoneJobs = $DoneJobs;

        return $this;
    }

    public function getRestrictions(): ?int
    {
        return $this->Restrictions;
    }

    public function setRestrictions(int $Restrictions): self
    {
        $this->Restrictions = $Restrictions;

        return $this;
    }

    public function getWinter(): ?int
    {
        return $this->Winter;
    }

    public function setWinter(int $Winter): self
    {
        $this->Winter = $Winter;

        return $this;
    }

    public function getLdap(): ?int
    {
        return $this->Ldap;
    }

    public function setLdap(int $Ldap): self
    {
        $this->Ldap = $Ldap;

        return $this;
    }

    public function getMechanism(): ?int
    {
        return $this->Mechanism;
    }

    public function setMechanism(int $Mechanism): self
    {
        $this->Mechanism = $Mechanism;

        return $this;
    }

    public function getRoadSection(): ?int
    {
        return $this->RoadSection;
    }

    public function setRoadSection(int $RoadSection): self
    {
        $this->RoadSection = $RoadSection;

        return $this;
    }

    public function getJob(): ?int
    {
        return $this->Job;
    }

    public function setJob(int $Job): self
    {
        $this->Job = $Job;

        return $this;
    }

    public function getInsuredEvent(): ?int
    {
        return $this->InsuredEvent;
    }

    public function setInsuredEvent(int $InsuredEvent): self
    {
        $this->InsuredEvent = $InsuredEvent;

        return $this;
    }

    public function getReports(): ?int
    {
        return $this->Reports;
    }

    public function setReports(int $Reports): self
    {
        $this->Reports = $Reports;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    public function getSubunit(): ?Subunit
    {
        return $this->Subunit;
    }

    public function setSubunit(?Subunit $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }
}
