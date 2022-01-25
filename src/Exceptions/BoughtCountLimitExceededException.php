<?php

namespace Vgplay\Reward\Exceptions;

use Exception;

class BoughtCountLimitExceededException extends Exception
{
    public function __construct($message = 'Đã đạt giới hạn mua vật phẩm này.')
    {
        parent::__construct($message);
    }
}
