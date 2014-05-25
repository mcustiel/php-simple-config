<?php

namespace mcustiel\config;

class ConfigLoader
{
    private $reader;
    private $config;
    private $cacher;
    private $name;

    public function __construct($fileName, ConfigReader $reader, ConfigCacher $cacher = null)
    {
        $this->name = $fileName;
        $this->reader = $reader;
        $this->cacher = $cacher;
    }

    public function load()
    {
        $this->openCache();
        $this->loadFromCache();
        if ($this->config === null) {
            $this->read();
            $this->saveInCache();
        }
        $this->closeCache();
        return $this->config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function openCache()
    {
        if ($this->cacher !== null) {
            $this->cacher->open();
        }
    }

    private function closeCache()
    {
        if ($this->cacher !== null) {
            $this->cacher->close();
        }
    }

    private function saveInCache()
    {
        if ($this->cacher !== null) {
            $this->cacher->save($this->config);
        }
    }

    private function loadFromCache()
    {
        if ($this->cacher !== null) {
            $this->cacher->setName($this->name);
            if ($this->cacher->exists()) {
                $this->config = $this->cacher->load();
            }
        }
    }

    private function read()
    {
        $this->reader->read($this->name);
        $this->config = $this->reader->getConfig();
    }
}
