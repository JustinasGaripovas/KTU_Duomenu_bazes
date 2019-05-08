<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialsRepository")
 */
class Materials
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
    private $Amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Material;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WinterJob", inversedBy="materials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_winterjob;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Warehouse", inversedBy="materials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_warehouse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->Material;
    }

    public function setMaterial(string $Material): self
    {
        $this->Material = $Material;

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

    public function getFkWarehouse(): ?Warehouse
    {
        return $this->fk_warehouse;
    }

    public function setFkWarehouse(?Warehouse $fk_warehouse): self
    {
        $this->fk_warehouse = $fk_warehouse;

        return $this;
    }
}
