<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 13.02.2019
 * Time: 14:47
 */

namespace App\Service;

class Convert
{
    public static function convert($base, $from_curency, $from_rate, $to_curency, $to_rate, $amount): ?float
    {
        return $amount * $to_rate / $from_rate;
    }
}