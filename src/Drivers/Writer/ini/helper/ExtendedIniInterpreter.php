<?php
namespace Mcustiel\Config\Drivers\Writer\ini\helper;

class ExtendedIniInterpreter
{
    public function getIniFromConfigArray(array $config)
    {
        $arrays = [];
        $notArrays = [];
        foreach ($config as $key => $val) {
            if (is_array($val)) {
                $arrays[$key] = $this->generateKeysArrayFromArray($val);
            } else {
                $notArrays[$key] = $val;
            }
        }

        return $this->getIniPortionForNonPointedProperties($notArrays) .
             $this->getIniPortionForPointedProperties($arrays);
    }

    private function getIniPortionForNonPointedProperties(array $notArrays)
    {
        $ini = "";
        foreach ($notArrays as $key => $val) {
            $ini .= "$key = " . str_replace('"', '""', $val) . "\n";
        }

        return $ini;
    }

    private function getIniPortionForPointedProperties(array $arrays)
    {
        $ini = "";
        foreach ($arrays as $key => $val) {
            $ini .= "\n[$key]\n";
            foreach ($val as $k => $v) {
                $ini .= "$k = " . str_replace('"', '""', $v) . "\n";
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
            return [
                $prefix => $value
            ];
        }
    }

    private function getArrayKeysForPrefix($prefix, $value)
    {
        $return = [];
        foreach ($value as $key => $val) {
            if (is_numeric($key) && ! is_array($val)) {
                $return[$prefix] = $val;
            } else {
                $return = array_merge(
                    $return,
                    $this->recursivelyGenerateKeys(
                        $prefix . (empty($prefix) ? '' : '.') . $key,
                        $val
                    )
                );
            }
        }

        return $return;
    }
}
