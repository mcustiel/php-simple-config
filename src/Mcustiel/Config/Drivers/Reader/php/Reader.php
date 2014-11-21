<?php
namespace Mcustiel\Config\Drivers\Reader\php;

use Mcustiel\Config\ConfigReader;
use Mcustiel\Config\Config;
use Mcustiel\Config\Drivers\Reader\BaseReader;

class Reader extends BaseReader implements ConfigReader
{
    public function read($filename)
    {
        $this->config = @include $filename;
    }
}
