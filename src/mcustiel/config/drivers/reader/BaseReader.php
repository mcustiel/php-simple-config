<?php

namespace mcustiel\config\drivers\reader;

use mcustiel\config\Config;
use mcustiel\config\ConfigReader;

class BaseReader
{
    protected $config;

    public function getConfig()
    {
        return new Config($this->config);
    }
}
