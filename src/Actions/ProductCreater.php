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
            'price' => 'required|integer',
            'payment_unit' => 'required|string',
            'conditions' => 'nullable|array',
            'stats' => 'nullable|array',
            'limit' => 'nullable|integer',
            'stock' => 'nullable|integer',
            'available_from' => 'nullable|date|before:available_to',
            'available_to' => 'nullable|date|after:available_from',
            'is_personal' => 'nullable',
            'is_publish' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
