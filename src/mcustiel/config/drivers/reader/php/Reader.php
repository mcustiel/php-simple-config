<?php
namespace mcustiel\config\drivers\reader\php;

use mcustiel\config\ConfigReader;
use mcustiel\config\Config;
use mcustiel\config\drivers\reader\BaseReader;

class Reader extends BaseReader implements ConfigReader
{
    public function read($filename)
    {
        include_once $filename;
        $this->config = $config;
    }
}
