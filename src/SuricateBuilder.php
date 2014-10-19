<?php

namespace Kpacha\Suricate;

use Guzzle\Http\Client;

class SuricateBuilder
{

    public static function build($suricateServerUrl)
    {
        return new Suricate(new Client($suricateServerUrl));
    }

}
