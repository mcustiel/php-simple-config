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
namespace Mcustiel\Config\Exception;

/**
 * This is the base exception for php-simple-config.
 *
 * @author mcustiel
 */
class ConfigException extends \Exception
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
