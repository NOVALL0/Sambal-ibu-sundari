<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'snap_token',
        'snap_url',
        'transaction_id',
        'transaction_time',
        'transaction_status',
        'payment_type',
        'acquirer',
        'bank',
        'va_number',
        'pdf_url',
        'raw_response',
        'paid_at'
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid()
    {
        return $this->payment_status === 'success' || $this->payment_status === 'paid';
    }

    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }
}