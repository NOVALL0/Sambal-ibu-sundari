<?php
// app/Services/MidtransService.php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class MidtransService
{
    public function __construct()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            throw new \Exception('Server Key atau Client Key tidak ditemukan. Cek file .env');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds', true);

        // Nonaktifkan SSL untuk development
        if (App::environment('local', 'development')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 30
            ];
        }

        Log::info('Midtrans Config Loaded', [
            'environment' => Config::$isProduction ? 'PRODUCTION' : 'SANDBOX',
            'server_key_exists' => !empty(Config::$serverKey),
            'client_key_exists' => !empty(Config::$clientKey)
        ]);
    }

    /**
     * Buat Snap Token untuk Order - FORMAT SESUAI PERMINTAAN
     */
    public function createSnapToken(Order $order)
    {
        try {
            // PARAMETER DASAR - FORMAT YANG ANDA INGINKAN
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'credit_card' => [
                    'secure' => true
                ]
            ];

            // TAMBAHKAN CUSTOMER DETAILS (OPSIONAL)
            if ($order->shipping_name) {
                $params['customer_details'] = [
                    'first_name' => $order->shipping_name,
                    'email' => $order->user->email ?? '',
                    'phone' => $order->shipping_phone ?? '',
                ];
            }

            if ($order->items && $order->items->count() > 0) {
                $params['item_details'] = $this->getItemDetails($order);
            }

            // TAMBAHKAN EXPIRY (OPSIONAL)
            $params['expiry'] = [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ];

            // TAMBAHKAN CALLBACKS (OPSIONAL)
            $params['callbacks'] = [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ];

            Log::info('Midtrans Request (Simple Format):', $params);

            // Dapatkan Snap Token
            $snap = Snap::createTransaction($params);

            Log::info('Midtrans Response:', (array) $snap);

            return [
                'success' => true,
                'token' => $snap->token,
                'redirect_url' => $snap->redirect_url ?? null
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            // FALLBACK: COBA DENGAN PARAMETER MINIMAL
            return $this->createSnapTokenMinimal($order);
        }
    }

    /**
     * Fallback: Snap Token dengan parameter minimal
     */
    private function createSnapTokenMinimal(Order $order)
    {
        try {
           
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->grand_total,
                ]
            ];

            Log::info('Midtrans Minimal Request:', $params);

            $snap = Snap::createTransaction($params);

            return [
                'success' => true,
                'token' => $snap->token,
                'redirect_url' => $snap->redirect_url ?? null
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Minimal Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal membuat Snap token: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test method untuk mencoba format sederhana
     */
    public function testSimpleSnapToken()
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . time(),
                    'gross_amount' => 10000,
                ],
                'credit_card' => [
                    'secure' => true
                ]
            ];

            $snap = Snap::createTransaction($params);

            return [
                'success' => true,
                'token' => $snap->token,
                'params' => $params
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Dapatkan detail item untuk Midtrans
     */
    private function getItemDetails(Order $order)
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product->name, 0, 50),
            ];
        }

        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        return $items;
    }

    /**
     * Handle notifikasi dari Midtrans
     */
    public function handleNotification($notifData)
    {
        try {
            $notif = new Notification($notifData);
            $order = Order::where('order_number', $notif->order_id)->first();

            if (!$order) {
                Log::error('Order not found: ' . $notif->order_id);
                return false;
            }

            $payment = $order->payment;
            $payment->transaction_id = $notif->transaction_id;
            $payment->transaction_time = $notif->transaction_time;
            $payment->transaction_status = $notif->transaction_status;
            $payment->payment_type = $notif->payment_type;
            $payment->raw_response = json_encode($notifData);

            // Handle VA numbers
            if (isset($notif->va_numbers[0])) {
                $payment->bank = $notif->va_numbers[0]->bank;
                $payment->va_number = $notif->va_numbers[0]->va_number;
            }

            // Handle PDF URL
            if (isset($notif->pdf_url)) {
                $payment->pdf_url = $notif->pdf_url;
            }

            // Update status berdasarkan transaction_status
            switch ($notif->transaction_status) {
                case 'settlement':
                case 'capture':
                    $payment->payment_status = 'success';
                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                    $order->paid_at = now();
                    break;

                case 'pending':
                    $payment->payment_status = 'pending';
                    $order->payment_status = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $payment->payment_status = 'failed';
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    $order->cancelled_at = now();

                    // Restore stock
                    foreach ($order->items as $item) {
                        $item->product->increment('stock', $item->quantity);
                    }
                    break;
            }

            $payment->save();
            $order->save();

            Log::info('Order updated successfully', [
                'order_id' => $order->id,
                'payment_status' => $payment->payment_status,
                'order_status' => $order->status
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return false;
        }
    }
}