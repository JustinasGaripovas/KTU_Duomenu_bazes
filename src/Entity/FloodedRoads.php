<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FloodedRoadsRepository")
 */
class FloodedRoads
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
     * @ORM\Column(type="float")
     */
    private $SectionBegin;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionEnd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $WaterDeep;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Notes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SubunitId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CreatedBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActive;

    public function getId(): ?int
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

    public function getWaterDeep(): ?string
    {
        return $this->WaterDeep;
    }

    public function setWaterDeep(string $WaterDeep): self
    {
        $this->WaterDeep = $WaterDeep;

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

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
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

    public function getSubunitId(): ?string
    {
        return $this->SubunitId;
    }

    public function setSubunitId(string $SubunitId): self
    {
        $this->SubunitId = $SubunitId;

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

    public function getIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(bool $IsActive): self
    {
        $this->IsActive = $IsActive;

        return $this;
    }
}
