<?php
namespace Mcustiel\Config\Drivers\Reader\ini\Helper;

class IniConfigExtender
{
    const SECTION_SEPARATOR = ":";
    const KEY_SEPARATOR = ".";

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function extendIniConfig()
    {
        return $this->iterateArray($this->config);
    }

    private function iterateArray($array)
    {
        foreach ($array as $key => $val) {
            $array[$key] = $this->checkRecursion($val);
            $tmp = $this->checkIfKeyIsParseableAndParseIt($key, $val);
            if ($tmp !== null) {
            	unset($array[$key]);
            	$array = array_merge_recursive($array, $tmp);
            }
        }

        return $array;
    }

    private function extendKeyWithSeparator($key, $val, $separator)
    {
        $tmpKey = explode($separator, $key);
        $tmp = $this->setArrayKeys($val, $tmpKey);

        return $tmp;
    }

    private function setArrayKeys($value, $arrKeys, $index = 0)
    {
    	if ($index == count($arrKeys)) {
    		return $value;
    	}

    	return [trim($arrKeys[$index]) => $this->setArrayKeys($value, $arrKeys, $index+1)];
    }

    private function checkRecursion($val)
    {
        if (is_array($val)) {
            $val =  $this->iterateArray($val);
        }
        return $val;
    }

    private function checkIfKeyIsParseableAndParseIt($key, $val)
    {
        $tmp = null;
        if (strpos($key, self::KEY_SEPARATOR) !== false) {
            $tmp = $this->extendKeyWithSeparator($key, $val, self::KEY_SEPARATOR);
        } elseif (strpos($key, self::SECTION_SEPARATOR) !== false) {
            $tmp = $this->extendKeyWithSeparator($key, $val, self::SECTION_SEPARATOR);
        }

        return $tmp;
    }
}
