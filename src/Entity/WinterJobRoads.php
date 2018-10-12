<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterJobRoadsRepository")
 */
class WinterJobRoads
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
    private $SectionId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SectionName;

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
    private $FullSectionName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WinterJobs", inversedBy="JobRoads")
     */
    private $winterJobs;

    public function getId()
    {
        return $this->id;
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

    public function getFullSectionName(): ?string
    {
        return $this->FullSectionName;
    }

    public function setFullSectionName(string $FullSectionName): self
    {
        $this->FullSectionName = $FullSectionName;

        return $this;
    }

    public function getWinterJobs(): ?WinterJobs
    {
        return $this->winterJobs;
    }

    public function setWinterJobs(?WinterJobs $winterJobs): self
    {
        $this->winterJobs = $winterJobs;

        return $this;
    }
}
