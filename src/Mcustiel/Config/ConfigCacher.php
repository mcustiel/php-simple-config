<?php
namespace Mcustiel\Config;

interface ConfigCacher
{
    public function open();
    public function close();
    public function setOptions(\stdClass $options);
    public function setName($name);
    public function save(Config $config);
    public function load();
}
