<?php

namespace Vgplay\Reward\Exceptions;

use Exception;

class ViolateConditionException extends Exception
{
    public function __construct($message = 'Không đạt điều kiện.')
    {
        parent::__construct($message);
    }
}
