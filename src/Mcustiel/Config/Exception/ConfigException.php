<?php
namespace Mcustiel\Config\Exception;

/**
 * This is the base exception for php-simple-config.
 *
 * @author mcustiel
 */
class ConfigException extends \Exception
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
