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

namespace Mcustiel\Config;

class ConfigLoader
{
    /**
     * The reader object used to read and parse the file.
     * @var \Mcustiel\Config\ConfigReader
     */
    private $reader;
    /**
     * The cacher object used to cache the configuration.
     * @var CacheConfig
     */
    private $cacheConfig;
    /**
     * The path to the file that contains the config to parse.
     * @var string
     */
    private $name;

    /**
     * @param string       $fileName
     * @param ConfigReader $reader
     * @param CacheConfig  $cacheconfig
     */
    public function __construct(
        $fileName,
        ConfigReader $reader,
        CacheConfig $cacheconfig = null
    )
    {
        $this->name = $fileName;
        $this->reader = $reader;
        $this->cacheConfig = $cacheconfig;
    }

    /**
     * Loads the configuration from the file and parses it. If cacheConfig was given in constructor
     * tryies to load it from cache first.
     *
     * @return \Mcustiel\Config\Mcustiel\Config\Config The resulting Config object.
     */
    public function load()
    {
        if ($this->cacheConfig !== null) {
            return $this->readFromCache();
        }

        return $this->read();
    }

    /**
     * Tries to read the configuration from cache, if it's not cached loads it from file and caches it.
     *
     * @return array|object
     */
    private function readFromCache()
    {
        if (($config = $this->cacheConfig->getCacheManager()->get($this->cacheConfig->getKey())) !== null) {
            return $config;
        }
        $config = $this->read();
        $this->cacheConfig->getCacheManager()->set(
            $this->cacheConfig->getKey(),
            $config,
            $this->cacheConfig->getTtl()
        );

        return $config;
    }

    /**
     * Reads a configuration and returns it.
     */
    private function read()
    {
        $this->reader->read($this->name);
        return $this->reader->getConfig();
    }
}
