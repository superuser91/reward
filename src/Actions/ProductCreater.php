<?php

namespace Vgplay\Reward\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\Reward\Models\Product;

class ProductCreater
{
    public function create(array $data)
    {
        $data = $this->validate($data);

        return Product::create($data);
    }

    protected function validate(array $data): array
    {
        $data['purchaseable_id'] = $data;
        $data['purchaseable_type'] = 1;

        $validator = Validator::make($data, [
            'shop_id' => 'required|integer|exists:shops,id',
            'name' => 'required|string|max:191',
            'picture' => 'nullable|string|max:2048',
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
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
