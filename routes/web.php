<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/loginStore', [LoginController::class, 'loginStore'])->name('loginStore');

Route::group([
    'prefix' => 'admin', 'as' => 'admin.'
    // , 'middleware' => 'Admin'
], function () {

    // Dashborad
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


    // Product Routes
    Route::get('/product', [AdminController::class, 'product'])->name('product');
    Route::post('/productStore', [AdminController::class, 'productStore'])->name('productStore');
    Route::put('/productUpdate/{id}', [AdminController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/productDestroy/{id}', [AdminController::class, 'productDestroy'])->name('productDestroy');

    // Transaction Routes
    Route::get('/transaction', [AdminController::class, 'transaction'])->name('transaction');
    Route::post('/transactionStore', [AdminController::class, 'transactionStore'])->name('transactionStore');
    Route::put('/transactionUpdate/{id}', [AdminController::class, 'transactionUpdate'])->name('transactionUpdate');
    Route::delete('/transactionDestroy/{id}', [AdminController::class, 'transactionDestroy'])->name('transactionDestroy');

    // Customer Routes
    Route::get('/customer', [AdminController::class, 'customer'])->name('customer');
    Route::post('/customerStore', [AdminController::class, 'customerStore'])->name('customerStore');
    Route::put('/customerUpdate/{id}', [AdminController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/customerDestroy/{id}', [AdminController::class, 'customerDestroy'])->name('customerDestroy');

    // Supplier Routes
    Route::get('/supplier', [AdminController::class, 'supplier'])->name('supplier');
    Route::post('/supplierStore', [AdminController::class, 'supplierStore'])->name('supplierStore');
    Route::put('/supplierUpdate/{id}', [AdminController::class, 'supplierUpdate'])->name('supplierUpdate');
    Route::delete('/supplierDestroy/{id}', [AdminController::class, 'supplierDestroy'])->name('supplierDestroy');

});

Route::get('/logout', function () {
    Session::forget('account');
    Session::forget('name');
    return redirect()->route('login');
})->name('logout');
