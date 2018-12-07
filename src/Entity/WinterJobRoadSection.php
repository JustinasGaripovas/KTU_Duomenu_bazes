<?php

namespace App\Entity;


use Doctrine\ORM\Query\Expr\Math;

class WinterJobRoadSection
{

    private $id;

    private $SectionId;

    private $SectionName;

    private $SectionBegin;

    private $SectionEnd;

    private $SectionWidth;

    private $Quadrature;

    private $level;

    private $RoadSectionSearch;

    private $SaltChecked;

    private $SandChecked;

    private $SolutionChecked;

    private $SaltValue;

    private $SectionType;

    private $SandValue;

    private $SolutionValue;

    public function getId(): ?int
    {
        return $this->id;
    }
/*
    public function getJobAmount()
    {
        return ((float)$this->SectionEnd - (float)$this->SectionBegin)*1000*$this->SectionWidth;
    }

    public function getTreatmentRate()
    {
        return (float)((($this->SaltValue + $this->SandValue)* 10 ** 6) / $this->getJobAmount());
    }*/

    public function getSectionType(): ?string
    {
        return $this->SectionType;
    }

    public function setSectionType(string $SectionType): self
    {
        $this->SectionType = $SectionType;

        return $this;
    }

    public function getSectionWidth(): ?float
    {
        return $this->SectionWidth;
    }

    public function setSectionWidth(string $SectionWidth): self
    {
        $this->SectionWidth = $SectionWidth;

        return $this;
    }

    public function getQuadrature(): ?float
    {
        return $this->Quadrature;
    }

    public function setQuadrature(float $Quadrature): self
    {
        $this->Quadrature = $Quadrature;

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

    public function getSectionName(): ?string
    {
        return $this->SectionName;
    }

    public function setSectionName(string $SectionName): self
    {
        $this->SectionName = $SectionName;

        return $this;
    }

    public function getSectionBegin(): ?float
    {

        return $this->SectionBegin;
    }

    public function setSectionBegin($SectionBegin): self
    {

        $this->SectionBegin = (float)str_replace(',', '.', $SectionBegin);

        return $this;
    }

    public function getSectionEnd(): ?float
    {
        return $this->SectionEnd;
    }

    public function setSectionEnd($SectionEnd): self
    {
        $this->SectionEnd = (float)str_replace(',', '.', $SectionEnd);

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getRoadSectionSearch(): ?string
    {
        return $this->RoadSectionSearch;
    }

    public function setRoadSectionSearch(?string $RoadSectionSearch): self
    {
        $this->RoadSectionSearch = $RoadSectionSearch;

        return $this;
    }

    public function getSaltChecked(): ?bool
    {
        return $this->SaltChecked;
    }

    public function setSaltChecked(?bool $SaltChecked): self
    {
        $this->SaltChecked = $SaltChecked;

        return $this;
    }

    public function getSandChecked(): ?bool
    {
        return $this->SandChecked;
    }

    public function setSandChecked(?bool $SandChecked): self
    {
        $this->SandChecked = $SandChecked;

        return $this;
    }

    public function getSolutionChecked(): ?bool
    {
        return $this->SolutionChecked;
    }

    public function setSolutionChecked(?bool $SolutionChecked): self
    {
        $this->SolutionChecked = $SolutionChecked;

        return $this;
    }

    public function getSaltValue(): ?float
    {
        return $this->SaltValue;
    }

    public function setSaltValue($SaltValue): self
    {
        $this->SaltValue = (float)str_replace(',', '.', $SaltValue);

        return $this;
    }

    public function getSandValue(): ?float
    {
        return $this->SandValue;
    }

    public function setSandValue($SandValue): self
    {

        $this->SandValue = (float)str_replace(',', '.', $SandValue);

        return $this;
    }

    public function getSolutionValue(): ?float
    {
        return $this->SolutionValue;
    }

    public function setSolutionValue($SolutionValue): self
    {
        $this->SolutionValue = (float)str_replace(',', '.', $SolutionValue);

        return $this;
    }
}
