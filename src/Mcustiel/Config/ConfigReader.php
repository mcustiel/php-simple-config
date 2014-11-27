<?php
namespace Mcustiel\Config;

/**
 * ConfigReader interface. It defines the methods that a Configuration Reader
 * should implement.
 *
 * @author mcustiel
 */
interface ConfigReader
{
    public function read($filename);
    public function getConfig();
}
