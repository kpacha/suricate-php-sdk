<?php

namespace Kpacha\Suricate\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Kpacha\Suricate\Suricate;
use Guzzle\Http\Client;

class RemoveService extends Command
{

    protected function configure()
    {
        $this->setName('suricate:remove')
                ->setDescription('Remove a node by a given service and id (removeService)')
                ->addOption('service', 's', InputOption::VALUE_REQUIRED, 'name of the servie')
                ->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'id of the node')
                ->addArgument('suricate-server', InputArgument::REQUIRED, 'the suricate server url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!($serviceName = $input->getOption('service'))) {
            throw new \InvalidArgumentException("Specify a service name");
        }
        if (!($nodeId = $input->getOption('id'))) {
            throw new \InvalidArgumentException("Specify a node id");
        }

        $suritcateServerUrl = $input->getArgument('suricate-server');

        $suricateClient = new Suricate(new Client($suritcateServerUrl));

        $output->writeln(print_r($suricateClient->removeService($serviceName, $nodeId), true));
    }

}
