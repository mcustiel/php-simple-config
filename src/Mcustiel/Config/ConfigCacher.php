<?php
namespace Mcustiel\Config;

/**
 * ConfigCacher interface. It defines the methods that
 * a Config cacher should implement.
 * @author mcustiel
 */
interface ConfigCacher
{
    public function open();
    public function close();
    public function setOptions(\stdClass $options);
    public function setName($name);
    public function save(Config $config);
    public function load();
}
