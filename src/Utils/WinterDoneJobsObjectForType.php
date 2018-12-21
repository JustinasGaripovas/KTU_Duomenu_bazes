<?php
/**
 * Created by PhpStorm.
 * User: justin
 * Date: 18.10.11
 * Time: 14.12
 */

namespace App\Utils;


class WinterDoneJobsObjectForType
{
    private $Type;
    private $DoneJobs = array();

    public function __construct($t)
    {
        $this->Type = $t;
        $this->DoneJobs = array();

    }

    public function addJob($job, $amount)
    {
        if($this->contains($job))
        {
            $this->DoneJobs[$job] += $amount;
        }else{
            $this->DoneJobs[$job] = $amount;
        }
    }

    public function contains($job)
    {
       return (array_key_exists($job, $this->DoneJobs));
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @return array
     */
    public function getDoneJobs(): array
    {
        return $this->DoneJobs;
    }

    /**
     * @param array $DoneJobs
     */
    public function setDoneJobs(array $DoneJobs): void
    {
        $this->DoneJobs = $DoneJobs;
    }

    /**
     * @param mixed $Type
     */
    public function setType($Type): void
    {
        $this->Type = $Type;
    }
}