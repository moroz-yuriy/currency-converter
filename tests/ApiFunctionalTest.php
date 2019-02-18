<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 14.02.2019
 * Time: 12:04
 */

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlGoodProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlBadProvider
     */
    public function testPageIsNotFound($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function urlGoodProvider()
    {
        yield ['/api/exchange/200/EUR-USD'];
        yield ['/api/exchange/200/USD-PLN'];
        yield ['/api/exchange/200/EUR-EUR'];
    }

    public function urlBadProvider()
    {
        yield ['/api/exchange/200/EUR-RRR'];
    }
}