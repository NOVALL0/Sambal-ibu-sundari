{{-- resources/views/orders.blade.php --}}
@extends('layouts.app')

@section('title', 'My Orders - Sambal Kitchen')

@section('content')
<div class="container mx-auto px-6 py-12" x-data="ordersPage()" x-init="loadOrders()">
    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="spice-tag inline-block mb-4">📋 MY ORDERS</span>
        <h1 class="text-5xl font-bold text-white mb-4">Pesanan Saya</h1>
        <p class="text-gray-400 max-w-2xl mx-auto">
            Lacak dan kelola semua pesananmu
        </p>
    </div>

    {{-- Loading State --}}
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-red-500 border-t-transparent"></div>
        <p class="text-gray-400 mt-4">Loading orders...</p>
    </div>

    {{-- Empty Orders --}}
    <div x-show="!loading && orders.length === 0" class="text-center py-12">
        <div class="text-8xl mb-4">📦</div>
        <h3 class="text-2xl font-bold text-white mb-2">Belum Ada Pesanan</h3>
        <p class="text-gray-400 mb-6">Yuk, order sambal favoritmu sekarang!</p>
        <a href="{{ route('products') }}" class="sambal-btn-primary inline-block">
            Mulai Belanja 🔥
        </a>
    </div>

    {{-- Orders List --}}
    <div x-show="!loading && orders.length > 0" class="max-w-4xl mx-auto">
        <div class="space-y-4">
            <template x-for="order in orders" :key="order.id">
                <div class="sambal-card p-6">
                    {{-- Order Header --}}
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <div>
                            <span class="text-sm text-gray-400">Order #</span>
                            <span class="text-white font-bold" x-text="order.id"></span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-400" x-text="new Date(order.created_at).toLocaleDateString('id-ID')"></span>
                            <span class="px-3 py-1 rounded-full text-sm"
                                  :class="{
                                      'bg-yellow-500/20 text-yellow-500': order.status === 'pending',
                                      'bg-blue-500/20 text-blue-500': order.status === 'paid',
                                      'bg-green-500/20 text-green-500': order.status === 'done',
                                      'bg-red-500/20 text-red-500': order.status === 'cancelled'
                                  }"
                                  x-text="order.status.toUpperCase()"></span>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="space-y-3 mb-4">
                        <template x-for="item in order.items" :key="item.id">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-400" x-text="item.quantity + 'x'"></span>
                                    <span class="text-white" x-text="item.product.name"></span>
                                </div>
                                <span class="text-gray-300" x-text="'Rp ' + (item.price * item.quantity).toLocaleString()"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Order Footer --}}
                    <div class="flex flex-wrap justify-between items-center pt-4 border-t border-gray-700">
                        <div>
                            <span class="text-gray-400">Total: </span>
                            <span class="text-xl font-bold text-red-500" x-text="'Rp ' + order.total_price.toLocaleString()"></span>
                        </div>
                        <div class="flex space-x-3">
                            <button @click="viewOrderDetail(order.id)"
                                    class="text-gray-400 hover:text-red-500 transition">
                                Detail
                            </button>
                            <template x-if="order.status === 'pending'">
                                <button @click="cancelOrder(order.id)"
                                        class="text-red-500 hover:text-red-600 transition">
                                    Cancel
                                </button>
                            </template>
                            <template x-if="order.status === 'pending'">
                                {{-- PERBAIKAN: Link ke halaman payment --}}
                                <a :href="'{{ url('/payment') }}/' + order.id"
                                   class="sambal-btn-primary px-4 py-1 text-sm">
                                    Bayar
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex justify-center" x-show="pagination.last_page > 1">
            <div class="flex space-x-2">
                <template x-for="page in pagination.last_page" :key="page">
                    <button @click="loadOrders(page)"
                            class="w-10 h-10 rounded-lg transition"
                            :class="pagination.current_page === page ? 'bg-red-600 text-white' : 'bg-[#262626] text-gray-400 hover:text-white'"
                            x-text="page"></button>
                </template>
            </div>
        </div>
    </div>

    {{-- Order Detail Modal --}}
    <div x-show="showDetail"
         x-cloak
         class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
         @keydown.escape.window="showDetail = false">
        <div class="sambal-card p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Detail Pesanan</h3>
                <button @click="showDetail = false" class="text-gray-400 hover:text-white text-2xl">×</button>
            </div>

            <template x-if="selectedOrder">
                <div class="space-y-6">
                    {{-- Order Info --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-400">Order ID</div>
                            <div class="text-white font-bold" x-text="'#' + selectedOrder.id"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Tanggal</div>
                            <div class="text-white" x-text="new Date(selectedOrder.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Status</div>
                            <span class="px-3 py-1 rounded-full text-sm"
                                  :class="{
                                      'bg-yellow-500/20 text-yellow-500': selectedOrder.status === 'pending',
                                      'bg-blue-500/20 text-blue-500': selectedOrder.status === 'paid',
                                      'bg-green-500/20 text-green-500': selectedOrder.status === 'done',
                                      'bg-red-500/20 text-red-500': selectedOrder.status === 'cancelled'
                                  }"
                                  x-text="selectedOrder.status.toUpperCase()"></span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Pengiriman</div>
                            <div class="text-white" x-text="selectedOrder.shipping_method === 'shipping' ? '🚚 Dikirim' : '🏪 Ambil di Toko'"></div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div>
                        <h4 class="text-lg font-bold text-white mb-3">Items</h4>
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
                    <div x-show="selectedOrder.payment">
                        <h4 class="text-lg font-bold text-white mb-3">Pembayaran</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-400">Metode</div>
                                <div class="text-white" x-text="selectedOrder.payment.payment_method"></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Status</div>
                                <span class="px-3 py-1 rounded-full text-sm"
                                      :class="selectedOrder.payment.payment_status === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500'"
                                      x-text="selectedOrder.payment.payment_status"></span>
                            </div>
                        </div>
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
    function ordersPage() {
        return {
            orders: [],
            loading: true,
            showDetail: false,
            selectedOrder: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            },

            async loadOrders(page = 1) {
                this.loading = true;
                try {
                    const response = await axios.get(`/api/orders?page=${page}`);
                    this.orders = response.data.data || [];
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        total: response.data.total
                    };
                } catch (error) {
                    console.error('Error loading orders:', error);
                    if (error.response?.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                } finally {
                    this.loading = false;
                }
            },

            async viewOrderDetail(orderId) {
                try {
                    const response = await axios.get(`/api/orders/${orderId}`);
                    this.selectedOrder = response.data.data;
                    this.showDetail = true;
                } catch (error) {
                    console.error('Error loading order detail:', error);
                    alert('Gagal memuat detail pesanan');
                }
            },

            async cancelOrder(orderId) {
                if (!confirm('Batalkan pesanan ini?')) return;

                try {
                    await axios.post(`/api/orders/${orderId}/cancel`);
                    await this.loadOrders(this.pagination.current_page);
                } catch (error) {
                    console.error('Error cancelling order:', error);
                    alert('Gagal membatalkan pesanan');
                }
            }
        }
    }
</script>
@endpush
@endsection