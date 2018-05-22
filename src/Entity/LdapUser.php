<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LdapUserRepository")
 */
class LdapUser
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subunit", inversedBy="ldapUsers")
     */
    private $Subunit;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    public function getSubunit(): ?Subunit
    {
        return $this->Subunit;
    }

    public function setSubunit(?Subunit $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }
}
