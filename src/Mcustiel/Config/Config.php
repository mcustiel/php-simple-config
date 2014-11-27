<?php
namespace Mcustiel\Config;

use Mcustiel\Config\Exception\ConfigKeyDoesNotExistException;
use Mcustiel\Config\Util\RawConfigToArrayConverter;

/**
 * Config type. Represents a configuration tree from the
 * configuration file and allows to get and set its properties.
 *
 * @author mcustiel
 */
class Config
{
    /**
     * Holds the configuration properties from a level of
     * the configuration tree.
     * @var array
     */
    private $config;
    /**
     * Converter service. Converts from a raw config array to a pure php array.
     * @var \Mcustiel\Config\Util\RawConfigToArrayConverter
     */
    private $converter;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->converter = new RawConfigToArrayConverter();
    }

    public function setConverter(RawConfigToArrayConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Returns this configuration tree as an array;
     * @return array
     */
    public function getFullConfigAsArray()
    {
        return $this->converter->convert($this->config);
    }

    public function set($keyName, $value)
    {
        $this->config[$keyName] = $value;
    }

    /**
     * Gets the value identified by keyName. If that value is an array it is converted
     * to a Config object before being returned and preserved for future invocations.
     * @param string $keyName
     *
     * @return mixed The value associated with the key
     * @throws ConfigKeyDoesNotExistException If Key is not found
     */
    public function get($keyName)
    {
        if (isset($this->config[$keyName])) {
            if (is_array($this->config[$keyName])) {
                $this->config[$keyName] = new self($this->config[$keyName]);
            }
            return $this->config[$keyName];
        }
        throw new ConfigKeyDoesNotExistException("The key {$keyName} does not exist in config");
    }
}
