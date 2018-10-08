<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChoicesRepository")
 */
class Choices
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
    private $Choice;

    /**
     * @ORM\Column(type="integer")
     */
    private $ChoiceId;

    public function getId()
    {
        return $this->id;
    }

    public function getChoice(): ?string
    {
        return $this->Choice;
    }

    public function setChoice(string $Choice): self
    {
        $this->Choice = $Choice;

        return $this;
    }

    public function getChoiceId(): ?int
    {
        return $this->ChoiceId;
    }

    public function setChoiceId(int $ChoiceId): self
    {
        $this->ChoiceId = $ChoiceId;

        return $this;
    }
}
