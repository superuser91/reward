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
        $purchaseable = explode('::', $data['reward']);

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
            'is_personal' => 'exclude',
            'is_publish' => 'nullable',
            'reward' => function ($val, $attr, $fail) use ($purchaseable) {
                if (is_null($purchaseable[0]::find($purchaseable[1]))) {
                    $fail("Không tìm thấy vật phẩm.");
                }
            }
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data['purchaseable_type'] = $purchaseable[0];
        $data['purchaseable_id'] = $purchaseable[1];
        $data['is_personal'] = isset($data['is_personal']) ? 1 : 0;
        $data['is_publish'] = isset($data['is_publish']) ? 1 : 0;

        return $data;
    }
}
