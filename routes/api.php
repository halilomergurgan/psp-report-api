<?php

use App\Http\Controllers\API\v3\AuthController;
use App\Http\Controllers\API\v3\CustomerController;
use App\Http\Controllers\API\v3\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v3')->group(function () {
    Route::post('merchant/user/login', [AuthController::class, 'login']);

    Route::middleware('jwt')->group(function () {
        Route::get('merchant/user/me', [AuthController::class, 'me']);
        Route::get('merchant/user/logout', [AuthController::class, 'logout']);
        Route::get('transactions/report', [TransactionController::class, 'transactionReport']);
        Route::get('transactions/list', [TransactionController::class, 'transactionList']);
        Route::get('transaction', [TransactionController::class, 'transaction']);
        Route::get('client ', [CustomerController::class, 'getCustomer']);
    });
});
