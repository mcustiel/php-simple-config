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
namespace Mcustiel\Config\Drivers\Writer\ini;

use Mcustiel\Config\Config;

class Writer
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->getFullConfigAsArray();
    }

    public function write($filename)
    {
        file_put_contents($filename, $this->getIniFromConfigArray());
    }

    private function getIniFromConfigArray()
    {
        $arrays = [];
        $notArrays = [];
        foreach ($this->config as $key => $val) {
            if (is_array($val)) {
                $arrays[$key] = $this->generateKeysArrayFromArray($val);
            } else {
                $notArrays[$key] = $val;
            }
        }

        return $this->getIniPortionForNonPointedProperties($notArrays)
            . $this->getIniPortionForPointedProperties($arrays);
    }

    private function getIniPortionForNonPointedProperties(array $notArrays)
    {
        $ini = "";
        foreach ($notArrays as $key => $val) {
            $ini .= "$key = " . str_replace('"', '""', $val) .  "\n";
        }

        return $ini;
    }

    private function getIniPortionForPointedProperties(array $arrays)
    {
        $ini = "";
        foreach ($arrays as $key => $val) {
            $ini .= "\n[$key]\n";
            foreach ($val as $k => $v) {
                $ini .= "$k = " . str_replace('"', '""', $v) .  "\n";
            }
        }

        return $ini;
    }

    private function generateKeysArrayFromArray(array $array)
    {
        return $this->recursivelyGenerateKeys("", $array);
    }

    private function recursivelyGenerateKeys($prefix, $value)
    {
        if (is_array($value)) {
            return $this->getArrayKeysForPrefix($prefix, $value);
        } else {
            return [$prefix => $value];
        }

        return $return;
    }

    private function getArrayKeysForPrefix($prefix, $value)
    {
        $return = [];
        foreach ($value as $key => $val) {
            if (is_numeric($key) && !is_array($val)) {
                $return[$prefix] = $val;
            } else {
                $return = array_merge(
                    $return,
                    $this->recursivelyGenerateKeys(
                        $prefix . (empty($prefix)? '' : '.') . $key,
                        $val
                    )
                );
            }
        }

        return $return;
    }
}
