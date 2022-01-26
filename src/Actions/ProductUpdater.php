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
            'name' => 'required|string|max:191',
            'game_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
