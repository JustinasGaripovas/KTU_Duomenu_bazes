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
    private $DoneJobsName=array();
    private $DoneJobQuantity=array();

    public function __construct($t)
    {
        $this->Type = $t;
        $this->DoneJobs = array();

    }

    public function addJob($job, $amount, $jobName, $jobQuantity)
    {
        if($this->contains($job))
        {
            $this->DoneJobs[$job] += $amount;
        }else{
            $this->DoneJobs[$job] = $amount;
            $this->DoneJobsName[$job] = $jobName;
            $this->DoneJobQuantity[$job] = $jobQuantity;
        }
    }

    public function getName($index)
    {
        return $this->getDoneJobsName()[$index];
    }

    public function getJobQuantity($index)
    {
        return $this->getDoneJobQuantity()[$index];

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
     * @return array
     */
    public function getDoneJobsName(): array
    {
        return $this->DoneJobsName;
    }

    /**
     * @param array $DoneJobs
     */
    public function setDoneJobsName(array $DoneJobsName): void
    {
        $this->DoneJobsName = $DoneJobsName;
    }

    /**
     * @param mixed $Type
     */
    public function setType($Type): void
    {
        $this->Type = $Type;
    }

    /**
     * @return array
     */
    public function getDoneJobQuantity(): array
    {
        return $this->DoneJobQuantity;
    }

    /**
     * @param array $DoneJobQuantity
     */
    public function setDoneJobQuantity(array $DoneJobQuantity): void
    {
        $this->DoneJobQuantity = $DoneJobQuantity;
    }
}