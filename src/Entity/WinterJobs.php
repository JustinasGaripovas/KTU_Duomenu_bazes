<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterJobsRepository")
 */
class WinterJobs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $Subunit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Mechanism;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Salt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Sand;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Mix;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Solution;

    /**
     * @ORM\Column(type="time")
     */
    private $TimeFrom;

    /**
     * @ORM\Column(type="time")
     */
    private $TimeTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Job;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $SaltChecked;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $SandChecked;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $MixChecked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $SolutionChecked;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CreatedBy;

    /**
     * @ORM\Column(type="array")
     */
    private $RoadSections = [];

    public function addRoadSection($additionalSection)
    {
        $this->RoadSections[] = $additionalSection;
        return $this->RoadSections;
    }

    public function getRoadSectionsStringArray()
    {
        $str = array();
        foreach ($this->RoadSections as $road)
        {
            $str[] = $road->getSectionId() . " ( ". $road->getSectionBegin() . "km -> " . $road->getSectionEnd() . "km )";
        }
        return $str;
    }

    public function getRoadSections(): ?array
    {
        return $this->RoadSections;
    }

    public function setRoadSections(array $RoadSections): self
    {
        $this->RoadSections = $RoadSections;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getSubunit(): ?int
    {
        return $this->Subunit;
    }

    public function setSubunit(int $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }

    public function getMechanism(): ?string
    {
        return $this->Mechanism;
    }

    public function setMechanism(string $Mechanism): self
    {
        $this->Mechanism = $Mechanism;

        return $this;
    }

    public function getSalt(): ?float
    {
        return $this->Salt;
    }

    public function setSalt(?float $Salt): self
    {
        $this->Salt = $Salt;

        return $this;
    }

    public function getSand(): ?float
    {
        return $this->Sand;
    }

    public function setSand(?float $Sand): self
    {
        $this->Sand = $Sand;

        return $this;
    }

    public function getMix(): ?float
    {
        return $this->Mix;
    }

    public function setMix(?float $Mix): self
    {
        $this->Mix = $Mix;

        return $this;
    }

    public function getSolution(): ?float
    {
        return $this->Solution;
    }

    public function setSolution(?float $Solution): self
    {
        $this->Solution = $Solution;

        return $this;
    }

    public function getTimeFrom(): ?\DateTimeInterface
    {
        return $this->TimeFrom;
    }

    public function setTimeFrom(\DateTimeInterface $TimeFrom): self
    {
        $this->TimeFrom = $TimeFrom;

        return $this;
    }

    public function getTimeTo(): ?\DateTimeInterface
    {
        return $this->TimeTo;
    }

    public function setTimeTo(\DateTimeInterface $TimeTo): self
    {
        $this->TimeTo = $TimeTo;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->Job;
    }

    public function setJob(string $Job): self
    {
        $this->Job = $Job;

        return $this;
    }

    public function setRoadSectionSearch(string $RoadSectionSearch): self
    {
        $this->RoadSectionSearch = $RoadSectionSearch;

        return $this;
    }

    public function getSaltChecked(): ?bool
    {
        return $this->SaltChecked;
    }

    public function setSaltChecked(?bool $SaltChecked): self
    {
        $this->SaltChecked = $SaltChecked;

        return $this;
    }

    public function getSandChecked(): ?bool
    {
        return $this->SandChecked;
    }

    public function setSandChecked(?bool $SandChecked): self
    {
        $this->SandChecked = $SandChecked;

        return $this;
    }

    public function getMixChecked(): ?bool
    {
        return $this->MixChecked;
    }

    public function setMixChecked(?bool $MixChecked): self
    {
        $this->MixChecked = $MixChecked;

        return $this;
    }

    public function getSolutionChecked(): ?bool
    {
        return $this->SolutionChecked;
    }

    public function setSolutionChecked(bool $SolutionChecked): self
    {
        $this->SolutionChecked = $SolutionChecked;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(string $CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

        return $this;
    }
}
