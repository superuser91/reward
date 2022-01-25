<?php

namespace Vgplay\Reward\Traits;

use Exception;
use Vgplay\Contracts\Product;

trait BuyerTrait
{
    public function payForReward(Product $reward)
    {
        if (!method_exists($this, sprintf('payBy%s', ucwords($reward->payment_unit)))) {
            throw new Exception(sprintf('User class must implement payBy%s method to buy this reward', ucwords($reward->payment_unit)));
        };

        return $this->{sprintf('payBy%s', ucwords($reward->payment_unit))}($reward->price);
    }
}
