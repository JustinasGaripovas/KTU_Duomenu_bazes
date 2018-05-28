<?php

namespace App\Entity;

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
}
