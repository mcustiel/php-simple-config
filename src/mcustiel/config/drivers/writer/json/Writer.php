<?php
namespace mcustiel\config\drivers\writer\json;

use mcustiel\config\Config;

class Writer
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->getFullConfigAsArray();
    }

    public function write($filename)
    {
        file_put_contents($filename, json_encode($this->config, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));
    }
}
