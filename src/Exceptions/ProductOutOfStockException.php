<?php

namespace Vgplay\Reward\Exceptions;

use Exception;

class ProductOutOfStockException extends Exception
{
    public function __construct($message = 'Đã hết hàng')
    {
        parent::__construct($message);
    }
}
