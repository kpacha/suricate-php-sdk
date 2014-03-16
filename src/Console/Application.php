<?php

namespace Kpacha\Suricate\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Kpacha\Suricate\Command\Get;
use Kpacha\Suricate\Command\GetAll;
use Kpacha\Suricate\Command\GetAllNames;
use Kpacha\Suricate\Command\PutService;
use Kpacha\Suricate\Command\RemoveService;
use Kpacha\Suricate\Command\SendHeartBeats;

class Application extends BaseApplication
{

    const APP_NAME = 'Suricate';
    const VERSION = '0.0.1-Beta';
    private static $logo = <<<LOGO

----------------------------------------------------------------------------<comment>
 .oooooo..o                       o8o                          .
d8P'    `Y8                       `"'                        .o8
Y88bo.      oooo  oooo  oooo d8b oooo   .ooooo.   .oooo.   .o888oo  .ooooo.
 `"Y8888o.  `888  `888  `888""8P `888  d88' `"Y8 `P  )88b    888   d88' `88b
     `"Y88b  888   888   888      888  888        .oP"888    888   888ooo888
oo     .d8P  888   888   888      888  888   .o8 d8(  888    888 . 888    .o
8""88888P'   `V88V"V8P' d888b    o888o `Y8bod8P' `Y888""8o   "888" `Y8bod8P' </comment>
----------------------------------------------------------------------------


LOGO;

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::VERSION);
    }

    protected function getDefaultCommands()
    {
        return array_merge(
                        parent::getDefaultCommands(),
                        array(new Get, new GetAll, new GetAllNames, new PutService, new RemoveService, new SendHeartBeats)
        );
    }

    public function getLongVersion()
    {
        return self::$logo . parent::getLongVersion();
    }

}