<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterJobUniqueRepository")
 */
class WinterJobUnique
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
    private $CreatedBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Job;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SectionId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SectionType;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionBegin;

    /**
     * @ORM\Column(type="float")
     */
    private $SectionEnd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Salt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Sand;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Solution;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Quadrature;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $OriginalId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SubunitName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $UniqueId;

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

    public function getCreatedBy(): ?string
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(string $CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

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

    public function getSectionId(): ?string
    {
        return $this->SectionId;
    }

    public function setSectionId(string $SectionId): self
    {
        $this->SectionId = $SectionId;

        return $this;
    }

    public function getSectionType()
    {
        return $this->SectionType;
    }

    public function setSectionType($SectionType): self
    {
        $this->SectionType = $SectionType;

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

    public function getSalt(): ?float
    {
        return $this->Salt;
    }

    public function setSalt(?float $Salt): self
    {
        $this->Salt = $Salt;

        return $this;
    }

    public function getSand(): ?float
    {
        return $this->Sand;
    }

    public function setSand(?float $Sand): self
    {
        $this->Sand = $Sand;

        return $this;
    }

    public function getSolution(): ?float
    {
        return $this->Solution;
    }

    public function setSolution(?float $Solution): self
    {
        $this->Solution = $Solution;

        return $this;
    }

    public function getQuadrature(): ?float
    {
        return $this->Quadrature;
    }

    public function setQuadrature(?float $Quadrature): self
    {
        $this->Quadrature = $Quadrature;

        return $this;
    }

    public function getOriginalId(): ?int
    {
        return $this->OriginalId;
    }

    public function setOriginalId(?int $OriginalId): self
    {
        $this->OriginalId = $OriginalId;

        return $this;
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

    public function getUniqueId(): ?string
    {
        return $this->UniqueId;
    }

    public function setUniqueId(string $UniqueId): self
    {
        $this->UniqueId = $UniqueId;

        return $this;
    }
}
