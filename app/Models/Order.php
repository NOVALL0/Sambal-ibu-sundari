<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'shipping_cost',
        'grand_total',
        'shipping_method',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'payment_method',
        'notes',
        'status',
        'payment_status',
        'order_date',
        'paid_at',
        'cancelled_at',
        'cancelled_reason'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'order_date' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function generateOrderNumber()
    {
        $this->order_number = 'INV/' . date('Ymd') . '/' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
        $this->save();
    }
}