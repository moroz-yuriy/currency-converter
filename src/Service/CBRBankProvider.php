<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 13.02.2019
 * Time: 15:45
 */

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class CBRBankProvider implements IBank
{
    const PROVIDER_ID = 1;
    const BASE_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch() : array
    {
        $response = $this->client->request('GET', self::BASE_URL);
        $xml = $response->getBody()->getContents();

        $crawler = new Crawler($xml);

        $crawler = $crawler->filterXPath('//ValCurs');
        $date = $crawler->attr('Date');

        $crawler = $crawler->filterXPath('//ValCurs/Valute');
        $rates = $crawler->each(function (Crawler $node, $i) use ($date) {
            $rate = preg_replace('/,/', '.', $node->children('Value')->text());
            return (object)['currency' => $node->children('CharCode')->text(), 'rate' => $rate, 'date' => $date];
        });

        return $rates;
    }

    public function getBankID() : int
    {
        return self::PROVIDER_ID;
    }

    public function getBaseCurrency() :string
    {
        return 'RUB';
    }
}