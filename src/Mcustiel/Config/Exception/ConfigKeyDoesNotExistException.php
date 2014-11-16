<?php
namespace Mcustiel\Config\Exception;

class ConfigKeyDoesNotExistException extends ConfigException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
