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
namespace Mcustiel\Config\Drivers\Writer\php;

use Mcustiel\Config\Config;
use Mcustiel\Config\Exception\ConfigWritingException;
use Mcustiel\Config\Drivers\Writer\Writer as BaseWriter;

class Writer extends BaseWriter
{
    public function write($filename)
    {
        if (file_put_contents(
            $filename,
            '<?php' . PHP_EOL . 'return ' . var_export($this->getConfig(), true) . ';' . PHP_EOL
        ) === false) {
            throw new ConfigWritingException(
                "An error occurred while writing config to {$filename} in php format"
            );
        }
    }
}
