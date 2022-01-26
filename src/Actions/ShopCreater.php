<?php

namespace Vgplay\Reward\Actions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\Reward\Models\Shop;

class ShopCreater
{
    public function create(array $data)
    {
        $data = $this->validate($data);

        return Shop::create($data);
    }

    protected function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:191',
            'game_id' => 'required|integer',
            'slug' => 'required|unique:shops,slug'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $data;
    }
}
