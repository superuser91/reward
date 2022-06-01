<?php

namespace Vgplay\Reward\Models;

use Vgplay\Reward\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Vgplay\Contracts\Deliverable;
use Vgplay\Contracts\Player;
use Vgplay\Contracts\Product as ProductContract;
use Vgplay\Reward\Exceptions\BoughtCountLimitExceededException;
use Vgplay\Reward\Exceptions\ProductNotAvailableYetException;
use Vgplay\Reward\Exceptions\ProductOutOfStockException;
use Vgplay\Reward\Exceptions\ViolateConditionException;

class Product extends Model implements ProductContract
{
    use HasFactory;
    use SoftDeletes;

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

    public function purchaseable()
    {
        return $this->morphTo();
    }

    public function purchase(Player $player, array $data)
    {
        if (!is_null($this->available_from) && now() < $this->available_from) {
            throw new ProductNotAvailableYetException();
        }

        if (!is_null($this->available_to) && now() > $this->available_to) {
            throw new ProductOutOfStockException();
        }

        if (!is_null($this->limit) && $this->boughtCount($player) >= $this->limit) {
            throw new BoughtCountLimitExceededException();
        }

        foreach (($this->conditions ?? []) as $condition) {
            $this->evaluate($player, $condition);
        }

        return DB::transaction(function () use ($player, $data) {
            $player->payForReward($this);

            $transaction = Transaction::create([
                'user_id' => $player->getId(),
                'product_id' => $this->id,
                'amount' => $this->price,
                'payment_unit' => $this->payment_unit
            ]);

            if ($this->purchaseable instanceof Deliverable) {
                $this->purchaseable->deliver($player, $data);
            }

            return $transaction;
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
            ->count();
    }
}
