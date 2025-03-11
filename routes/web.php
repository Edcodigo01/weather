<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::middleware([\App\Http\Middleware\MeasureResponseTime::class])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'get']);
    // BORRAR ---------------
    Route::get('/test', [UserController::class, 'store']);
// });
