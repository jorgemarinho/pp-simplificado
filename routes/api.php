<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/users', UserController::class)->except('store');
    Route::post('/wallets/add-credit', [WalletController::class, 'addCredit']);
    Route::post('/wallets/transfer', [WalletController::class, 'transfer']);
});
