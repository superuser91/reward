<?php

namespace Vgplay\Reward\Actions;

use Vgplay\Reward\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductUpdater
{
    public function update(Product $product, array $data)
    {
        $data = $this->validate($product, $data);

        return $product->update($data);
    }

    protected function validate(Product $product, array $data): array
    {
        $validator = Validator::make($data, [
            'shop_id' => 'exclude',
            'name' => 'required|string|max:191',
            'picture' => 'nullable|string|max:2048',
            'price' => 'required|integer',
            'payment_unit' => 'required|in:' . implode(',', array_keys(config('vgplay.products.payment_units', []))),
            'conditions' => 'nullable|array',
            'stats' => 'exclude',
            'limit' => 'nullable|integer',
            'stock' => 'nullable|integer',
            'available_from' => 'nullable|date|before:available_to',
            'available_to' => 'nullable|date|after:available_from',
            'is_personal' => 'nullable',
            'is_publish' => 'nullable',
            'purchaseable_type' => 'exclude',
            'purchaseable_id' => 'exclude',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data['is_personal'] = isset($data['is_personal']) ? true : false;
        $data['is_publish'] = isset($data['is_publish']) ? true : false;

        return $data;
    }
}
