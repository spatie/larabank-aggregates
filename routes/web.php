<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\TransactionsController;

Route::get('/', function () {
    return redirect('/accounts');
});

Route::middleware('auth')->group(function () {
    Route::resource('accounts', AccountsController::class);
    Route::get('transactions', [TransactionsController::class, 'index']);
});
