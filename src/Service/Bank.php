<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 13.02.2019
 * Time: 15:26
 */

namespace App\Service;


abstract class Bank
{
    abstract protected function getBank() : IBank;

    public function fetch() : array
    {
        return $this->getBank()->fetch();
    }

    public function getBankID()
    {
        return $this->getBank()->getBankID();
    }

    public function getBaseCurrency()
    {
        return $this->getBank()->getBaseCurrency();
    }
}