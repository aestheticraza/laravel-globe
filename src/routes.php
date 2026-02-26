<?php

use Illuminate\Support\Facades\Route;
use Yourname\LaravelGlobe\Http\Controllers\Api\CountryController;

Route::prefix(config('laravelglobe.routes_prefix', 'api/globe'))
    ->middleware(config('laravelglobe.routes_middleware', ['api']))
    ->group(function () {
        Route::get('/countries', [CountryController::class, 'index']);
        Route::get('/countries/{iso2}', [CountryController::class, 'show']);
        Route::get('/countries/{iso2}/states', [CountryController::class, 'states']);
    });
