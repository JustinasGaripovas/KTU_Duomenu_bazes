<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\WinterMaintenanceRepository")
 */
class WinterMaintenance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Subunit;

    /**
     * @ORM\Column(type="array", nullable=true)
     */

    private $RoadConditionHighway;

    /**
     * @ORM\Column(type="array", nullable=true)
     */

    private $RoadConditionLocal;

    /**
     * @ORM\Column(type="array", nullable=true)
     */

    private $RoadConditionDistrict;

    /**
     * @ORM\Column(type="array")
     */
    private $Weather;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $TrafficChanges;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $BlockedRoads;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OtherEvents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Mechanism;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoadConditionScore;

    /**
     * @ORM\Column(type="date")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ReportFor;


    public function getId()
    {
        return $this->id;
    }

    public function getSubunit(): ?int
    {
        return $this->Subunit;
    }

    public function setSubunit(int $Subunit): self
    {
        $this->Subunit = $Subunit;

        return $this;
    }

    public function getRoadConditionDistrict()
    {
        return $this->RoadConditionDistrict;
    }

    public function setRoadConditionDistrict( $RoadConditionDistrict): self
    {
        $this->RoadConditionDistrict = $RoadConditionDistrict;

        return $this;
    }

    public function getWeather()
    {
        return $this->Weather;
    }

    public function setWeather( $Weather): self
    {
        $this->Weather = $Weather;

        return $this;
    }

    public function getTrafficChanges()
    {
        return $this->TrafficChanges;
    }

    public function setTrafficChanges( $TrafficChanges): self
    {
        $this->TrafficChanges = $TrafficChanges;

        return $this;
    }

    public function getBlockedRoads(): ?string
    {
        return $this->BlockedRoads;
    }

    public function setBlockedRoads(string $BlockedRoads): self
    {
        $this->BlockedRoads = $BlockedRoads;

        return $this;
    }

    public function getOtherEvents(): ?string
    {
        return $this->OtherEvents;
    }

    public function setOtherEvents(?string $OtherEvents): self
    {
        $this->OtherEvents = $OtherEvents;

        return $this;
    }

    public function getMechanism(): ?string
    {
        return $this->Mechanism;
    }

    public function setMechanism(string $Mechanism): self
    {
        $this->Mechanism = $Mechanism;

        return $this;
    }


    public function getRoadConditionScore() :? string
    {
        return $this->RoadConditionScore;
    }

    public function setRoadConditionScore(? string $RoadConditionScore): self
    {
        $this->RoadConditionScore = $RoadConditionScore;

        return $this;
    }

    public function getRoadConditionHighway(): ?array
    {
        return $this->RoadConditionHighway;
    }

    public function setRoadConditionHighway(?array $RoadConditionHighway): self
    {
        $this->RoadConditionHighway = $RoadConditionHighway;

        return $this;
    }

    public function getRoadConditionLocal(): ?array
    {
        return $this->RoadConditionLocal;
    }

    public function setRoadConditionLocal(?array $RoadConditionLocal): self
    {
        $this->RoadConditionLocal = $RoadConditionLocal;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getReportFor(): ?string
    {
        return $this->ReportFor;
    }

    public function setReportFor(string $ReportFor): self
    {
        $this->ReportFor = $ReportFor;

        return $this;
    }
}
