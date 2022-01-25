<?php

namespace Vgplay\Reward\Exceptions;

use Exception;

class MissingPaymentMethodException extends Exception
{
    public function __construct($message = 'Thiếu thông tin thanh toán')
    {
        parent::__construct($message);
    }
}
