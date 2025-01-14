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
    private $Role = 'WORKER';

    /**
     * @ORM\Column(type="integer")
     */
    private $Inspection = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $DoneJobs = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $Restrictions = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $Winter = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $InsuredEvent = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $Reports = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $Flood = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $ContractJobs =1;

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

    public function getFlood(): ?int
    {
        return $this->Flood;
    }

    public function setFlood(int $Flood): self
    {
        $this->Flood = $Flood;

        return $this;
    }

    public function getContractJobs(): ?int
    {
        return $this->ContractJobs;
    }

    public function setContractJobs(int $ContractJobs): self
    {
        $this->ContractJobs = $ContractJobs;

        return $this;
    }
}
