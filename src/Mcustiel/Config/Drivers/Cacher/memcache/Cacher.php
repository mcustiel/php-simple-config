<?php
namespace Mcustiel\Config\Drivers\Cacher\memcache;

use Mcustiel\Config\ConfigCacher;
use Mcustiel\Config\Config;

class Cacher implements ConfigCacher
{
    private $cacheName;
    private $client;

    private $host;
    private $port;
    private $timeout;

    public function __construct(\stdClass $options = null)
    {
        $this->host = 'localhost';
        $this->port = 11211;
        $this->timeout = 1;
        $this->client = new \Memcache();
        if ($options != null) {
            $this->setOptions($options);
        }
    }

    public function setOptions(\stdClass $options)
    {
        if (isset($options->host)) {
            $this->host = $options->host;
        }
        if (isset($options->port)) {
            $this->port = $options->port;
        }
        if (isset($options->timeout)) {
            $this->timeout = $options->timeout;
        }
        if (isset($options->name)) {
            $this->cacheName = $options->name;
        }
    }

    public function setName($name)
    {
        $this->cacheName = "php.simple.config.cache.{$name}";
    }

    public function open()
    {
        $this->client->connect($this->host, $this->port, $this->timeout);
    }

    public function close()
    {
        $this->client->close();
    }

    public function save(Config $config)
    {
        $this->client->set($this->cacheName, $this->getSerializedConfig($config->getFullConfigAsArray()));
    }

    public function load()
    {
        $return = $this->getUnserializedConfig();
        if ($return != null) {
            $return = new Config($return);
        }

        return $return;
    }

    private function getSerializedConfig(array $config)
    {
        return serialize($config);
    }

    private function getUnserializedConfig()
    {
        $config = $this->client->get($this->cacheName);
        $config = ($config === false)? null : unserialize($config);

        return $config;
    }
}
