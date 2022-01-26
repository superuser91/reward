<?php

namespace Vgplay\Reward\Traits;

use Exception;
use Vgplay\Contracts\Product;

trait BuyerTrait
{
    public function payForReward(Product $reward)
    {
        if (!method_exists($this, sprintf('payBy%s', ucwords($reward->getPaymentUnit())))) {
            throw new Exception(sprintf('User class must implement payBy%s method to buy this reward', ucwords($reward->getPaymentUnit())));
        };

        return $this->{sprintf('payBy%s', ucwords($reward->getPaymentUnit()))}($reward->getPrice());
    }
}
