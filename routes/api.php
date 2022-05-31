<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LogRes\LoginControllerApi;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\LogRes\RegisterControllerApi;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::post('login', LoginControllerApi::class);
    Route::post('register', RegisterControllerApi::class);

    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');
    Route::resource('permissions', PermissionController::class)->middleware('auth:sanctum');
    Route::delete('permissions_mass_destroy', [PermissionController::class, 'massDestroy'])->middleware('auth:sanctum');
    Route::resource('roles', RoleController::class)->middleware('auth:sanctum');
    Route::delete('roles_mass_destroy', [RoleController::class, 'massDestroy'])->middleware('auth:sanctum');
    Route::resource('users', UserController::class)->middleware('auth:sanctum');
    Route::delete('users_mass_destroy', [UserController::class, 'massDestroy'])->middleware('auth:sanctum');

    // categories
    Route::resource('categories', CategoryController::class)->middleware('auth:sanctum');
    // Route::post('categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');
    // Route::get('categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');
    Route::delete('categories_mass_destroy', [CategoryController::class, 'massDestroy'])->middleware('auth:sanctum');

    // products
    // Route::resource('products', ProductController::class)->middleware('auth:sanctum');
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show'])->middleware('auth:sanctum');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->middleware('auth:sanctum');
    Route::post('products', [ProductController::class, 'store'])->middleware('auth:sanctum');
    Route::put('products/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');

    Route::delete('products_mass_destroy', [ProductController::class, 'massDestroy'])->middleware('auth:sanctum');
    Route::post('products/images', [ProductController::class, 'storeImage'])->middleware('auth:sanctum');
    Route::post('products/search', [ProductController::class, 'search'])->middleware('auth:sanctum');

    // pos
    Route::get('pos', [PosController::class, 'index'])->middleware('auth:sanctum');

    // carts
    Route::resource('carts', CartController::class)->middleware('auth:sanctum');
    Route::post('carts/scan', [CartController::class, 'scan'])->middleware('auth:sanctum');

    // transaction
    Route::get('transactions', [TransactionController::class, 'index'])->middleware('auth:sanctum');
    Route::get('transactions/{transaction}/print_struck', [TransactionController::class, 'print_struck'])->middleware('auth:sanctum');
    Route::post('transactions', [TransactionController::class, 'store'])->middleware('auth:sanctum');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->middleware('auth:sanctum');
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->middleware('auth:sanctum');

    // report
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->middleware('auth:sanctum');
