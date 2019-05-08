<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MechanismRepository")
 */
class Mechanism
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
    private $VehicleCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $LastChecked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUsable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $VehicleType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WinterJob", inversedBy="mechanisms")
     */
    private $fk_winterjob;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicleCode(): ?string
    {
        return $this->VehicleCode;
    }

    public function setVehicleCode(string $VehicleCode): self
    {
        $this->VehicleCode = $VehicleCode;

        return $this;
    }

    public function getLastChecked(): ?\DateTimeInterface
    {
        return $this->LastChecked;
    }

    public function setLastChecked(\DateTimeInterface $LastChecked): self
    {
        $this->LastChecked = $LastChecked;

        return $this;
    }

    public function getIsUsable(): ?bool
    {
        return $this->isUsable;
    }

    public function setIsUsable(bool $isUsable): self
    {
        $this->isUsable = $isUsable;

        return $this;
    }

    public function getVehicleType(): ?string
    {
        return $this->VehicleType;
    }

    public function setVehicleType(string $VehicleType): self
    {
        $this->VehicleType = $VehicleType;

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
