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

namespace Mcustiel\Config\Drivers\Cacher\file;

use Mcustiel\Config\ConfigCacher;
use Mcustiel\Config\Config;

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
        $this->createDir(dirname($this->fullPath));
        file_put_contents($this->fullPath, $this->getSerializedConfig($config->getFullConfigAsArray()));
    }

    public function load()
    {
        if (file_exists($this->fullPath)) {
            $return = $this->getUnserializedConfig();
            if ($return != null) {
                $return = new Config($return);
            }

            return $return;
        }
        return null;
    }

    private function createDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    abstract protected function generateFilePath();
    abstract protected function getUnserializedConfig();
    abstract protected function getSerializedConfig(array $config);
}
