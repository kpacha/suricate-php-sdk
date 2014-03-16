suricate-php-sdk
================

A suricate sdk for php

[![Build Status](https://travis-ci.org/kpacha/suricate-php-sdk.png?branch=master)](https://travis-ci.org/kpacha/suricate-php-sdk)

#Requirements

* git
* [suricate](https://github.com/kpacha/suricate) server (or any other [curator-x-discovery-service](http://curator.apache.org/curator-x-discovery-server/index.html))
* php 5.3.3+
* curl extension

#Installation

##Standalone

###Git installation

Clone the repo

    $ git clone https://github.com/kpacha/suricate-php-sdk.git

Install the php dependencies

    $ cd suricate-php-sdk
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install

###Composer installation

Create a project with composer

    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar create-project kpacha/suricate-php-sdk [directory]

Remeber to set the [directory] parameter or composer will create the project in your current path.

##As a library

Include the `kpacha/suricate-php-sdk` package in your compose.json with all the dependencies of your project

    "require":{
        "kpacha/suricate-php-sdk": "dev-master"
    }

#Usage

The suricate sdk comes with a simple client and several console commands bundled in a simple app.

###Suricate client

The `Kpacha\Suricate\Suricate` constructor requires a `Guzzle\Http\Client` rest client. 

    $suricateClient = new Suricate(new Client($suricateServerUrl));

And now the `$suricateClient` object is ready to work. Here you have some examples

    $success = $suricateClient->putService($serviceName, $nodeId, $node);
    $serviceNames = $suricateClient->getAllNames();
    $nodes = $suricateClient->getAll($serviceName);
    $node = $suricateClient->get($serviceName, $nodeId);
    $success = $suricateClient->removeService($serviceName, $nodeId);

Check the [test](tests/SuricateTest.php) for more details.

###Suricate console app

Run the `suricate` script to trigger any console command. You can use them as a:

* simple manager limited to some basic interactions with your suricate service
* simple agent to send some heartbeats to suricate (it should be monitored by some external tool, restarting it periodically). This way, suricate will be aware of the status of your node.
* base for your more complex consumption patterns. Usually, you will also need to fetch all the available nodes registered under the services you are interested in.

Check out the `list` built-in command and get a list of all the available commands.

    $ php bin/suricate list
    
    ----------------------------------------------------------------------------
     .oooooo..o                       o8o                          .             
    d8P'    `Y8                       `"'                        .o8             
    Y88bo.      oooo  oooo  oooo d8b oooo   .ooooo.   .oooo.   .o888oo  .ooooo.  
     `"Y8888o.  `888  `888  `888""8P `888  d88' `"Y8 `P  )88b    888   d88' `88b 
         `"Y88b  888   888   888      888  888        .oP"888    888   888ooo888 
    oo     .d8P  888   888   888      888  888   .o8 d8(  888    888 . 888    .o 
    8""88888P'   `V88V"V8P' d888b    o888o `Y8bod8P' `Y888""8o   "888" `Y8bod8P' 
    ----------------------------------------------------------------------------

    Suricate version 0.0.1-Beta

    Usage:
      [options] command [arguments]

    Options:
      --help           -h Display this help message.
      --quiet          -q Do not output any message.
      --verbose        -v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
      --version        -V Display this application version.
      --ansi              Force ANSI output.
      --no-ansi           Disable ANSI output.
      --no-interaction -n Do not ask any interactive question.

    Available commands:
      help                 Displays help for a command
      list                 Lists commands
    suricate
      suricate:all         Get a list of all the registered nodes by a given service (getAll)
      suricate:get         Get a node by a given service and id (get)
      suricate:heartbeat   Put a node by in a service cluster and update it periodically
      suricate:names       Get a list of all the registered service names (getAllNames)
      suricate:put         Put a node in a service cluster (putService)
      suricate:remove      Remove a node by a given service and id (removeService)

Remember, you can also get more details about any command just adding the `--help` option.

    $ php bin/suricate suricate:heartbeat --help
    Usage:
     suricate:heartbeat [-s|--service="..."] [-i|--id="..."] [--node="..."] [-t|--total="..."] [-w|--wait="..."] suricate-server

    Arguments:
     suricate-server       the suricate server url

    Options:
     --service (-s)        name of the servie
     --id (-i)             id of the node
     --node                the node in json format
     --total (-t)          the total heartbeats to send
     --wait (-w)           the sleep time between consecutive heartbeats in secs
     --help (-h)           Display this help message.
     --quiet (-q)          Do not output any message.
     --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
     --version (-V)        Display this application version.
     --ansi                Force ANSI output.
     --no-ansi             Disable ANSI output.
     --no-interaction (-n) Do not ask any interactive question.

Suricate only accepts payloads as `String`, so you should model your data with that in mind and, maybe, instead of seeing it as a limitation and send just plain texts, you would add some serializated info. Just remember, ZooKeeper is not a database, so use it with caution!