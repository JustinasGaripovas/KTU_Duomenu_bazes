<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InspectionRepository")
 */
class Inspection
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
     * @ORM\Column(type="text")
     */
    private $Note;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $RepairDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Username;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $Date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DoneJobs", mappedBy="inspection", cascade={"persist"})
     */
    private $Job;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadSectionBegin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadSectionEnd;

    /**
     * @ORM\Column(type="integer")
     */
    private $SubUnitId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsAdditional;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RoadCondition;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $WaveSize;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Place;

    public function __construct()
    {
        $this->Job = new ArrayCollection();
    }

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

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(string $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

    public function getRepairDate(): ?\DateTimeInterface
    {
        return $this->RepairDate;
    }

    public function setRepairDate(?\DateTimeInterface $RepairDate): self
    {
        $this->RepairDate = $RepairDate;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    /**
     * @return Collection|DoneJobs[]
     */
    public function getJob(): Collection
    {
        return $this->Job;
    }

    public function addJob(DoneJobs $job): self
    {
        if (!$this->Job->contains($job)) {
            $this->Job[] = $job;
            $job->setInspection($this);
        }

        return $this;
    }

    public function removeJob(DoneJobs $job): self
    {
        if ($this->Job->contains($job)) {
            $this->Job->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getInspection() === $this) {
                $job->setInspection(null);
            }
        }

        return $this;
    }

    public function getRoadSectionBegin(): ?string
    {
        return $this->RoadSectionBegin;
    }

    public function setRoadSectionBegin(string $RoadSectionBegin): self
    {
        $this->RoadSectionBegin = $RoadSectionBegin;

        return $this;
    }

    public function getRoadSectionEnd(): ?string
    {
        return $this->RoadSectionEnd;
    }

    public function setRoadSectionEnd(string $RoadSectionEnd): self
    {
        $this->RoadSectionEnd = $RoadSectionEnd;

        return $this;
    }

    public function getSubUnitId(): ?int
    {
        return $this->SubUnitId;
    }

    public function setSubUnitId(int $SubUnitId): self
    {
        $this->SubUnitId = $SubUnitId;

        return $this;
    }

    public function getIsAdditional(): ?bool
    {
        return $this->IsAdditional;
    }

    public function setIsAdditional(bool $IsAdditional): self
    {
        $this->IsAdditional = $IsAdditional;

        return $this;
    }

    public function getRoadCondition(): ?string
    {
        return $this->RoadCondition;
    }

    public function setRoadCondition(?string $RoadCondition): self
    {
        $this->RoadCondition = $RoadCondition;

        return $this;
    }

    public function getWaveSize(): ?string
    {
        return $this->WaveSize;
    }

    public function setWaveSize(?string $WaveSize): self
    {
        $this->WaveSize = $WaveSize;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->Place;
    }

    public function setPlace(?string $Place): self
    {
        $this->Place = $Place;

        return $this;
    }
}
