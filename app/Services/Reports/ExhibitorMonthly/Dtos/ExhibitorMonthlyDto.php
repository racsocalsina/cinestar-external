<?php


namespace App\Services\Reports\ExhibitorMonthly\Dtos;


class ExhibitorMonthlyDto
{
    private $year;
    private $month;
    private $tradeName;

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = intval($year);
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = intval($month);
    }

    /**
     * @return mixed
     */
    public function getTradeName()
    {
        return $this->tradeName;
    }

    /**
     * @param mixed $tradeName
     */
    public function setTradeName($tradeName): void
    {
        $this->tradeName = $tradeName;
    }
}