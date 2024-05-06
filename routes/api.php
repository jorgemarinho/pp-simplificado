<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '512M');

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});

Route::apiResource('/users', UserController::class);
Route::post('/wallets/add-credit', [WalletController::class, 'addCredit']);
Route::post('/wallets/transfer', [WalletController::class, 'transfer']);
