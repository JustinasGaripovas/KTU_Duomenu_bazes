<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestrictionStatusRepository")
 */
class RestrictionStatus
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
    private $StatusName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Restriction", mappedBy="Status")
     */
    private $restrictions;

    public function __construct()
    {
        $this->restrictions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatusName(): ?string
    {
        return $this->StatusName;
    }

    public function setStatusName(string $StatusName): self
    {
        $this->StatusName = $StatusName;

        return $this;
    }

    /**
     * @return Collection|Restriction[]
     */
    public function getRestrictions(): Collection
    {
        return $this->restrictions;
    }

    public function addRestriction(Restriction $restriction): self
    {
        if (!$this->restrictions->contains($restriction)) {
            $this->restrictions[] = $restriction;
            $restriction->setStatus($this);
        }

        return $this;
    }

    public function removeRestriction(Restriction $restriction): self
    {
        if ($this->restrictions->contains($restriction)) {
            $this->restrictions->removeElement($restriction);
            // set the owning side to null (unless already changed)
            if ($restriction->getStatus() === $this) {
                $restriction->setStatus(null);
            }
        }

        return $this;
    }
    
}
