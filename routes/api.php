<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('register', 'register');
});

Route::prefix('admin')->middleware(['auth:api', 'is_admin'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('/deletedProducts', [ProductController::class, 'deletedProducts']);
    Route::post('/products/{product}/restore', [ProductController::class, 'restoreProduct']);
    Route::delete('/products/{product}/finalDelete', [ProductController::class, 'forceDeleteProduct']);

    Route::apiResource('categories', CategoryController::class);
    Route::get('/deletedCategory', [CategoryController::class, 'deletedCategory']);
    Route::post('/categories/{category}/restore', [CategoryController::class, 'restoreCategory']);
    Route::delete('/categories/{category}/finalDelete', [CategoryController::class, 'forceDeleteCategory']);

    Route::apiResource('users', UserController::class);
    Route::get('/deletedUsers', [UserController::class, 'allDeletedUsers']);
    Route::post('/users/{user}/restore', [UserController::class, 'restoreUser']);
    Route::delete('/users/{user}/finalDelete', [UserController::class, 'forceDeleteUser']);

    Route::apiResource('transfers', TransferController::class)->except(['update', 'destroy']);
    Route::apiResource('orders', OrderController::class)->except(['store', 'update', 'destroy']);
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept']);
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject']);

    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
});

Route::prefix('pointRelitier')->middleware(['auth:api', 'is_point_relitier'])->group(function () {
    Route::apiResource('transfers', TransferController::class)->except(['update', 'destroy']);
});

Route::prefix('customer')->middleware(['auth:api', 'is_customer'])->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('carts', CartController::class)->except('show');
    Route::apiResource('orders', OrderController::class)->except(['store', 'update']);
    Route::post('/confirmOrder', [OrderController::class, 'confirm']);
    Route::post('/orders/{order}/pays', [OrderController::class, 'pays']);

    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
});