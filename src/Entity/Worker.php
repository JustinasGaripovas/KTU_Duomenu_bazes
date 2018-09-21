<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkerRepository")
 */
class Worker
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Subunit", inversedBy="workers")
     * @ORM\JoinColumn(nullable=false)
     */

    private $Subunit;

    /**
     * @ORM\Column(type="integer")
     */
    private $WorkerId;

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

    public function getSubunit(): ?Subunit
    {
        return $this->Subunit;
    }

    public function setSubunit(?Subunit $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }

    public function getWorkerId(): ?int
    {
        return $this->WorkerId;
    }

    public function setWorkerId(int $WorkerId): self
    {
        $this->WorkerId = $WorkerId;

        return $this;
    }

}
