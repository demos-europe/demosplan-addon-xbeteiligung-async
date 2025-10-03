<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use Exception;

class UnsupportedMessageTypeException extends Exception
{
    public function __construct($message = 'Unsupported message type', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
