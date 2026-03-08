{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="admin-card p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm">Total Orders</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($totalOrders) }}</h3>
                <div class="flex gap-2 mt-2">
                    <span class="text-xs text-green-500">✓ {{ $completedOrders }} completed</span>
                    <span class="text-xs text-yellow-500">⏳ {{ $pendingOrders }} pending</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl text-blue-500">📦</span>
            </div>
        </div>
    </div>

    <div class="admin-card p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm">Total Revenue</p>
                <h3 class="text-3xl font-bold text-green-500 mt-2">Rp {{ number_format($totalRevenue) }}</h3>
                <p class="text-xs text-gray-400 mt-2">From {{ $completedOrders }} completed orders</p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl text-green-500">💰</span>
            </div>
        </div>
    </div>

    <div class="admin-card p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm">Products</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($totalProducts) }}</h3>
                <p class="text-xs text-gray-400 mt-2">{{ $activeProducts }} active • {{ $totalProducts - $activeProducts }} inactive</p>
            </div>
            <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl text-red-500">🌶️</span>
            </div>
        </div>
    </div>

    <div class="admin-card p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm">Users</p>
                <h3 class="text-3xl font-bold text-white mt-2">{{ number_format($totalUsers) }}</h3>
                <p class="text-xs text-green-500 mt-2">+{{ $newUsers }} new this month</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl text-purple-500">👥</span>
            </div>
        </div>
    </div>
</div>



{{-- Charts and Tables --}}
<div class="">
    {{-- Recent Orders --}}
    <div class="admin-card p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-red-500 hover:text-red-400 transition">
                View All →
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="text-left py-3">Order ID</th>
                            <th class="text-left py-3">Customer</th>
                            <th class="text-left py-3">Total</th>
                            <th class="text-left py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="py-3">
                                <span class="font-medium text-white">#{{ $order['order_number'] }}</span>
                            </td>
                            <td class="py-3 text-gray-300">{{ $order['customer'] }}</td>
                            <td class="py-3 text-white">Rp {{ number_format($order['total']) }}</td>
                            <td class="py-3">
                                @php
                                    $statusClass = match($order['status']) {
                                        'pending' => 'badge-warning',
                                        'processing' => 'badge-info',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-danger',
                                        default => 'badge-warning'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order['status']) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                No orders yet
            </div>
        @endif
    </div>


</div>


@endsection