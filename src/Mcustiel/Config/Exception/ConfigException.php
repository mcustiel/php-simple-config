<?php
namespace Mcustiel\Config\Exception;

class ConfigException extends \Exception
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
