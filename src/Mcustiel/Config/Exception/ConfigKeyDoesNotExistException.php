<?php
namespace Mcustiel\Config\Exception;

/**
 * Exception to be thrown when a config key does not exist.
 *
 * @author mcustiel
 */
class ConfigKeyDoesNotExistException extends ConfigException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
