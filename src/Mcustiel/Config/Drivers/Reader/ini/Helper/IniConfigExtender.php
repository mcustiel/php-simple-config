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
namespace Mcustiel\Config\Drivers\Reader\ini\Helper;

class IniConfigExtender
{
    const SECTION_SEPARATOR = ":";
    const KEY_SEPARATOR = ".";

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function extendIniConfig()
    {
        return $this->iterateArray($this->config);
    }

    private function iterateArray($array)
    {
        foreach ($array as $key => $val) {
            $array[$key] = $this->checkRecursion($val);
            $tmp = $this->checkIfKeyIsParseableAndParseIt($key, $val);
            if ($tmp !== null) {
                unset($array[$key]);
                $array = array_merge_recursive($array, $tmp);
            }
        }

        return $array;
    }

    private function extendKeyWithSeparator($key, $val, $separator)
    {
        $tmpKey = explode($separator, $key);
        $tmp = $this->setArrayKeys($val, $tmpKey);

        return $tmp;
    }

    private function setArrayKeys($value, $arrKeys, $index = 0)
    {
        if ($index == count($arrKeys)) {
            return $value;
        }

        return [
            trim($arrKeys[$index]) => $this->setArrayKeys($value, $arrKeys, $index + 1)
        ];
    }

    private function checkRecursion($val)
    {
        if (is_array($val)) {
            $val = $this->iterateArray($val);
        }
        return $val;
    }

    private function checkIfKeyIsParseableAndParseIt($key, $val)
    {
        $tmp = null;
        if (strpos($key, self::KEY_SEPARATOR) !== false) {
            $tmp = $this->extendKeyWithSeparator($key, $val, self::KEY_SEPARATOR);
        } elseif (strpos($key, self::SECTION_SEPARATOR) !== false) {
            $tmp = $this->extendKeyWithSeparator($key, $val, self::SECTION_SEPARATOR);
        }

        return $tmp;
    }
}
