<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitRepository")
 */
class Unit
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LdapUser", mappedBy="unit")
     */

    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    /**
     * @return Collection| User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $UnitId;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $Name;

    public function getId()
    {
        return $this->id;
    }

    public function getUnitId(): ?int
    {
        return $this->UnitId;
    }

    public function setUnitId(int $UnitId): self
    {
        $this->UnitId = $UnitId;

        return $this;
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
}
