<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('users', UserController::class)
        ->only(['index', 'show', 'update', 'destroy']);
    Route::post('/transfer', [TransactionController::class, 'transfer']);
    Route::resource('transactions', TransactionController::class)
        ->only(['index', 'store', 'show']);
});
