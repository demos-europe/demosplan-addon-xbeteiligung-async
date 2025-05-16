<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use Exception;

class ProjectPrefixNotFoundException extends Exception
{
    protected $message = 'No project prefix found.';
}