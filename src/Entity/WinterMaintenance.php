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

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionHighway2;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionHighway3;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionLocal2;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionLocal3;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionDistrict2;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $RoadConditionDistrict3;


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

    public function getRoadConditionHighway2(): ?array
    {
        return $this->RoadConditionHighway2;
    }

    public function setRoadConditionHighway2(?array $RoadConditionHighway2): self
    {
        $this->RoadConditionHighway2 = $RoadConditionHighway2;

        return $this;
    }

    public function getRoadConditionHighway3(): ?array
    {
        return $this->RoadConditionHighway3;
    }

    public function setRoadConditionHighway3(?array $RoadConditionHighway3): self
    {
        $this->RoadConditionHighway3 = $RoadConditionHighway3;

        return $this;
    }

    public function getRoadConditionLocal2(): ?array
    {
        return $this->RoadConditionLocal2;
    }

    public function setRoadConditionLocal2(?array $RoadConditionLocal2): self
    {
        $this->RoadConditionLocal2 = $RoadConditionLocal2;

        return $this;
    }

    public function getRoadConditionLocal3(): ?array
    {
        return $this->RoadConditionLocal3;
    }

    public function setRoadConditionLocal3(?array $RoadConditionLocal3): self
    {
        $this->RoadConditionLocal3 = $RoadConditionLocal3;

        return $this;
    }

    public function getRoadConditionDistrict2(): ?array
    {
        return $this->RoadConditionDistrict2;
    }

    public function setRoadConditionDistrict2(?array $RoadConditionDistrict2): self
    {
        $this->RoadConditionDistrict2 = $RoadConditionDistrict2;

        return $this;
    }

    public function getRoadConditionDistrict3(): ?array
    {
        return $this->RoadConditionDistrict3;
    }

    public function setRoadConditionDistrict3(?array $RoadConditionDistrict3): self
    {
        $this->RoadConditionDistrict3 = $RoadConditionDistrict3;

        return $this;
    }
}
