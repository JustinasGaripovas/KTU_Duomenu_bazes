<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WarehouseRepository")
 */
class Warehouse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Capacity;

    /**
     * @ORM\Column(type="float")
     */
    private $CurrentCapacity;

    /**
     * @ORM\Column(type="float")
     */
    private $Latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $Longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subunit", inversedBy="warehouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_subunit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Materials", mappedBy="fk_warehouse")
     */
    private $materials;

    public function __toString()
    {
        return "{$this->getFkSubunit()->getName()} - {$this->getId()}";
    }

    public function __construct()
    {
        $this->materials = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?float
    {
        return $this->Capacity;
    }

    public function setCapacity(float $Capasity): self
    {
        $this->Capacity = $Capasity;

        return $this;
    }

    public function getCurrentCapacity(): ?float
    {
        return $this->CurrentCapacity;
    }

    public function setCurrentCapacity(float $CurrentCapacity): self
    {
        $this->CurrentCapacity = $CurrentCapacity;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(float $Latitude): self
    {
        $this->Latitude = $Latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function getFkSubunit(): ?Subunit
    {
        return $this->fk_subunit;
    }

    public function setFkSubunit(?Subunit $fk_subunit): self
    {
        $this->fk_subunit = $fk_subunit;

        return $this;
    }

    /**
     * @return Collection|Materials[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Materials $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
            $material->setFkWarehouse($this);
        }

        return $this;
    }

    public function removeMaterial(Materials $material): self
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
            // set the owning side to null (unless already changed)
            if ($material->getFkWarehouse() === $this) {
                $material->setFkWarehouse(null);
            }
        }

        return $this;
    }
}
