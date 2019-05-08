<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoadSectionRepository")
 */
class RoadSection
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
    private $Name;

    /**
     * @ORM\Column(type="float")
     */
    private $Begin;

    /**
     * @ORM\Column(type="float")
     */
    private $End;

    /**
     * @ORM\Column(type="float")
     */
    private $AverageWidth;

    /**
     * @ORM\Column(type="integer")
     */
    private $MaintenanceLevel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WinterJob", inversedBy="roadSections")
     */
    private $fk_winterjob;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBegin(): ?float
    {
        return $this->Begin;
    }

    public function setBegin(float $Begin): self
    {
        $this->Begin = $Begin;

        return $this;
    }

    public function getEnd(): ?float
    {
        return $this->End;
    }

    public function setEnd(float $End): self
    {
        $this->End = $End;

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

    public function getMaintenanceLevel(): ?int
    {
        return $this->MaintenanceLevel;
    }

    public function setMaintenanceLevel(int $MaintenanceLevel): self
    {
        $this->MaintenanceLevel = $MaintenanceLevel;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getFkWinterjob(): ?WinterJob
    {
        return $this->fk_winterjob;
    }

    public function setFkWinterjob(?WinterJob $fk_winterjob): self
    {
        $this->fk_winterjob = $fk_winterjob;

        return $this;
    }
}
