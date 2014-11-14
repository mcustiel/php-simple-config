<?php
namespace Mcustiel\Config;

interface ConfigReader
{
    public function read($filename);
    public function getConfig();
}
