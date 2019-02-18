<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 12.02.2019
 * Time: 16:54
 */

namespace App\Service;


class CBRBank extends Bank
{
    public function getBank(): IBank
    {
        return new CBRBankProvider();
    }
}
