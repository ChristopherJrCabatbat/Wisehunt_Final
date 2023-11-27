<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LiveSearchController;
use App\Http\Controllers\MainController;

Route::get('/', [LoginController::class, 'login'])->name('loginForm');

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/loginStore', [LoginController::class, 'loginStore'])->name('loginStore');
// Route::get('/loginForm', [LoginController::class, 'loginForm'])->name('loginForm');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Staff Routes
Route::group([
    'prefix' => 'staff', 'as' => 'staff.', 
    'middleware' => ['auth', 'staff'],
    // 'middleware' => 'auth',
], function () {

    // Dashboard
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    
    // Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');

    // Product Routes
    Route::get('/product', [StaffController::class, 'product'])->name('product');
    Route::post('/productStore', [StaffController::class, 'productStore'])->name('productStore');
    // Route::post('/validateProductStore', [StaffController::class, 'validateProductStore'])->name('validateProductStore');
    Route::put('/productUpdate/{id}', [StaffController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/productDestroy/{id}', [StaffController::class, 'productDestroy'])->name('productDestroy');
    Route::get('/productSearch', [LiveSearchController::class, 'productSearch'])->name('productSearch');


    // Transaction Routes
    Route::get('/transaction', [StaffController::class, 'transaction'])->name('transaction');
    Route::post('/transactionStore', [StaffController::class, 'transactionStore'])->name('transactionStore');
    Route::put('/transactionUpdate/{id}', [StaffController::class, 'transactionUpdate'])->name('transactionUpdate');
    Route::delete('/transactionDestroy/{id}', [StaffController::class, 'transactionDestroy'])->name('transactionDestroy');
    Route::get('/transactionSearch', [LiveSearchController::class, 'transactionSearch'])->name('transactionSearch');
    Route::post('/generateReport', [StaffController::class, 'generateReport'])->name('generateReport');


    // Customer Routes
    Route::get('/customer', [StaffController::class, 'customer'])->name('customer');
    Route::post('/customerStore', [StaffController::class, 'customerStore'])->name('customerStore');
    Route::put('/customerUpdate/{id}', [StaffController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/customerDestroy/{id}', [StaffController::class, 'customerDestroy'])->name('customerDestroy');

});


// Admin Routes
Route::group([
    'prefix' => 'admin', 'as' => 'admin.', 
    'middleware' => ['auth', 'admin'],
], function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Endpoint for current month earnings
    Route::get('/get-current-earnings', [AdminController::class, 'getCurrentEarnings'])->name('getCurrentEarnings');

    // Endpoint for forecast month earnings
    Route::get('/get-forecast-earnings', [AdminController::class, 'getForecastEarnings'])->name('getForecastEarnings');


    
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

// Route::get('/logout', [LoginController::class, 'logout'])->name('logout');



