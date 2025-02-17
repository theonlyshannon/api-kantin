<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StandController;


Route::apiResource('foods', FoodController::class);
Route::apiResource('stands', StandController::class);
Route::apiResource('users', UserController::class);
