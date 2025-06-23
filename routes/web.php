<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
     return view('admin.dashboard');
});

Route::prefix('admin')->group(function () {

    Route::resource('promotions', PromotionController::class);


    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('variants', ProductVariantController::class);

    Route::resource('rams', RamController::class);
    Route::resource('storages', StorageController::class);
    Route::resource('colors', ColorController::class);

    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);

    Route::resource('carts', CartController::class);
    Route::resource('cart-details', CartDetailController::class);
    Route::resource('carts', CartController::class)->only(['index', 'show', 'destroy']);
    Route::delete('/admin/cart-details/{id}', [CartDetailController::class, 'destroy'])->name('cart-details.destroy');


});