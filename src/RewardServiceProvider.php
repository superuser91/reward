<?php

namespace Vgplay\Reward;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Vgplay\Reward\Console\ExportLog;
use Vgplay\Reward\Models\Product;

class RewardServiceProvider extends ServiceProvider
{
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [
            Product::class => config('vgplay.products.policy'),
        ];
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'vgplay');
    }

    public function boot()
    {
        $this->registerPolicies();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'vgplay');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->commands([
            ExportLog::class
        ]);
    }
}
