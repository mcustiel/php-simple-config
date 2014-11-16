<?php
namespace Mcustiel\Config;

use Mcustiel\Config\Util\ObjectArrayConverter;

abstract class Config
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

    abstract public function get($keyName);
}
