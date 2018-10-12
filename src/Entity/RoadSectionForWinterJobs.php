<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $RoadType;

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
     * @ORM\Column(type="integer")
     */
    private $Subunit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoadType(): ?string
    {
        return $this->RoadType;
    }

    public function setRoadType(string $RoadType): self
    {
        $this->RoadType = $RoadType;

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

    public function getSubunit(): ?int
    {
        return $this->Subunit;
    }

    public function setSubunit(int $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }
}
