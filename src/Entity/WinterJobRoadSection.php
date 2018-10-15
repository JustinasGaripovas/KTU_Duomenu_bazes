<?php

namespace App\Entity;


class WinterJobRoadSection
{

    private $id;

    private $SectionId;

    private $SectionName;

    private $SectionBegin;

    private $SectionEnd;

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

    public function setSaltValue(?float $SaltValue): self
    {
        $this->SaltValue = $SaltValue;

        return $this;
    }

    public function getSandValue(): ?float
    {
        return $this->SandValue;
    }

    public function setSandValue(?float $SandValue): self
    {
        $this->SandValue = $SandValue;

        return $this;
    }

    public function getSolutionValue(): ?float
    {
        return $this->SolutionValue;
    }

    public function setSolutionValue(?float $SolutionValue): self
    {
        $this->SolutionValue = $SolutionValue;

        return $this;
    }
}
