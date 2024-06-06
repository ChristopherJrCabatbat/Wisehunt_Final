<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LiveSearchController;
use App\Http\Controllers\StaffLiveSearchController;
use App\Http\Controllers\MainController;

// Route::get('/', [LoginController::class, 'login'])->name('loginForm');
Route::get('/', [LoginController::class, 'login'])->name('login');

Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Admin Routes
Route::group([
    'prefix' => 'admin', 'as' => 'admin.', 
    'middleware' => ['auth', 'admin'],
], function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/getTransactions', [AdminController::class, 'getTransactions'])->name('getTransactions');

    // Endpoint for current month sales
    Route::get('/get-current-sales', [AdminController::class, 'getCurrentSales'])->name('getCurrentSales');

    // Endpoint for forecast month sales
    Route::get('/get-forecast-sales', [AdminController::class, 'getForecastSales'])->name('getForecastSales');



    // Product Routes
    Route::get('/product', [AdminController::class, 'product'])->name('product');
    Route::get('/products/filter/{category}', [AdminController::class, 'filterProductsByCategory'])->name('filterProductsByCategory');
    Route::post('/productStore', [AdminController::class, 'productStore'])->name('productStore');

    Route::get('/searchProductName', [AdminController::class, 'searchProductName'])->name('searchProductName');
    Route::get('/searchSupplierProduct', [AdminController::class, 'searchSupplierProduct'])->name('searchSupplierProduct');

    Route::get('/productEdit/{id}', [AdminController::class, 'productEdit'])->name('productEdit');
    Route::put('/productUpdate/{id}', [AdminController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/productDestroy/{id}', [AdminController::class, 'productDestroy'])->name('productDestroy');
    Route::get('/productSearch', [LiveSearchController::class, 'productSearch'])->name('productSearch');
    

    // Transaction Routes
    Route::get('/transaction', [AdminController::class, 'transaction'])->name('transaction');
    Route::post('/transactionStore', [AdminController::class, 'transactionStore'])->name('transactionStore');
    Route::get('/searchProduct', [AdminController::class, 'searchProduct'])->name('searchProduct');
    Route::get('/transactionEdit/{id}', [AdminController::class, 'transactionEdit'])->name('transactionEdit');
    Route::put('/transactionUpdate/{id}', [AdminController::class, 'transactionUpdate'])->name('transactionUpdate');
    Route::delete('/transactionDestroy/{id}', [AdminController::class, 'transactionDestroy'])->name('transactionDestroy');
    Route::get('/transactionSearch', [LiveSearchController::class, 'transactionSearch'])->name('transactionSearch');
    Route::post('/generateReport', [AdminController::class, 'generateReport'])->name('generateReport');


    // Customer Routes
    Route::get('/customer', [AdminController::class, 'customer'])->name('customer');
    Route::post('/customerStore', [AdminController::class, 'customerStore'])->name('customerStore');
    Route::get('/customerEdit/{id}', [AdminController::class, 'customerEdit'])->name('customerEdit');
    Route::put('/customerUpdate/{id}', [AdminController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/customerDestroy/{id}', [AdminController::class, 'customerDestroy'])->name('customerDestroy');


    // Supplier Routes
    Route::get('/supplier', [AdminController::class, 'supplier'])->name('supplier');
    Route::post('/supplierStore', [AdminController::class, 'supplierStore'])->name('supplierStore');
    Route::post('/supplierStoreQty', [AdminController::class, 'supplierStoreQty'])->name('supplierStoreQty');
    Route::get('/supplierEdit/{id}', [AdminController::class, 'supplierEdit'])->name('supplierEdit');
    Route::put('/supplierUpdate/{id}', [AdminController::class, 'supplierUpdate'])->name('supplierUpdate');
    Route::delete('/supplierDestroy/{id}', [AdminController::class, 'supplierDestroy'])->name('supplierDestroy');
    Route::get('/supplierSearch', [LiveSearchController::class, 'supplierSearch'])->name('supplierSearch');

    // Delivery Routes
    Route::get('/delivery', [AdminController::class, 'delivery'])->name('delivery');
    Route::get('/delivery/details/{id}', [AdminController::class, 'getDeliveryDetails'])->name('getDeliveryDetails');
    Route::post('/deliveryStore', [AdminController::class, 'deliveryStore'])->name('deliveryStore');
    Route::delete('/deliveryDestroy/{id}', [AdminController::class, 'deliveryDestroy'])->name('deliveryDestroy');
    Route::post('/deliveryUpdate', [AdminController::class, 'deliveryUpdate'])->name('deliveryUpdate');

    // Route::post('admin/update-delivery-status', 'DeliveryController@updateStatus')->name('admin.updateDeliveryStatus');

    
    // User Routes
    Route::get('/return', [AdminController::class, 'return'])->name('return');
    

    // User Routes
    Route::get('/user', [AdminController::class, 'user'])->name('user');
    // Route::get('/user/{id}', [AdminController::class, 'user'])->name('user');

    Route::post('/userStore', [AdminController::class, 'userStore'])->name('userStore');
    Route::get('/userEdit/{id}', [AdminController::class, 'userEdit'])->name('userEdit');
    Route::put('/userUpdate/{id}', [AdminController::class, 'userUpdate'])->name('userUpdate');
    Route::delete('/userDestroy/{id}', [AdminController::class, 'userDestroy'])->name('userDestroy');
});



// Staff Routes
Route::group([
    'prefix' => 'staff', 'as' => 'staff.', 
    'middleware' => ['auth', 'staff'],
    // 'middleware' => 'auth',
], function () {

    // Dashboard
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/userEdit/{id}', [StaffController::class, 'userEdit'])->name('userEdit');
    Route::put('/userUpdate/{id}', [StaffController::class, 'userUpdate'])->name('userUpdate');

    

    // Product Routes
    Route::get('/product', [StaffController::class, 'product'])->name('product');
    Route::post('/productStore', [StaffController::class, 'productStore'])->name('productStore');
    Route::get('/productEdit/{id}', [StaffController::class, 'productEdit'])->name('productEdit');
    Route::put('/productUpdate/{id}', [StaffController::class, 'productUpdate'])->name('productUpdate');
    Route::delete('/productDestroy/{id}', [StaffController::class, 'productDestroy'])->name('productDestroy');
    Route::get('/productSearch', [StaffLiveSearchController::class, 'productSearch'])->name('productSearch');


    // Transaction Routes
    Route::get('/transaction', [StaffController::class, 'transaction'])->name('transaction');
    Route::post('/transactionStore', [StaffController::class, 'transactionStore'])->name('transactionStore');
    Route::get('/searchProduct', [StaffController::class, 'searchProduct'])->name('searchProduct');
    Route::get('/transactionEdit/{id}', [StaffController::class, 'transactionEdit'])->name('transactionEdit');
    Route::put('/transactionUpdate/{id}', [StaffController::class, 'transactionUpdate'])->name('transactionUpdate');
    Route::delete('/transactionDestroy/{id}', [StaffController::class, 'transactionDestroy'])->name('transactionDestroy');
    Route::post('/generateReport', [StaffController::class, 'generateReport'])->name('generateReport');
    Route::get('/transactionSearch', [StaffLiveSearchController::class, 'transactionSearch'])->name('transactionSearch');


    // Customer Routes
    Route::get('/customer', [StaffController::class, 'customer'])->name('customer');
    Route::post('/customerStore', [StaffController::class, 'customerStore'])->name('customerStore');
    Route::get('/customerEdit/{id}', [StaffController::class, 'customerEdit'])->name('customerEdit');
    Route::put('/customerUpdate/{id}', [StaffController::class, 'customerUpdate'])->name('customerUpdate');
    Route::delete('/customerDestroy/{id}', [StaffController::class, 'customerDestroy'])->name('customerDestroy');
    Route::get('/customerSearch', [StaffLiveSearchController::class, 'customerSearch'])->name('customerSearch');


    // Delivery Routes
    Route::get('/delivery', [StaffController::class, 'delivery'])->name('delivery');
    Route::get('/delivery/details/{id}', [StaffController::class, 'getDeliveryDetails'])->name('getDeliveryDetails');
    Route::post('/deliveryStore', [StaffController::class, 'deliveryStore'])->name('deliveryStore');
    Route::delete('/deliveryDestroy/{id}', [StaffController::class, 'deliveryDestroy'])->name('deliveryDestroy');
    Route::post('/deliveryUpdate', [StaffController::class, 'deliveryUpdate'])->name('deliveryUpdate');

});



// Route::get('/logout', function () {
//     Session::forget('account');
//     Session::forget('name');
//     return redirect()->route('login');
// })->name('logout');

// Route::get('/logout', [LoginController::class, 'logout'])->name('logout');