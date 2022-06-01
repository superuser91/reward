<?php

namespace Vgplay\Reward\Models;

use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'status',
        'note',
        'quantity',
        'amount',
        'payment_unit',
        'extras'
    ];

    protected $casts = [
        'extras' => 'json'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
