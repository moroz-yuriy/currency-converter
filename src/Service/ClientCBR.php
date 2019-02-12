<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 12.02.2019
 * Time: 16:54
 */

namespace App\Service;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ClientCBR
{
    /** @var GuzzleHttp\Client $client*/
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch()
    {
        $response = $this->client->request('GET', 'https://www.cbr.ru/scripts/XML_daily.asp');
        $xml = $response->getBody()->getContents();

        $crawler = new Crawler($xml);

        $crawler = $crawler->filterXPath('//ValCurs');
        $date = $crawler->attr('Date');

        $crawler = $crawler->filterXPath('//ValCurs/Valute');
        $rates = $crawler->each(function (Crawler $node, $i) {

            return ['currency' => $node->children('CharCode')->text(), 'rate' => $node->children('Value')->text()];
        });
        return ['date' => $date, 'rates' => $rates];
    }
}