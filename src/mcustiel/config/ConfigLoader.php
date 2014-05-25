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
        if ($this->cacher !== null) {
            $this->readFromCache();
        } else {
            $this->read();
        }

        return $this->config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function readFromCache()
    {
        $this->openCache();
        $this->cacher->setName($this->name);
        $this->loadFromCache();
        if ($this->config === null) {
            $this->read();
            $this->saveInCache();
        }
        $this->closeCache();
    }

    private function openCache()
    {
         $this->cacher->open();
    }

    private function closeCache()
    {
        $this->cacher->close();
    }

    private function saveInCache()
    {
        $this->cacher->save($this->config);
    }

    private function loadFromCache()
    {
        $this->config = $this->cacher->load();
    }

    private function read()
    {
        $this->reader->read($this->name);
        $this->config = $this->reader->getConfig();
    }
}
