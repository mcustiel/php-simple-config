<?php
namespace Mcustiel\Config\Exception;

class ConfigParsingException extends ConfigException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
