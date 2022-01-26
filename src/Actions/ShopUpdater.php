<?php

namespace Vgplay\Reward\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\Reward\Models\Shop;

class ShopUpdater
{
    public function update(Shop $shop, array $data)
    {
        $data = $this->validate($shop, $data);

        return $shop->update($data);
    }

    protected function validate(Shop $shop, array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:191',
            'game_id' => 'required|integer',
            'slug' => 'required|unique:shops,slug,' . $shop->id
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
