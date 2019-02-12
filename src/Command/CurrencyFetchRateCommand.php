<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\ClientECB;
use App\Service\ClientCBR;


class CurrencyFetchRateCommand extends Command
{
    const _ECB = 1;
    const _CBR = 2;

    protected static $defaultName = 'currency:fetch';

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
                    $client = new ClientECB();
                    $this->saveRates($client->fetch(), self::_ECB);
                    break;
                case 'CBR':
                    $io->note('Fetch CBR rates');
                    $client = new ClientCBR();
                    $this->saveRates($client->fetch(), self::_CBR);
                    break;
                default:
                    $io->note('You passed invalid argument');
            }
        } else {
            $io->note('Fetch both ECB and CBR rates');
            $clientECB = new ClientECB();
            $this->saveRates($clientECB->fetch(), self::_ECB);
            $clientCBR = new ClientCBR();
            $this->saveRates($clientCBR->fetch(), self::_CBR);
            var_dump(array_merge($clientECB->fetch(), $clientCBR->fetch()));
        }
    }

    private function saveRates($rates, $source)
    {
    }
}
