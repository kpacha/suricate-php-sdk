<?php

namespace Kpacha\Suricate\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Kpacha\Suricate\SuricateBuilder;
use Guzzle\Http\Client;

class SendHeartBeats extends Command
{

    protected function configure()
    {
        $this->setName('suricate:heartbeat')
                ->setDescription('Put a node by in a service cluster and update it periodically')
                ->addOption('service', 's', InputOption::VALUE_REQUIRED, 'name of the servie')
                ->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'id of the node')
                ->addOption('node', null, InputOption::VALUE_REQUIRED, 'the node in json format')
                ->addOption('total', 't', InputOption::VALUE_REQUIRED, 'the total heartbeats to send')
                ->addOption('wait', 'w', InputOption::VALUE_REQUIRED,
                        'the sleep time between consecutive heartbeats in secs')
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
        if (!($node = $input->getOption('node'))) {
            throw new \InvalidArgumentException("Specify a node");
        }
        if (!($total = $input->getOption('total'))) {
            throw new \InvalidArgumentException("Specify a number of heartbeats to send");
        }
        if (!($sleep = $input->getOption('wait'))) {
            throw new \InvalidArgumentException("Specify a wait time between heartbeats");
        }
        $suritcateServerUrl = $input->getArgument('suricate-server');

        $suricateClient = SuricateBuilder::build($suritcateServerUrl);

        $node = json_decode($node);

        while ($total) {
            $node->registrationTimeUTC = microtime(true) * 1000;

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $output->writeln("<comment>Sending a new Heartbeat. Pending </comment>$total");
                if (OutputInterface::VERBOSITY_VERY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln("Node to put: " . print_r($node, true));
                }
            }

            $success = $suricateClient->putService($serviceName, $nodeId, $node);
            if(!$success && OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()){
                $output->writeln("<error>Heartbeat failed!</error>");
            }
            $total -= $success;
            sleep($sleep);
        }

        $output->writeln("Done");
    }

}
