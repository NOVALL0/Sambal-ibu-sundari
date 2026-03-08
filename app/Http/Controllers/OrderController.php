<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Tampilkan halaman checkout (dari cart)
     */
    public function checkout()
    {
        $cart = Auth::user()->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $shippingCost = 10000; // Ongkos kirim flat
        $total = $subtotal + $shippingCost;

        return view('checkout', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    /**
     * Proses pembuatan pesanan dari cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|in:shipping,pickup',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $cart = Auth::user()->cart()->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('Keranjang belanja kosong');
            }

            // Hitung total
            $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
            $shippingCost = ($request->shipping_method === 'shipping') ? 10000 : 0;
            $grandTotal = $subtotal + $shippingCost;

            // Cek stok semua item
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok {$item->product->name} tidak mencukupi");
                }
            }


            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'INV/' . date('Ymd') . '/' . str_pad(Order::count() + 1, 5, '0', STR_PAD_LEFT),
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'shipping_method' => $request->shipping_method,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_date' => now()
            ]);

            // Buat order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }


            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_status' => 'pending'
            ]);

            // Buat Snap Token dari Midtrans
            $midtransResult = $this->midtrans->createSnapToken($order);

            if (!$midtransResult['success']) {
                throw new \Exception($midtransResult['message']);
            }

            // Simpan snap token ke payment
            $payment->snap_token = $midtransResult['token'];
            $payment->snap_url = $midtransResult['redirect_url'] ?? null;
            $payment->save();

            // Hapus cart
            $cart->items()->delete();

            DB::commit();

            // Redirect ke halaman payment
            return redirect()->route('payment.show', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * API Store untuk frontend (dari cart)
     */
    public function apiStore(Request $request)
    {
        try {
            $request->validate([
                'shipping_method' => 'required|in:shipping,pickup',
                'shipping_name' => 'required|string|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'notes' => 'nullable|string|max:500'
            ]);

            $cart = Auth::user()->cart()->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja kosong'
                ], 400);
            }

            DB::beginTransaction();

            $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
            $shippingCost = ($request->shipping_method === 'shipping') ? 10000 : 0;
            $grandTotal = $subtotal + $shippingCost;

            // Cek stok
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok {$item->product->name} tidak mencukupi");
                }
            }

            // Buat order - TANPA payment_method
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'INV/' . date('Ymd') . '/' . str_pad(Order::count() + 1, 5, '0', STR_PAD_LEFT),
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'shipping_method' => $request->shipping_method,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_date' => now()
            ]);

            // Buat order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_status' => 'pending'
            ]);

            // Buat Snap Token
            $midtransResult = $this->midtrans->createSnapToken($order);

            if (!$midtransResult['success']) {
                throw new \Exception($midtransResult['message']);
            }

            $payment->snap_token = $midtransResult['token'];
            $payment->snap_url = $midtransResult['redirect_url'] ?? null;
            $payment->save();

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order' => $order,
                    'payment' => $payment,
                    'snap_token' => $payment->snap_token,
                    'redirect_url' => route('payment.show', $order)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

   
    public function apiBuyNow(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'shipping_method' => 'required|in:shipping,pickup',
                'shipping_name' => 'required|string|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_email' => 'nullable|email',
                'shipping_address' => 'required|string',
                'notes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);

            // Cek stok
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$product->name} tidak mencukupi"
                ], 400);
            }

            // Hitung total
            $subtotal = $product->price * $request->quantity;
            $shippingCost = ($request->shipping_method === 'shipping') ? 10000 : 0;
            $grandTotal = $subtotal + $shippingCost;

            // Generate order number
            $orderNumber = 'INV/' . date('Ymd') . '/' . str_pad(Order::count() + 1, 5, '0', STR_PAD_LEFT);

            // Buat order - TANPA payment_method
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'shipping_method' => $request->shipping_method,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_date' => now()
            ]);

            // Buat order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);

            // Kurangi stok
            $product->decrement('stock', $request->quantity);

            // Buat payment record - TANPA payment_method
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_status' => 'pending'
            ]);

            // Buat Snap Token Midtrans
            try {
                $midtransResult = $this->midtrans->createSnapToken($order);

                if ($midtransResult['success']) {
                    $payment->snap_token = $midtransResult['token'];
                    $payment->snap_url = $midtransResult['redirect_url'] ?? null;
                    $payment->save();
                } else {
                    \Log::error('Midtrans token creation failed: ' . ($midtransResult['message'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                \Log::error('Midtrans error: ' . $e->getMessage());
                // Tetap lanjutkan proses
            }

            DB::commit();

            // Redirect ke halaman payment
            $redirectUrl = route('payment.show', $order);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order' => $order,
                    'payment' => $payment,
                    'snap_token' => $payment->snap_token,
                    'redirect_url' => $redirectUrl
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('API BuyNow Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Daftar pesanan user
     */
    public function index()
    {
        $orders = Auth::user()->orders()
                    ->with('items.product', 'payment')
                    ->latest()
                    ->paginate(10);

        return view('orders', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load('items.product', 'payment');

        return view('order-detail', compact('order'));
    }

    /**
     * Checkout single product (halaman)
     */
    public function checkoutSingle($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            return redirect()->route('products')
                ->with('error', 'Produk tidak tersedia');
        }

        if ($product->stock < 1) {
            return redirect()->route('products')
                ->with('error', 'Stok produk habis');
        }

        return view('checkout-single', compact('product'));
    }

    /**
     * Batalkan pesanan
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        DB::transaction(function () use ($order, $request) {
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_reason' => $request->reason ?? 'Dibatalkan oleh customer'
            ]);

            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan');
    }
}