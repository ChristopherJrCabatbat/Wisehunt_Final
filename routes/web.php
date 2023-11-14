<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/loginStore', [LoginController::class, 'loginStore'])->name('loginStore');

Route::group(['prefix' => 'admin', 'as' => 'admin.'
    // , 'middleware' => 'Admin'
], function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/product', [AdminController::class, 'product'])->name('product');

    Route::get('/transaction', [AdminController::class, 'transaction'])->name('transaction');

    Route::get('/customer', [AdminController::class, 'customer'])->name('customer');

    Route::get('/supplier', [AdminController::class, 'supplier'])->name('supplier');

});
