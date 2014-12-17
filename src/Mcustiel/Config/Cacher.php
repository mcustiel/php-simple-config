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

use Mcustiel\Config\Config;

/**
 * Class used to read and write Configs from and to a PHP cache file.
 *
 * @author mcustiel
 */
class Cacher
{
    private $fullPath;

    public function __construct($path, $name)
    {
        $this->fullPath = $path . $name . '.php';
    }

    protected function getSerializedConfig(array $config)
    {
        return '<?php' . PHP_EOL . 'return ' . var_export($config, true) . ';' . PHP_EOL;
    }

    /**
     * Loads a config from a cache file.
     *
     * @return NULL|\Mcustiel\Config\Config The cached config if it exists, null otherwise.
     */
    public function getCachedConfig()
    {
        $config = @include $this->fullPath;
        return is_array($config) ? new Config($config) : null;
    }

    /**
     * Writes the config to a PHP cache file.
     *
     * @param Mcustiel\Config\Config $config The config to write to cache.
     */
    public function cacheConfig(Config $config)
    {
        file_put_contents(
            $this->fullPath,
            $this->getSerializedConfig($config->getFullConfigAsArray())
        );
    }
}
