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

    const MONTH_LIST = [
        'январь' => '1',
        'февраль' => '2',
        'март' => '3',
        'апрель' => '4',
        'май' => '5',
        'июнь' => '6',
        'июль' => '7',
        'август' => '8',
        'сентябрь' => '9',
        'октябрь' => '10',
        'ноябрь' => '11',
        'декабрь' => '12',
    ];

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

    public static function getYearList()
    {
        $yearList = [];
        $firstYear = date('Y');
        for ($i=0; $i<5; $i++) {
            $yearList[$firstYear - $i] = $firstYear - $i;
        }
        return $yearList;
    }
}