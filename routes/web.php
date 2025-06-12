<?php

use App\Http\Controllers\adminCatCategoriesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});
Route::resource('danhmuc',            adminCatCategoriesController::class);
