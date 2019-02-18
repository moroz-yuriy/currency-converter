<?php

namespace App\Command;

use App\Service\Bank;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\ECBBank;
use App\Service\CBRBank;
use App\Entity\CurrencyRate;


class CurrencyFetchRateCommand extends Command
{
    const _ECB = 1;
    const _CBR = 2;

    protected static $defaultName = 'currency:fetch';
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetch currency rate')
            ->addArgument('src', InputArgument::OPTIONAL, 'Fetch currency rate from source ECB or CBR');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $src = $input->getArgument('src');


        if ($src) {
            switch ($src) {
                case 'ECB':
                    $io->note('Fetch ECB rates');
                    $bank = new ECBBank();

                    break;
                case 'CBR':
                    $io->note('Fetch CBR rates');
                    $bank = new CBRBank();
                    break;
                default:
                    $io->note('You passed invalid argument');
            }

            $this->saveRates($bank);
        }
    }

    private function saveRates(Bank $bank)
    {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $this->container->get('doctrine')->getRepository(CurrencyRate::class);

        $rates = $bank->fetch();
        $source =  $bank->getBankID();

        foreach ($rates as $rate) {
            var_dump($rate);
            $currency_rate = $repository->findOneBy([
                'date' => new \DateTime($rate->date),
                'currency' => $rate->currency,
                'source' => $source
            ]);

            if (!$currency_rate) {
                $currency_rate = new CurrencyRate();
            }

            $currency_rate->setDate(new \DateTime($rate->date));
            $currency_rate->setCurrency($rate->currency);
            $currency_rate->setRate($rate->rate);
            $currency_rate->setSource($source);

            $em->persist($currency_rate);
            $em->flush();
        }
    }
}
