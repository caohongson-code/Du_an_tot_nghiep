<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;

use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\StorageController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProductVariantController as ClientProductVariantController;

Route::get('/', function () {
     return view('admin.dashboard');
});
Route::get('/products', [ClientProductController::class, 'index'])
    ->name('client.products.index');

// Trang chi tiết sản phẩm theo slug
Route::get('/products/{slug}', [ClientProductController::class, 'show'])
    ->name('client.products.show');

// API lấy thông tin biến thể sản phẩm theo lựa chọn (AJAX)
Route::get('/variant', [ClientProductVariantController::class, 'getVariant'])
    ->name('client.variant.get');

//admin 
Route::prefix('admin')->group(function () {

    Route::resource('promotions', PromotionController::class);


    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('variants', ProductVariantController::class);

    Route::resource('rams', RamController::class);
    Route::resource('storages', StorageController::class);
    Route::resource('colors', ColorController::class);

});
