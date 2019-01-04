<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 */
class Structure
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
     * @ORM\Column(type="string", length=255)
     */
    private $Master;

    public $array = array();

    /**
     * @ORM\Column(type="integer")
     */
    private $InformationType;

    /**
     * @ORM\Column(type="integer")
     */
    private $StructureId;

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

    public function getMaster(): ?string
    {
        return $this->Master;
    }

    public function setMaster(string $Master): self
    {
        $this->Master = $Master;

        return $this;
    }

    public function getInformationType(): ?int
    {
        return $this->InformationType;
    }

    public function setInformationType(int $InformationType): self
    {
        $this->InformationType = $InformationType;

        return $this;
    }

    public function getStructureId(): ?int
    {
        return $this->StructureId;
    }

    public function setStructureId(int $StructureId): self
    {
        $this->StructureId = $StructureId;

        return $this;
    }

}
