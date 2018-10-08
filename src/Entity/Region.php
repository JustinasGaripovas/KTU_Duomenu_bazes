<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
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
    private $RegionName;

    /**
     * @ORM\Column(type="integer")
     */
    private $SubunitId;

    public function getId()
    {
        return $this->id;
    }

    public function getRegionName(): ?string
    {
        return $this->RegionName;
    }

    public function setRegionName(string $RegionName): self
    {
        $this->RegionName = $RegionName;

        return $this;
    }

    public function getSubunitId(): ?int
    {
        return $this->SubunitId;
    }

    public function setSubunitId(int $SubunitId): self
    {
        $this->SubunitId = $SubunitId;

        return $this;
    }
}
