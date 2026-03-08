<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Tampilkan halaman pembayaran dengan Snap
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan sudah dibayar');
        }

        // Cek apakah order sudah kadaluarsa
        if ($order->created_at->diffInHours(now()) > 24) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Waktu pembayaran telah habis');
        }

        return view('payment', [
            'order' => $order,
            'snapToken' => $order->payment->snap_token,
            'clientKey' => config('midtrans.client_key')
        ]);
    }

    /**
     * Handle notifikasi dari Midtrans (webhook)
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification Received:', $request->all());

        $result = $this->midtrans->handleNotification($request->all());

        return response()->json(['success' => $result]);
    }

    /**
     * Handle finish payment (redirect setelah sukses)
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Order tidak ditemukan');
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pembayaran berhasil! Terima kasih telah berbelanja.');
    }

    /**
     * Handle unfinish payment (redirect jika pending)
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('home');
        }

        return redirect()->route('payment.show', $order)
            ->with('info', 'Menunggu pembayaran...');
    }

    /**
     * Handle error payment (redirect jika error)
     */
    public function error(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('home');
        }

        return redirect()->route('payment.show', $order)
            ->with('error', 'Pembayaran gagal, silakan coba lagi');
    }

    /**
     * Cek status pembayaran via API
     */
    public function checkStatus(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
            'order_status' => $order->status
        ]);
    }
}