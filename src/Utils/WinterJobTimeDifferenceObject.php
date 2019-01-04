<?php
/**
 * Created by PhpStorm.
 * User: justin
 * Date: 18.10.11
 * Time: 14.12
 */

namespace App\Utils;

class WinterJobTimeEntity
{
    private $Subunit;
    private $Date;
    private $TimeFrom;
    private $TimeTo;

    public function __construct($s,$d,$tf,$tt)
    {
        $this->Subunit = $s;
        $this->setDate($d);
        $this->setTimeTo($tt);
        $this->setTimeFrom($tf);
    }

    /**
     * @return mixed
     */
    public function getTimeFrom()
    {
        return $this->TimeFrom;
    }

    /**
     * @param mixed $TimeFrom
     */
    public function setTimeFrom($TimeFrom): void
    {
        $this->TimeFrom = $TimeFrom;
    }

    /**
     * @return mixed
     */
    public function getTimeTo()
    {
        return $this->TimeTo;
    }

    /**
     * @param mixed $TimeTo
     */
    public function setTimeTo($TimeTo): void
    {
        $this->TimeTo = $TimeTo;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @param mixed $Date
     */
    public function setDate($Date): void
    {
        $this->Date = $Date;
    }

    /**
     * @return mixed
     */
    public function getSubunit()
    {
        return $this->Subunit;
    }

    /**
     * @param mixed $Subunit
     */
    public function setSubunit($Subunit): void
    {
        $this->Subunit = $Subunit;
    }
}

class WinterJobTimeDifferenceObject
{
    private $RoadSection;
    private $TimeStamps = array();

    public function __construct($road, $stamp)
    {
        $this->RoadSection = $road;
        $this->addRoadSection($stamp);
        $this->TimeStamps = array();

    }

    public function addRoadSection($timeObject)
    {
        //TODO Additional logic

        $didWeFind = false;
        foreach ($this->TimeStamps as $item) {
            if ($timeObject->getTimeFrom() == $item->getTimeFrom() && $timeObject->getTimeTo() == $item->getTimeTo())
            {
                $didWeFind = true;
            }
        }

        if($didWeFind == false)
        {
            $this->TimeStamps[] = $timeObject;
        }
    }

    public function roadId()
    {
        return $this->RoadSection->getSectionId();
    }

    public function compare($x)
    {
        if($x->getSectionId() != $this->RoadSection->getSectionId())
        {
            return false;
        }

        return true;
    }

    public function sort()
    {
        usort($this->TimeStamps, function($a, $b)
        {
            return ($a->getTimeFrom() > $b->getTimeFrom());
        });
    }

    public function contains($job)
    {
       return (array_key_exists($job, $this->TimeStamps));
    }

    /**
     * @return mixed
     */
    public function getRoadSection()
    {
        return $this->RoadSection;
    }

    /**
     * @param mixed $RoadSection
     */
    public function setRoadSection($RoadSection): void
    {
        $this->RoadSection = $RoadSection;
    }

    /**
     * @return array
     */
    public function getTimeStamps(): array
    {
        return $this->TimeStamps;
    }

    /**
     * @param array $TimeStamps
     */
    public function setTimeStamps(array $TimeStamps): void
    {
        $this->TimeStamps = $TimeStamps;
    }

}