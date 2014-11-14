<?php

namespace mcustiel\config\drivers\cacher\file;

use mcustiel\config\ConfigCacher;
use mcustiel\config\Config;

abstract class BaseCacher implements ConfigCacher
{
    protected $cacheName;
    protected $path;
    protected $fullPath;

    public function __construct(\stdClass $options = null)
    {
        if ($options != null) {
            $this->setOptions($options);
        } else {
            $this->cacheName = "php-simple-config-default.cache.name";
            $this->path = sys_get_temp_dir();
        }
    }

    public function setOptions(\stdClass $options)
    {
        if (isset($options->path)) {
            $this->path = $options->path;
        }
        if (isset($options->name)) {
            $this->setName($options->name);
        }
        $this->fullPath = $this->generateFilePath();
    }

    public function open()
    {
    }

    public function close()
    {
    }

    public function setName($name)
    {
        $this->cacheName = str_replace(array('\\', '/', ':', ',', '?', '$', '~'), '-', $name);
        $this->fullPath = $this->generateFilePath();
    }

    public function save(Config $config)
    {
        file_put_contents($this->fullPath, $this->getSerializedConfig($config->getFullConfigAsArray()));
    }

    public function load()
    {
        $return = $this->getUnserializedConfig();
        if ($return != null) {
            $return = new Config($return);
        }

        return $return;
    }

    abstract protected function generateFilePath();
    abstract protected function getUnserializedConfig();
    abstract protected function getSerializedConfig(array $config);
}
