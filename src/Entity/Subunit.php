<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SubunitRepository")
 */
class Subunit
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Worker", mappedBy="Subunit")
     */

    private $workers;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
        $this->ldapUsers = new ArrayCollection();
    }
    /**
     * @return Collection| Worker[]
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }
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
     * @ORM\Column(type="integer")
     */
    private $SubunitId;

    /**
     * @ORM\Column(type="integer")
     */
    private $UnitId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LdapUser", mappedBy="Subunit")
     */
    private $ldapUsers;

    public function getId()
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

    public function getSubunitId(): ?int
    {
        return $this->SubunitId;
    }

    public function setSubunitId(int $SubunitId): self
    {
        $this->SubunitId = $SubunitId;

        return $this;
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

    /**
     * @return Collection|LdapUser[]
     */
    public function getLdapUsers(): Collection
    {
        return $this->ldapUsers;
    }

    public function addLdapUser(LdapUser $ldapUser): self
    {
        if (!$this->ldapUsers->contains($ldapUser)) {
            $this->ldapUsers[] = $ldapUser;
            $ldapUser->setSubunit($this);
        }

        return $this;
    }

    public function removeLdapUser(LdapUser $ldapUser): self
    {
        if ($this->ldapUsers->contains($ldapUser)) {
            $this->ldapUsers->removeElement($ldapUser);
            // set the owning side to null (unless already changed)
            if ($ldapUser->getSubunit() === $this) {
                $ldapUser->setSubunit(null);
            }
        }

        return $this;
    }
}
