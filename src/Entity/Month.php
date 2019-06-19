<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 19.06.19
 * Time: 7:46
 */

namespace App\Entity;


class Month
{

    protected $year;
    protected $month;

    public function getMonth()
    {
        return $this->month;
    }

    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }
}