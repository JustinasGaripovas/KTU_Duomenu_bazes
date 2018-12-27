<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoadSectionForWinterJobsRepository")
 */
class RoadSectionForWinterJobs
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
    private $SectionType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SectionId;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionBegin;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionEnd;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionLength;

    /**
     * @ORM\Column(type="integer")
     */
    private $Subunit;

    /**
     * @ORM\Column(type="float")
     */
    private $AverageWidth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $MaintenanceLevel;

    public function getQuadrature()
    {
        return (float)($this->getAverageWidth() * $this->getSectionLength());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSectionType(): ?string
    {
        return $this->SectionType;
    }

    public function setSectionType(string $SectionType): self
    {
        $this->SectionType = $SectionType;

        return $this;
    }

    public function getSectionId(): ?string
    {
        return $this->SectionId;
    }

    public function setSectionId(string $SectionId): self
    {
        $this->SectionId = $SectionId;

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

    public function getSectionLength(): ?float
    {
        return $this->SectionLength;
    }

    public function setSectionLength(float $SectionLength): self
    {
        $this->SectionLength = $SectionLength;

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

    public function getAverageWidth(): ?float
    {
        return $this->AverageWidth;
    }

    public function setAverageWidth(float $AverageWidth): self
    {
        $this->AverageWidth = $AverageWidth;

        return $this;
    }

    public function getMaintenanceLevel(): ?string
    {
        return $this->MaintenanceLevel;
    }

    public function setMaintenanceLevel(string $MaintenanceLevel): self
    {
        $this->MaintenanceLevel = $MaintenanceLevel;

        return $this;
    }
}
