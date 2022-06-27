<?php

namespace Vgplay\Reward\Models;

use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vgplay\LaravelRedisModel\Contracts\Cacheable;
use Vgplay\LaravelRedisModel\HasCache;

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
