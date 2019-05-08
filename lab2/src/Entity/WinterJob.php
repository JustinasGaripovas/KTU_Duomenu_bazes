<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterJobRepository")
 */
class WinterJob
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
     * @ORM\Column(type="datetime")
     */
    private $FinishedAt;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=5)
     */
    private $EstimatedCost;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=5)
     */
    private $ActualCost;

    /**
     * @ORM\Column(type="float")
     */
    private $Temperature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $GeneralCondition;

    /**
     * @ORM\Column(type="float")
     */
    private $MoistureLevel;

    /**
     * @ORM\Column(type="float")
     */
    private $PressureLevel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="fk_winterjob")
     */
    private $jobs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoadSection", mappedBy="fk_winterjob")
     */
    private $roadSections;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mechanism", mappedBy="fk_winterjob")
     */
    private $mechanisms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Materials", mappedBy="fk_winterjob")
     */
    private $materials;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="WinterJobs")
     */
    private $people;

    /**
     * @ORM\Column(type="datetime")
     */
    private $StartedAt;


    public function __toString()
    {
       return "{$this->id}";
    }

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->roadSections = new ArrayCollection();
        $this->mechanisms = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->FinishedAt;
    }

    public function setFinishedAt(\DateTimeInterface $FinishedAt): self
    {
        $this->FinishedAt = $FinishedAt;

        return $this;
    }

    public function getEstimatedCost()
    {
        return $this->EstimatedCost;
    }

    public function setEstimatedCost($EstimatedCost): self
    {
        $this->EstimatedCost = $EstimatedCost;

        return $this;
    }

    public function getActualCost()
    {
        return $this->ActualCost;
    }

    public function setActualCost($ActualCost): self
    {
        $this->ActualCost = $ActualCost;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->Temperature;
    }

    public function setTemperature(float $Temperature): self
    {
        $this->Temperature = $Temperature;

        return $this;
    }

    public function getGeneralCondition(): ?string
    {
        return $this->GeneralCondition;
    }

    public function setGeneralCondition(string $GeneralCondition): self
    {
        $this->GeneralCondition = $GeneralCondition;

        return $this;
    }

    public function getMoistureLevel(): ?float
    {
        return $this->MoistureLevel;
    }

    public function setMoistureLevel(float $MoistureLevel): self
    {
        $this->MoistureLevel = $MoistureLevel;

        return $this;
    }

    public function getPressureLevel(): ?float
    {
        return $this->PressureLevel;
    }

    public function setPressureLevel(float $PressureLevel): self
    {
        $this->PressureLevel = $PressureLevel;

        return $this;
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setFkWinterjob($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getFkWinterjob() === $this) {
                $job->setFkWinterjob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RoadSection[]
     */
    public function getRoadSections(): Collection
    {
        return $this->roadSections;
    }

    public function addRoadSection(RoadSection $roadSection): self
    {
        if (!$this->roadSections->contains($roadSection)) {
            $this->roadSections[] = $roadSection;
            $roadSection->setFkWinterjob($this);
        }

        return $this;
    }

    public function removeRoadSection(RoadSection $roadSection): self
    {
        if ($this->roadSections->contains($roadSection)) {
            $this->roadSections->removeElement($roadSection);
            // set the owning side to null (unless already changed)
            if ($roadSection->getFkWinterjob() === $this) {
                $roadSection->setFkWinterjob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mechanism[]
     */
    public function getMechanisms(): Collection
    {
        return $this->mechanisms;
    }

    public function addMechanism(Mechanism $mechanism): self
    {
        if (!$this->mechanisms->contains($mechanism)) {
            $this->mechanisms[] = $mechanism;
            $mechanism->setFkWinterjob($this);
        }

        return $this;
    }

    public function removeMechanism(Mechanism $mechanism): self
    {
        if ($this->mechanisms->contains($mechanism)) {
            $this->mechanisms->removeElement($mechanism);
            // set the owning side to null (unless already changed)
            if ($mechanism->getFkWinterjob() === $this) {
                $mechanism->setFkWinterjob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Materials[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Materials $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
            $material->setFkWinterjob($this);
        }

        return $this;
    }

    public function removeMaterial(Materials $material): self
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
            // set the owning side to null (unless already changed)
            if ($material->getFkWinterjob() === $this) {
                $material->setFkWinterjob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addWinterJob($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeWinterJob($this);
        }

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->StartedAt;
    }

    public function setStartedAt(\DateTimeInterface $StartedAt): self
    {
        $this->StartedAt = $StartedAt;

        return $this;
    }

}
