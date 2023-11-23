<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LiveSearchController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/loginStore', [LoginController::class, 'loginStore'])->name('loginStore');
// Route::get('/loginForm', [LoginController::class, 'loginForm'])->name('loginForm');

Route::group([
    'prefix' => 'admin', 'as' => 'admin.', 
    // 'middleware' => 'admin'
], function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


    // Product Routes
    Route::get('/product', [AdminController::class, 'product'])->name('product');
    Route::post('/productStore', [AdminController::class, 'productStore'])->name('productStore');
    // Route::post('/validateProductStore', [AdminController::class, 'validateProductStore'])->name('validateProductStore');
    Route::put('/productUpdate/{id}', [AdminController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/productDestroy/{id}', [AdminController::class, 'productDestroy'])->name('productDestroy');
    Route::get('/productSearch', [LiveSearchController::class, 'productSearch'])->name('productSearch');


    // Transaction Routes
    Route::get('/transaction', [AdminController::class, 'transaction'])->name('transaction');
    Route::post('/transactionStore', [AdminController::class, 'transactionStore'])->name('transactionStore');
    Route::put('/transactionUpdate/{id}', [AdminController::class, 'transactionUpdate'])->name('transactionUpdate');
    Route::delete('/transactionDestroy/{id}', [AdminController::class, 'transactionDestroy'])->name('transactionDestroy');
    Route::get('/transactionSearch', [LiveSearchController::class, 'transactionSearch'])->name('transactionSearch');
    Route::post('/generateReport', [AdminController::class, 'generateReport'])->name('generateReport');


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

// Route::get('/logout', function () {
//     Session::forget('account');
//     Session::forget('name');
//     return redirect()->route('login');
// })->name('logout');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
