<?php
namespace Mcustiel\Config\Drivers\Cacher\file\php;

use Mcustiel\Config\Drivers\Cacher\file\BaseCacher;

class Cacher extends BaseCacher
{
    protected function getSerializedConfig(array $config)
    {
        return '<?php' . PHP_EOL . 'return ' . var_export($config, true) . ';' . PHP_EOL;
    }

    protected function getUnserializedConfig()
    {
        $config = @include $this->fullPath;
        return is_array($config)? $config : null;
    }

    protected function generateFilePath()
    {
        return "{$this->path}/php.simple.config.cache.{$this->cacheName}.php";
    }
}
