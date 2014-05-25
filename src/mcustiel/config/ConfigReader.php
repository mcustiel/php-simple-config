<?php
namespace mcustiel\config;

interface ConfigReader
{
    public function read($filename);
    public function getConfig();
}
