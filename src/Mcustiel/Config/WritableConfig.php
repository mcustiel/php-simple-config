<?php
namespace Mcustiel\Config;

class WritableConfig extends Config
{
    public function __construct(array $config)
    {
        $config = $this->buildFrom($config);
        parent::__construct($config);
    }

    private function buildFrom(array $config)
    {
        $return = [];
        foreach ($config as $key => $val) {
            if (is_array($val)) {
                $return[$key] = new self($val);
            } else {
                $return[$key] = $val;
            }
        }
        return $return;
    }

    public function set($keyName, $value)
    {
        $this->config[$keyName] = $value;
    }

    public function get($keyName)
    {
        return $this->config[$keyName];
    }
}
