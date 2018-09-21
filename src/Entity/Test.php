<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
class Test
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $One;

    public function getId()
    {
        return $this->id;
    }

    public function getOne(): ?string
    {
        return $this->One;
    }

    public function setOne(?string $One): self
    {
        $this->One = $One;

        return $this;
    }
}
