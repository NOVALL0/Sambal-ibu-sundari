<?php
// routes/api.php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/products', [ProductController::class, 'apiIndex'])->name('api.products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show');

// Cart count (public - untuk guest, return 0)
Route::get('/cart/count', [CartController::class, 'count'])->name('api.cart.count');

// ==================== PROTECTED ROUTES ====================
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ========== CART ROUTES ==========
    Route::prefix('cart')->name('api.cart.')->group(function () {
        Route::get('/', [CartController::class, 'apiIndex'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/item/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/item/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
    });

    // ========== ORDER ROUTES ==========
    Route::prefix('orders')->name('api.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'apiIndex'])->name('index');
        Route::post('/', [OrderController::class, 'apiStore'])->name('store');
        Route::get('/{order}', [OrderController::class, 'apiShow'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'apiCancel'])->name('cancel');
    });

    // ========== PAYMENT ROUTES ==========
    Route::prefix('payment')->name('api.payment.')->group(function () {
        Route::post('/{order}/process', [PaymentController::class, 'apiProcess'])->name('process');
        Route::get('/{order}/status', [PaymentController::class, 'checkStatus'])->name('status');
    });
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('api.admin.')->group(function () {
    // Products
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Users
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// ==================== MIDTRANS CALLBACK ====================
Route::post('/payment/callback', [PaymentController::class, 'notification'])->name('api.payment.callback');


Route::middleware('auth:sanctum')->post('/orders/buy-now', [OrderController::class, 'apiBuyNow'])->name('api.orders.buy-now');