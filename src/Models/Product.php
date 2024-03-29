<?php

namespace Vgplay\Reward\Models;

use Hacoidev\CachingModel\Contracts\Cacheable;
use Hacoidev\CachingModel\HasCache;
use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\Contracts\Deliverable;
use Vgplay\Contracts\Player;
use Vgplay\Contracts\Product as ProductContract;
use Vgplay\Reward\Exceptions\BoughtCountLimitExceededException;
use Vgplay\Reward\Exceptions\ProductNotAvailableYetException;
use Vgplay\Reward\Exceptions\ProductOutOfStockException;
use Vgplay\Reward\Exceptions\ViolateConditionException;

class Product extends Model implements ProductContract, Cacheable
{
    use HasFactory;
    use SoftDeletes;
    use HasCache;

    protected $fillable = [
        'shop_id',
        'name',
        'picture',
        'purchaseable_type',
        'purchaseable_id',
        'price',
        'payment_unit',
        'conditions',
        'stats',
        'limit',
        'stock',
        'available_from',
        'available_to',
        'is_personal',
        'is_publish',
    ];

    protected $casts = [
        'accepted_payments' => 'array',
        'conditions' => 'array',
        'stats' => 'array',
    ];

    protected $appends = [
        'purchased'
    ];

    public function purchaseable()
    {
        return $this->morphTo();
    }

    public function purchase(Player $player, array $data)
    {
        $validator = Validator::make($data, [
            'quantity' => 'required|integer|min:1',
            'server' => 'nullable|array',
            'server.id' => 'nullable',
            'server.name' => 'nullable',
            'character' => 'nullable|array',
            'character.id' => 'nullable'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ((!is_null($this->available_from) && now() < $this->available_from) || !$this->is_publish) {
            throw new ProductNotAvailableYetException();
        }

        if (!is_null($this->available_to) && now() > $this->available_to) {
            throw new ProductOutOfStockException();
        }

        if (!is_null($this->limit) && ((int) $data['quantity'] + $this->boughtCount($player)) > $this->limit) {
            throw new BoughtCountLimitExceededException();
        }

        foreach (($this->conditions ?? []) as $condition) {
            $this->evaluate($player, $condition);
        }

        return DB::transaction(function () use ($player, $data) {
            $player->pay($this->price * (int) $data['quantity'], $this->payment_unit);

            $transaction = Transaction::create([
                'user_id' => $player->getId(),
                'product_id' => $this->id,
                'amount' => $this->price,
                'quantity' => (int) $data['quantity'],
                'payment_unit' => $this->payment_unit,
                'extras' => $data
            ]);

            $delivered = null;
            if ($this->purchaseable instanceof Deliverable) {
                $delivered = $this->purchaseable->deliver($player, $data);
            }

            return [
                'transaction' => $transaction,
                'delivered' => $delivered
            ];
        });
    }

    protected function evaluate(Player $player, array $condition)
    {
        if (!$this->parseCondition($player->{$condition[0]}, $condition[1], $condition[2])) {
            throw new ViolateConditionException(sprintf("Không thỏa mãn điều kiện (%s)", $condition[2]));
        }
    }

    protected function parseCondition($f, $o, $c)
    {
        switch ($o) {
            case "=":
            case "==":
                return $f == $c;
            case ">":
                return $f > $c;
            case ">=":
                return $f >= $c;
            case "<":
                return $f < $c;
            case "<=":
                return $f <= $c;
            case "!=":
                return $f != $c;
            default:
                throw new \Exception(sprintf('Error at %s %s %s', $f, $o, $c));
        }
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function boughtCount(Player $player)
    {
        return Transaction::where('user_id', $player->getId())
            ->where('product_id', $this->id)
            ->sum('quantity');
    }

    public function getPurchasedAttribute()
    {
        return Transaction::where('user_id', auth(config('vgplay.products.buyer.auth'))->id())
            ->where('product_id', $this->id)
            ->sum('quantity');
    }
}
