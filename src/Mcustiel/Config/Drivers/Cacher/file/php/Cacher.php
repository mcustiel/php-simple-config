<?php
namespace mcustiel\config\drivers\cacher\file\php;

use mcustiel\config\drivers\cacher\file\BaseCacher;

class Cacher extends BaseCacher
{
    protected function getSerializedConfig(array $config)
    {
        return "<?php\n\$config = " . var_export($config, true) . ';' . PHP_EOL;
    }

    protected function getUnserializedConfig()
    {
        @include $this->fullPath;
        return isset($config)? $config : null;
    }

    protected function generateFilePath()
    {
        return "{$this->path}/php.simple.config.cache.{$this->cacheName}.php";
    }
}
