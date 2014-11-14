<?php
namespace Mcustiel\Config\Drivers\Reader\json;

use Mcustiel\Config\ConfigReader;
use Mcustiel\Config\Config;
use Mcustiel\Config\Drivers\Reader\BaseReader;
use Mcustiel\Config\Exception\ConfigException;

class Reader extends BaseReader implements ConfigReader
{
    public function read($filename)
    {
        $this->config = json_decode(file_get_contents($filename), true);
        if ($this->config == null) {
            throw new ConfigException(ConfigException::EXCEPTION_ERROR_PARSING_CONFIG);
        }
    }
}
