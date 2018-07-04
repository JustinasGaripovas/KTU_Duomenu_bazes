<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InsuredEventRepository")
 */
class InsuredEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Subunit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadName;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionBegin;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionEnd;

    /**
     * @ORM\Column(type="text")
     */
    private $DamagedStuff;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Documents;

    /**
     * @ORM\Column(type="float")
     */
    private $EstimateToCompany;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $InsurensCompany;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NumberOfDamage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DamageData;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PayoutDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PayoutAmount;

    public function getId()
    {
        return $this->id;
    }

    public function getSubunit(): ?string
    {
        return $this->Subunit;
    }

    public function setSubunit(string $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }

    public function getRoadId(): ?string
    {
        return $this->RoadId;
    }

    public function setRoadId(string $RoadId): self
    {
        $this->RoadId = $RoadId;

        return $this;
    }

    public function getRoadName(): ?string
    {
        return $this->RoadName;
    }

    public function setRoadName(string $RoadName): self
    {
        $this->RoadName = $RoadName;

        return $this;
    }

    public function getSectionBegin(): ?float
    {
        return $this->SectionBegin;
    }

    public function setSectionBegin(float $SectionBegin): self
    {
        $this->SectionBegin = $SectionBegin;

        return $this;
    }

    public function getSectionEnd(): ?float
    {
        return $this->SectionEnd;
    }

    public function setSectionEnd(float $SectionEnd): self
    {
        $this->SectionEnd = $SectionEnd;

        return $this;
    }

    public function getDamagedStuff(): ?string
    {
        return $this->DamagedStuff;
    }

    public function setDamagedStuff(string $DamagedStuff): self
    {
        $this->DamagedStuff = $DamagedStuff;

        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->Documents;
    }

    public function setDocuments(string $Documents): self
    {
        $this->Documents = $Documents;

        return $this;
    }

    public function getEstimateToCompany(): ?float
    {
        return $this->EstimateToCompany;
    }

    public function setEstimateToCompany(float $EstimateToCompany): self
    {
        $this->EstimateToCompany = $EstimateToCompany;

        return $this;
    }

    public function getInsurensCompany(): ?string
    {
        return $this->InsurensCompany;
    }

    public function setInsurensCompany(string $InsurensCompany): self
    {
        $this->InsurensCompany = $InsurensCompany;

        return $this;
    }

    public function getNumberOfDamage(): ?string
    {
        return $this->NumberOfDamage;
    }

    public function setNumberOfDamage(string $NumberOfDamage): self
    {
        $this->NumberOfDamage = $NumberOfDamage;

        return $this;
    }

    public function getDamageData(): ?\DateTimeInterface
    {
        return $this->DamageData;
    }

    public function setDamageData(\DateTimeInterface $DamageData): self
    {
        $this->DamageData = $DamageData;

        return $this;
    }

    public function getPayoutDate(): ?\DateTimeInterface
    {
        return $this->PayoutDate;
    }

    public function setPayoutDate(?\DateTimeInterface $PayoutDate): self
    {
        $this->PayoutDate = $PayoutDate;

        return $this;
    }

    public function getPayoutAmount(): ?string
    {
        return $this->PayoutAmount;
    }

    public function setPayoutAmount(?string $PayoutAmount): self
    {
        $this->PayoutAmount = $PayoutAmount;

        return $this;
    }
}
