<?php

use App\Models\Food;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StandController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\DashboardController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{cart}', [CartController::class, 'update']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

});

Route::apiResource('foods', FoodController::class);
Route::apiResource('stands', StandController::class);
Route::apiResource('users', UserController::class);

Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

Route::get('/history', [HistoryController::class, 'index']);

Route::get('/orders', [OrderController::class, 'index']);
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

