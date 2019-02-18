<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 12.02.2019
 * Time: 14:46
 */


namespace App\Service;


class ECBBank extends Bank
{
    public function getBank(): IBank
    {
        return new ECBBankProvider();
    }
}