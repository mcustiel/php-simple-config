<?php
namespace Mcustiel\Config\Drivers\Writer\ini;

use Mcustiel\Config\Config;

class Writer
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->getFullConfigAsArray();
    }

    public function write($filename)
    {
        file_put_contents($filename, $this->getIniFromConfigArray());
    }

    private function getIniFromConfigArray()
    {
        $arrays = [];
        $notArrays = [];
        foreach ($this->config as $key => $val) {
            if (is_array($val)) {
                $arrays[$key] = $this->generateKeysArrayFromArray($val);
            } else {
                $notArrays[$key] = $val;
            }
        }

        return $this->getIniPortionForNonPointedProperties($notArrays)
            . $this->getIniPortionForPointedProperties($arrays);
    }

    private function getIniPortionForNonPointedProperties(array $notArrays)
    {
        $ini = "";
        foreach ($notArrays as $key => $val) {
            $ini .= "$key = " . str_replace('"', '""', $val) .  "\n";
        }

        return $ini;
    }

    private function getIniPortionForPointedProperties(array $arrays)
    {
        $ini = "";
        foreach ($arrays as $key => $val) {
            $ini .= "\n[$key]\n";
            foreach ($val as $k => $v) {
                $ini .= "$k = " . str_replace('"', '""', $v) .  "\n";
            }
        }

        return $ini;
    }

    private function generateKeysArrayFromArray(array $array)
    {
        return $this->recursivelyGenerateKeys("", $array);
    }

    private function recursivelyGenerateKeys($prefix, $value)
    {
        if (is_array($value)) {
            return $this->getArrayKeysForPrefix($prefix, $value);
        } else {
            return [$prefix => $value];
        }

        return $return;
    }

    private function getArrayKeysForPrefix($prefix, $value)
    {
        $return = [];
        foreach ($value as $key => $val) {
            if (is_numeric($key) && !is_array($val)) {
                $return[$prefix] = $val;
            } else {
                $return = array_merge(
                    $return,
                    $this->recursivelyGenerateKeys(
                        $prefix . (empty($prefix)? '' : '.') . $key,
                        $val
                    )
                );
            }
        }

        return $return;
    }
}
