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
    Route::view('/inventory', 'inventory')->name('inventory');
    Route::view('/movements', 'movements')->name('movements');
    Route::view('/storage', 'storage')->name('storage');
    Route::view('/management', 'management')->name('management');
    Route::view('/reports', 'reports')->name('reports');

    Route::Resource('users', UserController::class);
    Route::Resource('categories', CategoryController::class);
    Route::Resource('suppliers', SupplierController::class);
    Route::Resource('items', ItemController::class);
    Route::Resource('presentations', PresentationController::class);
    Route::Resource('inventory-movements', InventoryMovementController::class);
    Route::Resource('price-histories', PriceHistoryController::class);
    Route::Resource('storage-zones', StorageZoneController::class);
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