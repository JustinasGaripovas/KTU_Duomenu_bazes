<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoneJobsRepository")
 */
class DoneJobs
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
    private $JobId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $JobName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadSection;

    /**
     * @ORM\Column(type="float")
     */
    private $RoadSectionBegin;

    /**
     * @ORM\Column(type="float")
     */
    private $RoadSectionEnd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $UnitOf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Username;

    /**
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     */
    private $IsDeleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DoneJobDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Note;


    public function getId()
    {
        return $this->id;
    }

    public function getJobId(): ?string
    {
        return $this->JobId;
    }

    public function setJobId(string $JobId): self
    {
        $this->JobId = $JobId;

        return $this;
    }

    public function getJobName(): ?string
    {
        return $this->JobName;
    }

    public function setJobName(string $JobName): self
    {
        $this->JobName = $JobName;

        return $this;
    }

    public function getRoadSection(): ?string
    {
        return $this->RoadSection;
    }

    public function setRoadSection(string $RoadSection): self
    {
        $this->RoadSection = $RoadSection;

        return $this;
    }

    public function getRoadSectionBegin(): ?float
    {
        return $this->RoadSectionBegin;
    }

    public function setRoadSectionBegin(float $RoadSectionBegin): self
    {
        $this->RoadSectionBegin = $RoadSectionBegin;

        return $this;
    }

    public function getRoadSectionEnd(): ?float
    {
        return $this->RoadSectionEnd;
    }

    public function setRoadSectionEnd(float $RoadSectionEnd): self
    {
        $this->RoadSectionEnd = $RoadSectionEnd;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->Quantity;
    }

    public function setQuantity(string $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->IsDeleted;
    }

    public function setIsDeleted(bool $IsDeleted): self
    {
        $this->IsDeleted = $IsDeleted;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDoneJobDate(): ?\DateTimeInterface
    {
        return $this->DoneJobDate;
    }

    public function setDoneJobDate(\DateTimeInterface $DoneJobDate): self
    {
        $this->DoneJobDate = $DoneJobDate;

        return $this;
    }

    public function getUnitOf(): ?string
    {
        return $this->UnitOf;
    }

    public function setUnitOf(?string $UnitOf): self
    {
        $this->UnitOf = $UnitOf;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(?string $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

}
