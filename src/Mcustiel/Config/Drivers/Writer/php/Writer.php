<?php
namespace Mcustiel\Config\Drivers\Writer\php;

use Mcustiel\Config\Config;

class Writer
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->getFullConfigAsArray();
    }

    public function write($filename)
    {
        file_put_contents($filename, "<?php\n\$config = " . var_export($this->config, true) . ';' . PHP_EOL);
    }
}
