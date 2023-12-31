<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TransactionController;
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

Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::delete('logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('currencies', [CurrencyController::class, 'listCurrencies'])->name('currencies.index');
    Route::get('currencies/{currency}', [CurrencyController::class, 'getCurrency'])->name('currencies.show');

    Route::get('transactions/summary', [TransactionController::class, 'getSummaryTransactions'])->name('transactions.getSummaryTransactions');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
});
