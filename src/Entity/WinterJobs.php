<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterJobsRepository")
 */
class WinterJobs
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
     * @ORM\Column(type="integer")
     */
    private $Subunit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Mechanism;

    /**
     * @ORM\Column(type="time")
     */
    private $TimeFrom;

    /**
     * @ORM\Column(type="time")
     */
    private $TimeTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Job;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CreatedBy;

    /**
     * @ORM\Column(type="array")
     * cascade={"persist"}
     */
    private $RoadSections = [];

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SubunitName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $JobId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $JobQuantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $JobName;

    public function addRoadSection($additionalSection)
    {
        $this->RoadSections[] = $additionalSection;
        return $this->RoadSections;
    }

    public function getRoadSectionsStringArray()
    {
        $str = array();


        foreach ($this->RoadSections as $road)
        {
            $str[] = $road->getSectionId() . " ( ". $road->getSectionBegin() . "km -> " . $road->getSectionEnd() . "km )";
        }
        return $str;
    }

    public function getRoadSectionsSalt()
    {
        $str = array();
        foreach ($this->RoadSections as $road )
        {
            if($road->getSaltValue()  === null)
            {
                $str[] = " ";

            }else{
                $str[] = $road->getSaltValue();

            }
        }

        return $str;
    }

    public function getRoadSectionsSand()
    {
        $str = array();
        foreach ($this->RoadSections as $road)
        {
            if($road->getSandValue() === null)
            {
                $str[] = " ";

            }else{
                $str[] = $road->getSandValue();

            }
        }
        return $str;
    }

    public function getRoadSectionsSolution()
    {
        $str = array();
        foreach ($this->RoadSections as $road)
        {
            if($road->getSolutionValue() === null)
            {
                $str[] = " ";

            }else{
                $str[] = $road->getSolutionValue();

            }
        }
        return $str;
    }

    public function getRoadSections(): ?array
    {
        return $this->RoadSections;
    }

    public function getRoadSectionsIndex($index)
    {
        return $this->RoadSections[$index];
    }

    public function setRoadSections(array $RoadSections): self
    {
        if (!empty($RoadSections) && $RoadSections === $this->RoadSections) {
            reset($RoadSections);
            $RoadSections[key($RoadSections)] = clone current($RoadSections);
        }
        $this->RoadSections = $RoadSections;

        return $this;
    }

    public function getId()
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

    public function getSubunit(): ?int
    {
        return $this->Subunit;
    }

    public function setSubunit(int $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }

    public function getMechanism(): ?string
    {
        return $this->Mechanism;
    }

    public function setMechanism(string $Mechanism): self
    {
        $this->Mechanism = $Mechanism;

        return $this;
    }

    public function getTimeFrom(): ?\DateTimeInterface
    {
        return $this->TimeFrom;
    }

    public function setTimeFrom(\DateTimeInterface $TimeFrom): self
    {
        $this->TimeFrom = $TimeFrom;

        return $this;
    }

    public function getTimeTo(): ?\DateTimeInterface
    {
        return $this->TimeTo;
    }

    public function setTimeTo(\DateTimeInterface $TimeTo): self
    {
        $this->TimeTo = $TimeTo;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->Job;
    }

    public function setJob(string $Job): self
    {
        $this->Job = $Job;

        return $this;
    }

    public function setRoadSectionSearch(string $RoadSectionSearch): self
    {
        $this->RoadSectionSearch = $RoadSectionSearch;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }
    public function __toString()
    {
        return $this->getMechanism();
    }

    public function getSubunitName(): ?string
    {
        return $this->SubunitName;
    }

    public function setSubunitName(string $SubunitName): self
    {
        $this->SubunitName = $SubunitName;

        return $this;
    }

    public function getJobId(): ?string
    {
        return $this->JobId;
    }

    public function setJobId(?string $JobId): self
    {
        $this->JobId = $JobId;

        return $this;
    }

    public function getJobQuantity(): ?string
    {
        return $this->JobQuantity;
    }

    public function setJobQuantity(?string $JobQuantity): self
    {
        $this->JobQuantity = $JobQuantity;

        return $this;
    }

    public function getJobName(): ?string
    {
        return $this->JobName;
    }

    public function setJobName(?string $JobName): self
    {
        $this->JobName = $JobName;

        return $this;
    }
}
