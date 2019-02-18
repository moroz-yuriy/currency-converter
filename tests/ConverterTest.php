<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Convert;

class ConverterTest extends KernelTestCase
{
    private $bank;

    protected function setUp()
    {
        static::bootKernel();
        $this->bank = static::$kernel->getContainer()->get('bank.current_bank');
    }

    public function testConvertFromBaseToUsd()
    {
        $base_currency = $this->bank->getBaseCurrency();

        $converted_amount = Convert::convert($base_currency, $base_currency, 1, 'USD', 1.2, 200);

        $this->assertTrue(round($converted_amount, 2) == 240.00, 'ConvertFromBaseToUsd');
    }

    public function testConvertFromUsdToBase()
    {
        $base_currency = $this->bank->getBaseCurrency();

        $converted_amount = Convert::convert($base_currency, 'USD', 1.2, $base_currency, 1,  200);

        $this->assertTrue(round($converted_amount, 2) == 166.67, 'ConvertFromUsdToBase');
    }

    public function testConvertFromBaseToBase()
    {
        $base_currency = $this->bank->getBaseCurrency();

        $converted_amount = Convert::convert($base_currency, $base_currency, 1, $base_currency, 1,  200);

        $this->assertTrue(round($converted_amount, 2) == 200, 'ConvertFromBaseToBase');
    }
}
