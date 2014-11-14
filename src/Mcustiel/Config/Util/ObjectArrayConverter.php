<?php
namespace Mcustiel\Config\Util;

class ObjectArrayConverter
{
    public static function arrayToObject(array $array)
    {
    	$tmp = array();
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$tmp[$k] = self::arrayToObject($v);
			} else {
				$tmp[$k] = $v;
			}
		}
		return (object) $tmp;
    }
}
