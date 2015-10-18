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

use Mcustiel\SimpleCache\Interfaces\CacheInterface;
use Mcustiel\SimpleCache\Interfaces\KeyInterface;
use Mcustiel\SimpleCache\Types\Key;

/**
 * Class used to read and write Configs from and to a PHP cache file.
 *
 * @author mcustiel
 */
class CacheConfig
{
    /**
     * @var \Mcustiel\SimpleCache\Interfaces\CacheInterface
     */
    private $cacheManager;
    /**
     * @var \Mcustiel\SimpleCache\Interfaces\KeyInterface
     */
    private $key;
    /**
     * @var integer
     */
    private $ttl;

    /**
     * @param \Mcustiel\SimpleCache\Interfaces\CacheInterface $cacheManager
     * @param string                                          $key
     * @param integer                                         $ttl
     */
    public function __construct(CacheInterface $cacheManager, $key, $ttl)
    {
        $this->cacheManager = $cacheManager;
        $this->key = new Key($key);
        $this->ttl = $ttl;
    }

    /**
     * @return \Mcustiel\SimpleCache\Interfaces\CacheInterface
     */
    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    /**
     * @return \Mcustiel\SimpleCache\Interfaces\KeyInterface
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }
}
