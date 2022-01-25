<?php

namespace Vgplay\Reward\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vgplay\Reward\Models\Shop;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
