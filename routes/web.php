<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'get']);

