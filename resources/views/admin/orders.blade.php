{{-- resources/views/admin/orders.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Orders - Sambal Kitchen')

@section('content')
<div class="container mx-auto px-6 py-12" x-data="adminOrders()" x-init="loadOrders()">
    <div class="flex justify-between items-center mb-8">
        <div>
            <span class="spice-tag inline-block mb-4">📋 ORDERS</span>
            <h1 class="text-4xl font-bold text-white">Manage Orders</h1>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <select x-model="status" @change="loadOrders()" class="sambal-input w-40">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="done">Done</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <input type="date" x-model="dateFrom" @change="loadOrders()" class="sambal-input w-40">
        <input type="date" x-model="dateTo" @change="loadOrders()" class="sambal-input w-40">

        <input type="text"
               x-model="search"
               @keyup.enter="loadOrders()"
               placeholder="Search customer..."
               class="sambal-input flex-1">
    </div>

    {{-- Orders Table --}}
    <div class="sambal-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-gray-400 text-sm border-b border-gray-700">
                        <th class="text-left py-3">Order ID</th>
                        <th class="text-left py-3">Customer</th>
                        <th class="text-left py-3">Total</th>
                        <th class="text-left py-3">Status</th>
                        <th class="text-left py-3">Payment</th>
                        <th class="text-left py-3">Date</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="order in orders" :key="order.id">
                        <tr class="border-b border-gray-800 hover:bg-[#262626]">
                            <td class="py-3 text-white" x-text="'#' + order.id"></td>
                            <td class="py-3">
                                <div x-text="order.user?.name"></div>
                                <div class="text-xs text-gray-500" x-text="order.user?.email"></div>
                            </td>
                            <td class="py-3 text-white" x-text="'Rp ' + order.total_price.toLocaleString()"></td>
                            <td class="py-3">
                                <select @change="updateStatus(order.id, $event.target.value)"
                                        class="bg-[#262626] text-white border border-gray-700 rounded-lg px-2 py-1 text-sm">
                                    <option value="pending" :selected="order.status === 'pending'">Pending</option>
                                    <option value="paid" :selected="order.status === 'paid'">Paid</option>
                                    <option value="done" :selected="order.status === 'done'">Done</option>
                                    <option value="cancelled" :selected="order.status === 'cancelled'">Cancelled</option>
                                </select>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs"
                                      :class="order.payment?.payment_status === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500'"
                                      x-text="order.payment?.payment_status || 'pending'"></span>
                            </td>
                            <td class="py-3 text-gray-400" x-text="new Date(order.created_at).toLocaleDateString('id-ID')"></td>
                            <td class="py-3">
                                <button @click="viewOrderDetail(order)" class="text-blue-500 hover:text-blue-400 mr-2">👁️</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Order Detail Modal --}}
    <div x-show="showDetail"
         x-cloak
         class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
         @keydown.escape.window="showDetail = false">
        <div class="sambal-card p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Order #<span x-text="selectedOrder?.id"></span></h3>
                <button @click="showDetail = false" class="text-gray-400 hover:text-white text-2xl">×</button>
            </div>

            <template x-if="selectedOrder">
                <div class="space-y-6">
                    {{-- Customer Info --}}
                    <div class="bg-[#262626] p-4 rounded-xl">
                        <h4 class="text-white font-bold mb-2">Customer Information</h4>
                        <p class="text-gray-400" x-text="'Name: ' + selectedOrder.user?.name"></p>
                        <p class="text-gray-400" x-text="'Email: ' + selectedOrder.user?.email"></p>
                    </div>

                    {{-- Order Items --}}
                    <div>
                        <h4 class="text-white font-bold mb-3">Order Items</h4>
                        <div class="space-y-2">
                            <template x-for="item in selectedOrder.items" :key="item.id">
                                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                                    <div>
                                        <span class="text-white" x-text="item.product.name"></span>
                                        <span class="text-gray-400 text-sm ml-2" x-text="'x' + item.quantity"></span>
                                    </div>
                                    <span class="text-white" x-text="'Rp ' + (item.price * item.quantity).toLocaleString()"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Payment Info --}}
                    <div class="bg-[#262626] p-4 rounded-xl">
                        <h4 class="text-white font-bold mb-2">Payment Information</h4>
                        <p class="text-gray-400" x-text="'Method: ' + selectedOrder.payment?.payment_method"></p>
                        <p class="text-gray-400" x-text="'Status: ' + selectedOrder.payment?.payment_status"></p>
                        <p class="text-gray-400" x-text="selectedOrder.payment?.paid_at ? 'Paid at: ' + new Date(selectedOrder.payment.paid_at).toLocaleString() : ''"></p>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                        <span class="text-lg text-white">Total</span>
                        <span class="text-2xl font-bold text-red-500" x-text="'Rp ' + selectedOrder.total_price.toLocaleString()"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function adminOrders() {
        return {
            orders: [],
            status: '',
            dateFrom: '',
            dateTo: '',
            search: '',
            showDetail: false,
            selectedOrder: null,

            async loadOrders() {
                try {
                    let url = '/api/admin/orders';
                    const params = new URLSearchParams();
                    if (this.status) params.append('status', this.status);
                    if (this.dateFrom) params.append('date_from', this.dateFrom);
                    if (this.dateTo) params.append('date_to', this.dateTo);
                    if (this.search) params.append('search', this.search);
                    if (params.toString()) url += '?' + params.toString();

                    const response = await axios.get(url);
                    this.orders = response.data.data.data || [];
                } catch (error) {
                    console.error('Error loading orders:', error);
                }
            },

            async updateStatus(orderId, newStatus) {
                try {
                    await axios.put(`/api/admin/orders/${orderId}/status`, {
                        status: newStatus
                    });
                    await this.loadOrders();
                } catch (error) {
                    alert('Error updating order status');
                }
            },

            viewOrderDetail(order) {
                this.selectedOrder = order;
                this.showDetail = true;
            }
        }
    }
</script>
@endpush
@endsection