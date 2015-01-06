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

use Mcustiel\Config\Cacher;

class ConfigLoader
{
    /**
     * The reader object used to read and parse the file.
     * @var Mcustiel\Config\ConfigReader
     */
    private $reader;
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

    public function __construct($fileName, ConfigReader $reader, Cacher $cacher = null)
    {
        $this->name = $fileName;
        $this->reader = $reader;
        if ($cacher != null) {
            $this->cacher = $cacher;
        }
    }

    /**
     * Loads the configuration from the file and parses it.
     *
     * @return \Mcustiel\Config\Mcustiel\Config\Config The resulting Config object.
     */
    public function load()
    {
        if ($this->cacher !== null) {
            return $this->readFromCache();
        }

        return $this->read();
    }

    private function readFromCache()
    {
        if (($config = $this->cacher->getCachedConfig()) !== null) {
            return $config;
        }
        $config = $this->read();
        $this->cacher->cacheConfig($config);

        return $config;
    }

    private function read()
    {
        $this->reader->read($this->name);
        return $this->reader->getConfig();
    }
}
