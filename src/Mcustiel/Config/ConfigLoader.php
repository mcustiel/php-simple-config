<?php

namespace Mcustiel\Config;

class ConfigLoader
{
    /**
     *
     * @var Mcustiel\Config\ConfigReader
     */
    private $reader;
    /**
     *
     * @var Mcustiel\Config\Config
     */
    private $config;
    /**
     *
     * @var Mcustiel\Config\ConfigCacher
     */
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
        $this->cacher->open();
        $this->loadFromCache();
        if ($this->config === null) {
            $this->read();
            $this->cacher->save($this->config);
        }
        $this->cacher->close();
    }

    private function loadFromCache()
    {
        $this->cacher->setName($this->name);
        $this->config = $this->cacher->load();
    }

    private function read()
    {
        $this->reader->read($this->name);
        $this->config = $this->reader->getConfig();
    }
}
