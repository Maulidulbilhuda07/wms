<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductController::class, 'index']);
Route::post('/product-save', [ProductController::class, 'store']);
Route::put('/product-update/{id}', [ProductController::class, 'edit_save']);
Route::get('/products/data', [ProductController::class, 'getDataProduct'])->name('products.data');
Route::delete('/products-delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');