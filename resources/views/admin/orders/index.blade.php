{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Orders</h1>
    <p class="text-gray-400 mt-1">Manage customer orders</p>
</div>

{{-- Filter & Search --}}
<div class="mb-6 flex flex-col md:flex-row gap-4">
    <form method="GET" action="{{ url('/admin/orders') }}" class="flex-1 flex flex-wrap gap-2">
        <select name="status" class="admin-input w-32">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}" class="admin-input w-36">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="admin-input w-36">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search order ID or customer..."
               class="admin-input flex-1">

        <button type="submit" class="btn-secondary">Filter</button>
        <a href="{{ url('/admin/orders') }}" class="btn-secondary">Reset</a>
    </form>
</div>

{{-- Orders Table --}}
<div class="admin-card p-0 overflow-hidden">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="font-medium">#{{ $order->id }}</td>
                <td>
                    <div>{{ $order->user->name ?? 'Guest' }}</div>
                    <div class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</div>
                </td>
                <td class="font-bold text-green-500">Rp {{ number_format($order->total_price) }}</td>
                <td>
                    <span class="badge
                        @if($order->status == 'pending') badge-warning
                        @elseif($order->status == 'paid') badge-success
                        @elseif($order->status == 'done') badge-info
                        @else badge-danger
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $order->payment->payment_status == 'success' ? 'badge-success' : 'badge-warning' }}">
                        {{ $order->payment->payment_status ?? 'pending' }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ url('/admin/orders/' . $order->id) }}"
                       class="text-blue-500 hover:text-blue-400 transition"
                       title="View Detail">
                        👁️ Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-400">
                    No orders found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection