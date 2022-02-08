<?php

namespace Vgplay\Reward;

use Illuminate\Support\ServiceProvider;
use Vgplay\Reward\Console\ExportLog;

class RewardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->commands([
            ExportLog::class
        ]);
    }
}
