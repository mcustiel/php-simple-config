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
     * @var Mcustiel\Config\ConfigReader
     */
    private $reader;
    /**
     * The config object that resulted from parsing the file.
     * @var Mcustiel\Config\Config
     */
    private $config;
    /**
     * The cacher object used to cache the configuration.
     * @var Mcustiel\Config\ConfigCacher
     */
    private $cacher;
    /**
     * The path to the file that contains the config to parse.
     * @var string
     */
    private $name;

    public function __construct($fileName, ConfigReader $reader, ConfigCacher $cacher = null)
    {
        $this->name = $fileName;
        $this->reader = $reader;
        $this->cacher = $cacher;
    }

    /**
     * Loads the configuration from the file and parses it.
     *
     * @return \Mcustiel\Config\Mcustiel\Config\Config The resulting Config object.
     */
    public function load()
    {
        if ($this->cacher !== null) {
            $this->readFromCache();
        } else {
            $this->read();
        }

        return $this->config;
    }

    /**
     * Gets the Config object obtained from parsing the config file.
     *
     * @return \Mcustiel\Config\Mcustiel\Config\Config
     */
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
