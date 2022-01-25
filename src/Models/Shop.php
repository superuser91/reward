<?php

namespace Vgplay\Reward\Models;

use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

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
