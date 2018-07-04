<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestrictionRepository")
 */
class Restriction
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
    private $RoadId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SectionBegin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SectionEnd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Jobs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Restrictions;

    /**
     * @ORM\Column(type="date")
     */
    private $DateFrom;

    /**
     * @ORM\Column(type="date")
     */
    private $DateTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Notes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Contractor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RestrictionStatus", inversedBy="restrictions")
     */
    private $Status;


    public function getId()
    {
        return $this->id;
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

    public function getSectionBegin(): ?string
    {
        return $this->SectionBegin;
    }

    public function setSectionBegin(string $SectionBegin): self
    {
        $this->SectionBegin = $SectionBegin;

        return $this;
    }

    public function getSectionEnd(): ?string
    {
        return $this->SectionEnd;
    }

    public function setSectionEnd(string $SectionEnd): self
    {
        $this->SectionEnd = $SectionEnd;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->Place;
    }

    public function setPlace(string $Place): self
    {
        $this->Place = $Place;

        return $this;
    }

    public function getJobs(): ?string
    {
        return $this->Jobs;
    }

    public function setJobs(string $Jobs): self
    {
        $this->Jobs = $Jobs;

        return $this;
    }

    public function getRestrictions(): ?string
    {
        return $this->Restrictions;
    }

    public function setRestrictions(string $Restrictions): self
    {
        $this->Restrictions = $Restrictions;

        return $this;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->DateFrom;
    }

    public function setDateFrom(\DateTimeInterface $DateFrom): self
    {
        $this->DateFrom = $DateFrom;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->DateTo;
    }

    public function setDateTo(\DateTimeInterface $DateTo): self
    {
        $this->DateTo = $DateTo;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->Notes;
    }

    public function setNotes(?string $Notes): self
    {
        $this->Notes = $Notes;

        return $this;
    }
    

    public function getContractor(): ?string
    {
        return $this->Contractor;
    }

    public function setContractor(?string $Contractor): self
    {
        $this->Contractor = $Contractor;

        return $this;
    }

    public function getStatus(): ?RestrictionStatus
    {
        return $this->Status;
    }

    public function setStatus(?RestrictionStatus $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

}
