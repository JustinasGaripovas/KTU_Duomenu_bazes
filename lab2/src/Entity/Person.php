<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
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
    private $Surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subunit", inversedBy="people")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_subunit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\WinterJob", inversedBy="people")
     */
    private $WinterJobs;

    public function __construct()
    {
        $this->WinterJobs = new ArrayCollection();
    }

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

    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    public function setSurname(string $Surname): self
    {
        $this->Surname = $Surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;

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
     * @return Collection|WinterJob[]
     */
    public function getWinterJobs(): Collection
    {
        return $this->WinterJobs;
    }

    public function addWinterJob(WinterJob $winterJob): self
    {
        if (!$this->WinterJobs->contains($winterJob)) {
            $this->WinterJobs[] = $winterJob;
        }

        return $this;
    }

    public function removeWinterJob(WinterJob $winterJob): self
    {
        if ($this->WinterJobs->contains($winterJob)) {
            $this->WinterJobs->removeElement($winterJob);
        }

        return $this;
    }
}
