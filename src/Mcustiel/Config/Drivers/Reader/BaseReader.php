<?php

namespace Mcustiel\Config\Drivers\Reader;

use Mcustiel\Config\Config;
use Mcustiel\Config\ConfigReader;

class BaseReader
{
    protected $config;

    public function getConfig()
    {
        return new Config($this->config);
    }
}
