<?php

use Illuminate\Support\Facades\Route;
use Vgplay\Reward\Controllers\RewardController;

Route::middleware('web')->group(function () {
    Route::group([
        'prefix' => config('vgplay.news.prefix'),
        'middleware' => config('vgplay.news.middleware')
    ], function () {
        Route::resource('rewards', RewardController::class)->except('show');
    });
});
