<?php
/**
 * This file is part of php-simple-config.
 * 
 * php-simple-config is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * php-simple-config is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with php-simple-config.  If not, see <http://www.gnu.org/licenses/>.
 */
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
