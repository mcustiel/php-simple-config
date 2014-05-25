<?php
namespace mcustiel\config\drivers\reader\json;

use mcustiel\config\ConfigReader;
use mcustiel\config\Config;
use mcustiel\config\drivers\reader\BaseReader;

class Reader extends BaseReader implements ConfigReader
{
    public function read($filename)
    {
        $this->config = json_decode(file_get_contents($filename), true);
    }
}
