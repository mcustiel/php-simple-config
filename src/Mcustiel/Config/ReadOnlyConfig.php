<?php
namespace Mcustiel\Config;

use Mcustiel\Config\Util\ObjectArrayConverter;
use Mcustiel\Config\Exception\ConfigException;

class ReadOnlyConfig extends Config
{
    public function get($keyName)
    {
        if (isset($this->config[$keyName])) {
            $tmp = $this->config[$keyName];
            if (is_array($tmp)) {
                return new self($tmp);
            } else {
                return $tmp;
            }
        } else {
            throw new ConfigException(ConfigException::EXCEPTION_KEY_DOES_NOT_EXIST);
        }
    }
}
