<?php

namespace Vgplay\Reward\Exceptions;

use Exception;

class ProductNotAvailableYetException extends Exception
{
    public function __construct($message = 'Sản phẩm hiện chưa có sẵn')
    {
        parent::__construct($message);
    }
}
