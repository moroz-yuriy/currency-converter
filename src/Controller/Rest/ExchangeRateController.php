<?php

namespace App\Controller\Rest;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\CurrencyRate;
use App\Service\Convert;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


class ExchangeRateController extends AbstractFOSRestController
{
    private $bank;
    
    public function __construct(ContainerInterface  $container)
    {
        $this->container = $container;
        $this->bank = $this->container->get('bank.current_bank');
    }

    /**
     *  Convert
     * @Rest\Get("/exchange/{amount}/{from}-{to}", name="exchange")
     */
    public function index($amount, $from, $to)
    {
        $to = mb_strtoupper($to);
        $from = mb_strtoupper($from);

        $repository = $this->container->get('doctrine')->getRepository(CurrencyRate::class);

        $base_currency = $this->bank->getBaseCurrency();
        $base_id = $this->bank->getBankID();

        if ($from == $base_currency) {
            $from_currency = new CurrencyRate();
            $from_currency->setRate(1);
            $from_currency->setCurrency($from);
        } else {
            $from_currency = $repository->findOneBy([
                'currency' => $from,
                'source' => $base_id
            ]);
        }

        if ($to == $base_currency) {
            $to_currency = new CurrencyRate();
            $to_currency->setRate(1);
            $to_currency->setCurrency($from);
        } else {
            $to_currency = $repository->findOneBy([
                'currency' => $to,
                'source' => $base_id
            ]);
        }

        if ($to_currency == null || $from_currency == null) {
            return $this->json([
                'message' => 'Wrong currency pair'
            ], 404);
        }

        $converted_amount = Convert::convert(
            $base_currency,
            $from_currency->getCurrency(),
            $from_currency->getRate(),
            $to_currency->getCurrency(),
            $to_currency->getRate(),
            $amount
        );

        return $this->json([
            'amount' => round($converted_amount, 2)
        ]);
    }
}
