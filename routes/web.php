<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\PriceHistoryController;
use App\Http\Controllers\StorageZoneController;
use App\Http\Controllers\ItemLocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    
    Route::get('/inventory/receive', [InventoryController::class, 'showReceiveForm'])->name('inventory.receive.form');
    Route::post('/inventory/receive', [InventoryController::class, 'storeReceive'])->name('inventory.receive.store');
    
    Route::get('/inventory/transfer', [InventoryController::class, 'showTransferForm'])->name('inventory.transfer.form');
    Route::post('/inventory/transfer', [InventoryController::class, 'storeTransfer'])->name('inventory.transfer.store');
    Route::get('/movements', [InventoryMovementController::class, 'index'])->name('movements');
    Route::get('/management', [DashboardController::class, 'management'])->name('management');
    Route::view('/reports', 'reports')->name('reports');
    Route::get('/reports/price-comparison', [ReportController::class, 'priceComparison'])->name('reports.price-comparison');
    Route::Resource('users', UserController::class);
    
    Route::Resource('categories', CategoryController::class);
    Route::post('/categories/ajax-store', [CategoryController::class, 'storeAjax'])->name('categories.ajax.store');

    Route::resource('suppliers', SupplierController::class);
    Route::post('/suppliers/ajax-store', [SupplierController::class, 'storeAjax'])->name('suppliers.ajax.store');

    Route::Resource('items', ItemController::class);
    Route::post('/items/ajax-store', [ItemController::class, 'storeAjax'])->name('items.ajax.store');
    
    Route::Resource('presentations', PresentationController::class);
    Route::Resource('inventory-movements', InventoryMovementController::class);
    Route::Resource('price-histories', PriceHistoryController::class);
    Route::Resource('storage_zones', StorageZoneController::class);
    Route::Resource('item-locations', ItemLocationController::class);
});


Route::get('/csrf-token', function () {
    $token = csrf_token();
    return response()->json(['token' => $token])
        ->withHeaders([
            'X-CSRF-TOKEN' => $token,
        ])
        ->withCookie('XSRF-TOKEN', $token, 60, '/', null, true, true);
});