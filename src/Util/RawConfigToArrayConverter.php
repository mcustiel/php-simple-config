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
namespace Mcustiel\Config\Util;

use Mcustiel\Config\Config;

/**
 * Converts a raw config array as it's used in Config class and converts it to
 * a pure PHP array.
 *
 * @author mcustiel
 */
class RawConfigToArrayConverter
{
    /**
     * Converts the rawConfig and returns a pure PHP array.
     *
     * @param array $rawConfig The config from the Config object
     *
     * @return array A pure PHP array
     */
    public function convert(array $rawConfig)
    {
        $return = [];
        foreach ($rawConfig as $key => $value) {
            $return[$key] = $this->getConfigValue($value);
        }

        return $return;
    }

    private function getConfigValue($value)
    {
        return $this->isAConfig($value) ? $value->getFullConfigAsArray() : $value;
    }

    private function isAConfig($value)
    {
        return is_object($value) && $value instanceof Config;
    }
}
