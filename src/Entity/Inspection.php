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
}
