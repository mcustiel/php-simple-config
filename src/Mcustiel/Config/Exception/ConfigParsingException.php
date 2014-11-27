<?php
namespace Mcustiel\Config\Exception;

/**
 * Exception to be thrown when config file can't be parsed.
 *
 * @author mcustiel
 */
class ConfigParsingException extends ConfigException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
