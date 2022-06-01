<?php

use Illuminate\Support\Facades\Route;
use Vgplay\Reward\Controllers\RewardController;

Route::middleware('web')->group(function () {
    Route::group([
        'prefix' => config('vgplay.products.prefix'),
        'middleware' => config('vgplay.products.middleware')
    ], function () {
        Route::resource('rewards', RewardController::class)->except('show');
    });
});
