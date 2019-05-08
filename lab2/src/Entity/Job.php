<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 */
class Job
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
    private $Description;

    /**
     * @ORM\Column(type="float")
     */
    private $Quadrature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $QuadratureUnit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $DangerLevel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WinterJob", inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_winterjob;

    public function __toString()
    {
        return "{$this->Description} {$this->Code}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

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

    public function getQuadratureUnit(): ?string
    {
        return $this->QuadratureUnit;
    }

    public function setQuadratureUnit(string $QuadratureUnit): self
    {
        $this->QuadratureUnit = $QuadratureUnit;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }

    public function getDangerLevel(): ?string
    {
        return $this->DangerLevel;
    }

    public function setDangerLevel(string $DangerLevel): self
    {
        $this->DangerLevel = $DangerLevel;

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
