<?php

namespace Vgplay\Reward;

use Illuminate\Support\ServiceProvider;

class RewardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        # code...
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
