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

use Mcustiel\Config\Exception\ConfigKeyDoesNotExistException;
use Mcustiel\Config\Util\RawConfigToArrayConverter;

/**
 * Config type. Represents a configuration tree from the
 * configuration file and allows to get and set its properties.
 *
 * @author mcustiel
 */
class Config
{
    /**
     * Holds the configuration properties from a level of
     * the configuration tree.
     * @var array
     */
    private $config;
    /**
     * Converter service. Converts from a raw config array to a pure php array.
     * @var \Mcustiel\Config\Util\RawConfigToArrayConverter
     */
    private $converter;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->converter = new RawConfigToArrayConverter();
    }

    public function setConverter(RawConfigToArrayConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Returns this configuration tree as an array;
     * @return array
     */
    public function getFullConfigAsArray()
    {
        return $this->converter->convert($this->config);
    }

    public function set($keyName, $value)
    {
        $this->config[$keyName] = $value;
    }

    /**
     * Gets the value identified by keyName. If that value is an array it is converted
     * to a Config object before being returned and preserved for future invocations.
     * @param string $keyName
     *
     * @return mixed The value associated with the key
     * @throws ConfigKeyDoesNotExistException If Key is not found
     */
    public function get($keyName)
    {
        if (isset($this->config[$keyName])) {
            if (is_array($this->config[$keyName])) {
                $this->config[$keyName] = new self($this->config[$keyName]);
            }
            return $this->config[$keyName];
        }
        throw new ConfigKeyDoesNotExistException("The key {$keyName} does not exist in config");
    }
}
