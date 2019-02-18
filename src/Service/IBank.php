<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 13.02.2019
 * Time: 15:23
 */

namespace App\Service;


interface IBank
{
    public function fetch() : array;
    public function getBaseCurrency() : string;
    public function getBankID() : int;

}