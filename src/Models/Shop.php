<?php

namespace Vgplay\Reward\Models;

use Hacoidev\CachingModel\Contracts\Cacheable;
use Hacoidev\CachingModel\HasCache;
use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model implements Cacheable
{
    use HasFactory;
    use HasCache;

    protected $fillable = [
        'name',
        'slug',
        'game_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
