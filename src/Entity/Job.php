<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 */
class Job
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
    private $JobId;

    /**
     * @ORM\Column(type="text")
     */
    private $JobName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $JobQuantity;


    public function getId()
    {
        return $this->id;
    }

    public function getJobId(): ?string
    {
        return $this->JobId;
    }

    public function setJobId(string $JobId): self
    {
        $this->JobId = $JobId;

        return $this;
    }

    public function getJobName(): ?string
    {
        return $this->JobName;
    }

    public function setJobName(string $JobName): self
    {
        $this->JobName = $JobName;

        return $this;
    }

    public function getJobQuantity(): ?string
    {
        return $this->JobQuantity;
    }

    public function setJobQuantity(string $JobQuantity): self
    {
        $this->JobQuantity = $JobQuantity;

        return $this;
    }

}
