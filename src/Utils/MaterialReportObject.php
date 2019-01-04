<?php
/**
 * Created by PhpStorm.
 * User: justin
 * Date: 18.10.11
 * Time: 14.12
 */

namespace App\Utils;


class MaterialReportObject
{
    private $Name;
    private $SubunitId;
    private $SectionId;
    private $SaltValue;
    private $SandValue;
    private $SolutionValue;

    public function __construct($n,$sub,$section,$salt,$sand,$solution)
    {
        $this->Name = $n;
        $this->SubunitId = $sub;
        $this->SectionId = $section;
        $this->SaltValue= $salt;
        $this->SandValue= $sand;
        $this->SolutionValue= $solution;
    }

    public function addSalt($value)
    {

        if($value==null ||$value==0)
            return;

        $this->SaltValue += $value;
    }

    public function addSand($value)
    {

        if($value==null ||$value==0)
            return;

        $this->SandValue += $value;
    }

    public function addSolution($value)
    {
        if($value==null ||$value==0)
            return;

        $this->SolutionValue += $value;
    }

    /**
     * @param mixed $Name
     * @return MaterialReportObject
     */
    public function setName($Name)
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @return mixed
     */
    public function getSubunitId()
    {
        return $this->SubunitId;
    }

    /**
     * @param mixed $SubunitId
     */
    public function setSubunitId($SubunitId): void
    {
        $this->SubunitId = $SubunitId;
    }

    /**
     * @return mixed
     */
    public function getSectionId()
    {
        return $this->SectionId;
    }

    /**
     * @param mixed $SectionId
     */
    public function setSectionId($SectionId): void
    {
        $this->SectionId = $SectionId;
    }

    /**
     * @return mixed
     */
    public function getSaltValue()
    {
        return $this->SaltValue;
    }

    /**
     * @param mixed $SaltValue
     */
    public function setSaltValue($SaltValue): void
    {
        $this->SaltValue = $SaltValue;
    }

    /**
     * @return mixed
     */
    public function getSandValue()
    {
        return $this->SandValue;
    }

    /**
     * @param mixed $SandValue
     */
    public function setSandValue($SandValue): void
    {
        $this->SandValue = $SandValue;
    }

    /**
     * @return mixed
     */
    public function getSolutionValue()
    {
        return $this->SolutionValue;
    }

    /**
     * @param mixed $SolutionValue
     */
    public function setSolutionValue($SolutionValue): void
    {
        $this->SolutionValue = $SolutionValue;
    }
}