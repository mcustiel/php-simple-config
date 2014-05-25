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
        return unserialize(file_get_contents($this->fullPath));
    }

    protected function generateFilePath()
    {
        return "{$this->path}/php.simple.config.cache.{$this->cacheName}.ser";
    }
}
