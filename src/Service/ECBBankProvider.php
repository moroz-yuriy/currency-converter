<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 12.02.2019
 * Time: 14:46
 */


namespace App\Service;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ECBBankProvider implements IBank
{
    const PROVIDER_ID = 2;
    const BASE_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

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
        $crawler = $crawler->filterXPath('//gesmes:Envelope//default:Cube');
        $date = $crawler->children()->attr('time');

        $crawler = new Crawler($xml);
        $crawler = $crawler->filterXPath('//gesmes:Envelope//default:Cube/default:Cube');

        $rates = $crawler->each(function (Crawler $node, $i) use ($date) {
            return (object)['currency' => $node->attr('currency'), 'rate' => $node->attr('rate'), 'date' => $date];
        });

        // Remove empty first element
        array_shift($rates);
        return $rates;
    }

    public function getBankID() : int
    {
        return self::PROVIDER_ID;
    }

    public function getBaseCurrency() : string
    {
        return 'EUR';
    }
}