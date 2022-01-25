<?php

namespace Vgplay\Reward\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vgplay\Reward\Models\Product;

trait PurchaseableTrait
{
    public function purchaseables(): MorphMany
    {
        return $this->morphMany(Product::class, 'purchaseable');
    }
}
