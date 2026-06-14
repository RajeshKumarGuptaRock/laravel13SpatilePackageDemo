<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Sales\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('articles.index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('articles', ArticleController::class);

// Inventory (new module - routes currently scaffolded)
Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', function () {
        return view('inventory.index');
    })->name('index');

    Route::resource('products', \App\Http\Controllers\Inventory\ProductController::class)->names('products');
    Route::resource('categories', \App\Http\Controllers\Inventory\CategoryController::class)->names('categories');
    Route::resource('suppliers', \App\Http\Controllers\Inventory\SupplierController::class)->names('suppliers');
    Route::resource('warehouses', \App\Http\Controllers\Inventory\WarehouseController::class)->names('warehouses');
    Route::resource('stock', \App\Http\Controllers\Inventory\StockMovementController::class)->names('stock');
});

Route::resource('sales', SaleController::class)->names('sales');;
