<?php
namespace mcustiel\config\exception;

class ConfigException extends \Exception
{
    private static $exceptions = array(
        0 => "Unknown exception",
        "The selected key does not exist"
    );
    const EXCEPTION_UNKNOWN = 0;
    const EXCEPTION_KEY_DOES_NOT_EXIST = 1;

    public function __construct($exceptionId, \Exception $previous = null)
    {
        if (isset(self::$exceptions[$exceptionId])) {
            parent::__construct(self::$exceptions[$exceptionId], $exceptionId, $previous);
        } else {
            parent::__construct(self::$exceptions[self::EXCEPTION_UNKNOWN], self::EXCEPTION_UNKNOWN, $previous);
        }
    }
}
