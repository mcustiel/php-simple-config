<?php
namespace mcustiel\config\drivers\reader\json;

use mcustiel\config\ConfigReader;
use mcustiel\config\Config;
use mcustiel\config\drivers\reader\BaseReader;
use mcustiel\config\exception\ConfigException;

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
