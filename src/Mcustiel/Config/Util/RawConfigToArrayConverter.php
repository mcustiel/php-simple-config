<?php
namespace Mcustiel\Config\Util;

use Mcustiel\Config\Config;

class RawConfigToArrayConverter
{
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
