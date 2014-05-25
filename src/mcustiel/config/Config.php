<?php

namespace mcustiel\config;

use mcustiel\config\util\ObjectArrayConverter;
use mcustiel\config\exception\ConfigException;

class Config
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFullConfigAsArray()
    {
        return $this->config;
    }

    public function getFullConfigAsObject()
    {
        return ObjectArrayConverter::arrayToObject($this->config);
    }

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

    public function set($keyName, $value)
    {
        $this->config[$keyName] = $value;
    }
}
