<?php
namespace Mcustiel\Config;

use Mcustiel\Config\Util\ObjectArrayConverter;
use Mcustiel\Config\Exception\ConfigKeyDoesNotExistException;

class Config
{
    protected $config;

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

    public function set($keyName, $value)
    {
        $this->config[$keyName] = $value;
    }

    public function get($keyName)
    {
        if (isset($this->config[$keyName])) {
            if (is_array($this->config[$keyName])) {
                $this->config[$keyName] = new self($this->config[$keyName]);
            }
            return $this->config[$keyName];
        }
        throw new ConfigKeyDoesNotExistException("The key {$keyName} does not exist in config");
    }
}
