<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\adminCatCategoriesController;
use App\Http\Controllers\CustomersControllerr;

use App\Http\Controllers\Client\ProductClientController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProductVariantController as ClientProductVariantController;

// Trang mặc định → login admin
Route::get('/', function () {
    return view('admin.auth.login');
});

// Trang người dùng (client)
Route::get('/home', [ProductClientController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductClientController::class, 'show'])->name('product.show');

// Đăng nhập / đăng ký / đăng xuất dùng chung
Route::get('/login', [AccountController::class, 'showLoginForm'])->name('taikhoan.showLoginForm');
Route::post('/login', [AccountController::class, 'login'])->name('taikhoan.login');
Route::post('/register', [AccountController::class, 'register'])->name('taikhoan.register');
Route::post('/logout', [AccountController::class, 'logout'])->name('taikhoan.logout');

// Khu vực quản trị (admin)
Route::prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('rams', RamController::class);
    Route::resource('storages', StorageController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('customers', CustomersControllerr::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);
});
