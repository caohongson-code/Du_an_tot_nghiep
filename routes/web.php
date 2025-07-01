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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    // Resources
    Route::resource('promotions', PromotionController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::resource('rams', RamController::class);
    Route::resource('storages', StorageController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('carts', CartController::class)->only(['index', 'show', 'destroy']);
    Route::resource('cart-details', CartDetailController::class)->only(['destroy']);

    // Orders - Admin routes
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update'])->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
    ]);

    // Orders - Customer routes
    Route::middleware(['auth'])->group(function () {
        Route::post('orders/cart/{cartId}', [OrderController::class, 'placeOrderFromCart'])
            ->name('orders.place')
            ->middleware('can:view,cart');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrder'])
            ->name('orders.cancel')
            ->middleware('can:cancel,order');
    });

    // Payment routes
    Route::get('payment/vnpay/{order}', [PaymentController::class, 'redirectToVNPay'])->name('payment.vnpay');
    Route::get('payment/return', [PaymentController::class, 'handleVNPayReturn'])->name('payment.return');
});
