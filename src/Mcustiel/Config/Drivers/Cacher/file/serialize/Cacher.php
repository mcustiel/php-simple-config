<?php
namespace mcustiel\config\drivers\cacher\file\serialize;

use mcustiel\config\drivers\cacher\file\BaseCacher;

class Cacher extends BaseCacher
{
    protected function getSerializedConfig(array $config)
    {
        return serialize($config);
    }

    protected function getUnserializedConfig()
    {
        $return = file_get_contents($this->fullPath);
        return ($return === false)? null : unserialize($return);
    }

    protected function generateFilePath()
    {
        return "{$this->path}/php.simple.config.cache.{$this->cacheName}.ser";
    }
}
