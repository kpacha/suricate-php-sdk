<?php

namespace Kpacha\Suricate\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Kpacha\Suricate\SuricateBuilder;
use Guzzle\Http\Client;

class GetAll extends Command
{

    protected function configure()
    {
        $this->setName('suricate:all')
                ->setDescription('Get a list of all the registered nodes by a given service (getAll)')
                ->addOption('service', 's', InputOption::VALUE_REQUIRED, 'name of the servie')
                ->addArgument('suricate-server', InputArgument::REQUIRED, 'the suricate server url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!($serviceName = $input->getOption('service'))) {
            throw new \InvalidArgumentException("Specify a service name");
        }
        $suritcateServerUrl = $input->getArgument('suricate-server');

        $suricateClient = SuricateBuilder::build($suritcateServerUrl);

        $output->writeln(print_r($suricateClient->getAll($serviceName), true));
    }

}
