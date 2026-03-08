<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    // Ambil 3 produk untuk featured
    $featuredProducts = Product::where('is_active', true)
                        ->latest()
                        ->limit(3)
                        ->get();

    return view('home', compact('featuredProducts'));
})->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Static pages
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');


// ==================== GUEST ROUTES ====================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// ==================== AUTH ROUTES ====================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ========== CART ROUTES ==========
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // ========== ORDER ROUTES ==========
    Route::prefix('orders')->name('orders.')->group(function () {
        // Checkout dari cart (form checkout biasa)
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');

        // Checkout Single Product (Beli Sekarang) - TARUH SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/checkout/{product}/single', [OrderController::class, 'checkoutSingle'])->name('checkout.single');

        // Proses pembuatan pesanan
        Route::post('/', [OrderController::class, 'store'])->name('store');

        // Daftar pesanan
        Route::get('/', [OrderController::class, 'index'])->name('index');

        // Detail pesanan (route dengan parameter - taruh paling bawah)
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // ========== PAYMENT ROUTES ==========
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{order}/check', [PaymentController::class, 'checkStatus'])->name('check');
    });
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', AdminProductController::class);

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
    });

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::put('/{user}/role', [AdminUserController::class, 'updateRole'])->name('role');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy'); // Untuk delete user
    });
});

// ==================== MIDTRANS PAYMENT CALLBACKS ====================
// Routes ini harus PUBLIC karena dipanggil oleh Midtrans
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

// ==================== API ROUTES (untuk frontend) ====================
Route::prefix('api')->name('api.')->group(function () {
    // Public API
    Route::get('/products', [ProductController::class, 'apiIndex'])->name('products.index');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // Protected API
    Route::middleware('auth')->group(function () {
        // Cart API
        Route::get('/cart', [CartController::class, 'apiIndex'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::put('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Orders API
        Route::get('/orders', [OrderController::class, 'apiIndex'])->name('orders.index');
        Route::post('/orders', [OrderController::class, 'apiStore'])->name('orders.store');
        Route::post('/orders/buy-now', [OrderController::class, 'apiBuyNow'])->name('orders.buy-now');
        Route::get('/orders/{order}', [OrderController::class, 'apiShow'])->name('orders.show');
    });
});

// ==================== TEST MIDTRANS (HAPUS JIKA SUDAH PRODUCTION) ====================
Route::get('/test-midtrans', function() {
    try {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;

        $params = [
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'credit_card' => ['secure' => true]
        ];

        $token = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
});

