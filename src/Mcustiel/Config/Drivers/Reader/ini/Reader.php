<?php
namespace mcustiel\config\drivers\reader\ini;

use mcustiel\config\ConfigReader;
use mcustiel\config\Config;
use mcustiel\config\drivers\reader\ini\helper\IniConfigExtender;
use mcustiel\config\drivers\reader\BaseReader;

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
