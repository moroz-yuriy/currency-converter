<?php
/**
 * Created by PhpStorm.
 * User: ymoroz
 * Date: 12.02.2019
 * Time: 14:46
 */
/*
<gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
<gesmes:subject>Reference rates</gesmes:subject>
<gesmes:Sender>
<gesmes:name>European Central Bank</gesmes:name>
</gesmes:Sender>
<Cube>
<Cube time="2019-02-11">
<Cube currency="USD" rate="1.1309"/>
<Cube currency="JPY" rate="124.63"/>
<Cube currency="BGN" rate="1.9558"/>
<Cube currency="CZK" rate="25.836"/>
<Cube currency="DKK" rate="7.4637"/>
<Cube currency="GBP" rate="0.87615"/>
<Cube currency="HUF" rate="319.66"/>
<Cube currency="PLN" rate="4.3158"/>
<Cube currency="RON" rate="4.7405"/>
<Cube currency="SEK" rate="10.4858"/>
<Cube currency="CHF" rate="1.1351"/>
<Cube currency="ISK" rate="136.60"/>
<Cube currency="NOK" rate="9.8190"/>
<Cube currency="HRK" rate="7.4075"/>
<Cube currency="RUB" rate="74.1735"/>
<Cube currency="TRY" rate="5.9588"/>
<Cube currency="AUD" rate="1.5983"/>
<Cube currency="BRL" rate="4.2270"/>
<Cube currency="CAD" rate="1.5005"/>
<Cube currency="CNY" rate="7.6781"/>
<Cube currency="HKD" rate="8.8750"/>
<Cube currency="IDR" rate="15924.05"/>
<Cube currency="ILS" rate="4.1196"/>
<Cube currency="INR" rate="80.4480"/>
<Cube currency="KRW" rate="1272.60"/>
<Cube currency="MXN" rate="21.5907"/>
<Cube currency="MYR" rate="4.6056"/>
<Cube currency="NZD" rate="1.6768"/>
<Cube currency="PHP" rate="58.974"/>
<Cube currency="SGD" rate="1.5364"/>
<Cube currency="THB" rate="35.533"/>
<Cube currency="ZAR" rate="15.5344"/>
</Cube>
</Cube>
</gesmes:Envelope>

*/


namespace App\Service;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ClientECB
{
    /** @var GuzzleHttp\Client $client*/
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetch()
    {
        $rates = [];
        $response = $this->client->request('GET', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
        $xml = $response->getBody()->getContents();

        $crawler = new Crawler($xml);
        $crawler = $crawler->filterXPath('//gesmes:Envelope//default:Cube');
        $date = $crawler->children()->attr('time');

        $crawler = new Crawler($xml);
        $crawler = $crawler->filterXPath('//gesmes:Envelope//default:Cube/default:Cube');

        $rates = $crawler->each(function (Crawler $node, $i) {
            return ['currency' => $node->attr('currency'), 'rate' => $node->attr('rate')];
        });
        return ['date' => $date, 'rates' => $rates];
    }
}