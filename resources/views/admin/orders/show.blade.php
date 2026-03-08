{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Order #{{ $order->id }}</h1>
            <p class="text-gray-400 mt-1">Order details and management</p>
        </div>
        <a href="{{ url('/admin/orders') }}" class="btn-secondary">
            ← Back to Orders
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Order Items --}}
        <div class="admin-card">
            <h3 class="text-lg font-bold text-white mb-4">Order Items</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->variant }}</td>
                        <td>Rp {{ number_format($item->price) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="font-bold">Rp {{ number_format($item->price * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-bold text-white">Total:</td>
                        <td class="font-bold text-green-500 text-lg">Rp {{ number_format($order->total_price) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Update Status --}}
        <div class="admin-card">
            <h3 class="text-lg font-bold text-white mb-4">Update Order Status</h3>
            <form method="POST" action="{{ url('/admin/orders/' . $order->id . '/status') }}" class="flex items-center gap-3">
                @csrf
                @method('PUT')
                <select name="status" class="admin-input w-40">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Customer Info --}}
        <div class="admin-card">
            <h3 class="text-lg font-bold text-white mb-4">Customer Information</h3>
            <div class="space-y-2">
                <p class="text-gray-400">Name: <span class="text-white">{{ $order->user->name ?? 'Guest' }}</span></p>
                <p class="text-gray-400">Email: <span class="text-white">{{ $order->user->email ?? '-' }}</span></p>
                <p class="text-gray-400">Phone: <span class="text-white">{{ $order->user->phone ?? '-' }}</span></p>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="admin-card">
            <h3 class="text-lg font-bold text-white mb-4">Payment Information</h3>
            <div class="space-y-2">
                <p class="text-gray-400">Method: <span class="text-white">{{ $order->payment->payment_method ?? '-' }}</span></p>
                <p class="text-gray-400">Status:
                    <span class="badge {{ $order->payment->payment_status == 'success' ? 'badge-success' : 'badge-warning' }}">
                        {{ $order->payment->payment_status ?? 'pending' }}
                    </span>
                </p>
                @if($order->payment->paid_at)
                <p class="text-gray-400">Paid at: <span class="text-white">{{ $order->payment->paid_at->format('d M Y H:i') }}</span></p>
                @endif
            </div>
        </div>

        {{-- Order Info --}}
        <div class="admin-card">
            <h3 class="text-lg font-bold text-white mb-4">Order Information</h3>
            <div class="space-y-2">
                <p class="text-gray-400">Order Date: <span class="text-white">{{ $order->created_at->format('d M Y H:i') }}</span></p>
                <p class="text-gray-400">Shipping: <span class="text-white">{{ $order->shipping_method }}</span></p>
                <p class="text-gray-400">Status:
                    <span class="badge
                        @if($order->status == 'pending') badge-warning
                        @elseif($order->status == 'paid') badge-success
                        @elseif($order->status == 'done') badge-info
                        @else badge-danger
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection