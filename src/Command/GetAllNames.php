<?php

namespace Kpacha\Suricate\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Kpacha\Suricate\SuricateBuilder;
use Guzzle\Http\Client;

class GetAllNames extends Command
{

    protected function configure()
    {
        $this->setName('suricate:names')
                ->setDescription('Get a list of all the registered service names (getAllNames)')
                ->addArgument('suricate-server', InputArgument::REQUIRED, 'the suricate server url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $suritcateServerUrl = $input->getArgument('suricate-server');

        $suricateClient = SuricateBuilder::build($suritcateServerUrl);

        $output->writeln(print_r($suricateClient->getAllNames(), true));
    }

}
