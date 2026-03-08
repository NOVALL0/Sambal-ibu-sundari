<?php
// database/migrations/xxxx_update_orders_table_for_midtrans.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->unique()->nullable()->after('id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('total_price');
            $table->decimal('grand_total', 10, 2)->after('shipping_cost');
            $table->string('shipping_name')->nullable()->after('shipping_method');
            $table->string('shipping_phone')->nullable()->after('shipping_name');
            $table->text('shipping_address')->nullable()->after('shipping_phone');
            $table->text('notes')->nullable()->after('shipping_address');
            $table->string('payment_status')->default('pending')->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->timestamp('cancelled_at')->nullable()->after('paid_at');
            $table->text('cancelled_reason')->nullable()->after('cancelled_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('payment_status');
            $table->string('payment_url')->nullable()->after('payment_token');
            $table->string('transaction_id')->nullable()->after('payment_url');
            $table->timestamp('transaction_time')->nullable()->after('transaction_id');
            $table->string('transaction_status')->nullable()->after('transaction_time');
            $table->string('payment_type')->nullable()->after('transaction_status');
            $table->string('bank')->nullable()->after('payment_type');
            $table->string('va_number')->nullable()->after('bank');
            $table->string('bill_key')->nullable()->after('va_number');
            $table->string('biller_code')->nullable()->after('bill_key');
            $table->string('pdf_url')->nullable()->after('biller_code');
            $table->timestamp('finish_time')->nullable()->after('pdf_url');
            $table->json('raw_response')->nullable()->after('finish_time');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'shipping_cost',
                'grand_total',
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'notes',
                'payment_status',
                'paid_at',
                'cancelled_at',
                'cancelled_reason'
            ]);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_token',
                'payment_url',
                'transaction_id',
                'transaction_time',
                'transaction_status',
                'payment_type',
                'bank',
                'va_number',
                'bill_key',
                'biller_code',
                'pdf_url',
                'finish_time',
                'raw_response'
            ]);
        });
    }
};