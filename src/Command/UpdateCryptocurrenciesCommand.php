<?php

namespace App\Command;

use App\Service\CallApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCryptocurrenciesCommand extends Command
{
    protected static $defaultName = 'app:update-cryptocurrencies';
    protected static $defaultDescription = 'Update cryptocurrencies';
    private $__callApi;

    public function __construct(CallApi $callApi)
    {
        parent::__construct();
        $this->__callApi = $callApi;
    }


    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->__callApi->updateCryptocurrencyData();

        return Command::SUCCESS;
    }


}
