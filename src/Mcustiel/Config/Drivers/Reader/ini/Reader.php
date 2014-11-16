<?php
namespace Mcustiel\Config\Drivers\Reader\ini;

use Mcustiel\Config\ConfigReader;
use Mcustiel\Config\Config;
use Mcustiel\Config\Drivers\Reader\ini\Helper\IniConfigExtender;
use Mcustiel\Config\Drivers\Reader\BaseReader;

class Reader extends BaseReader implements ConfigReader
{
    public function read($filename)
    {
        $this->config = parse_ini_file($filename, true);
        $this->extendConfig();
    }

    private function extendConfig()
    {
        $helper = new IniConfigExtender($this->config);
        $this->config = $helper->extendIniConfig();
    }
}
