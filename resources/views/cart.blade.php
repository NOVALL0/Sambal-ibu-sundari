{{-- resources/views/cart.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sambal Ibu Sundari')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    {{-- Konten akan diisi oleh Alpine.js --}}
    <div x-data="cartManager()" x-init="loadCart()" class="w-full">
        {{-- Header --}}
        <div class="text-center mb-8 md:mb-12">
            <span class="spice-tag inline-block mb-3 md:mb-4 text-sm md:text-base px-3 py-1 md:px-4 md:py-2">🛒 KERANJANG BELANJA</span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 md:mb-4">Keranjang Belanja</h1>
            <p class="text-sm md:text-base text-gray-400 max-w-2xl mx-auto">
                Periksa kembali pesanan Anda sebelum checkout
            </p>
        </div>

        {{-- Loading State --}}
        <div x-show="loading" class="text-center py-12 md:py-20">
            <div class="inline-block w-12 h-12 md:w-16 md:h-16 border-4 border-red-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-400 mt-4 text-sm md:text-base">Memuat keranjang...</p>
        </div>

        {{-- Empty Cart --}}
        <div x-show="!loading && items.length === 0" class="text-center py-12 md:py-20">
            <div class="text-7xl md:text-8xl mb-4 md:mb-6 opacity-30">🛒</div>
            <h3 class="text-xl md:text-2xl font-bold text-white mb-2 md:mb-3">Keranjang Kosong</h3>
            <p class="text-sm md:text-base text-gray-400 mb-6 md:mb-8 max-w-md mx-auto">
                Yuk, tambahkan sambal favoritmu ke keranjang!
            </p>
            <a href="{{ route('products') }}" class="sambal-btn-primary inline-block px-6 md:px-8 py-3 md:py-4 text-base md:text-lg">
                Mulai Belanja 🔥
            </a>
        </div>

        {{-- Cart Items --}}
        <div x-show="!loading && items.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            {{-- Items List --}}
            <div class="lg:col-span-2 space-y-3 md:space-y-4">
                <template x-for="item in items" :key="item.id">
                    <div class="sambal-card p-4 md:p-6 hover:border-red-500/30 transition-all duration-300">
                        <div class="flex flex-col sm:flex-row gap-4 md:gap-6">
                            {{-- Product Image --}}
                            <div class="w-full sm:w-24 md:w-32 h-24 md:h-32 rounded-xl overflow-hidden bg-[#262626] mx-auto sm:mx-0">
                                <img :src="'{{ asset('storage') }}/' + item.product.image">
                                     :alt="item.name"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            </div>

                            {{-- Product Details --}}
                            <div class="flex-1 text-center sm:text-left">
                                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start gap-2">
                                    <div>
                                        <h3 class="text-lg md:text-xl font-bold text-white mb-1" x-text="item.name"></h3>
                                        <p class="text-xs md:text-sm text-gray-400" x-text="item.variant || 'Sambal'"></p>
                                    </div>
                                    <button @click="removeItem(item.id)"
                                            class="w-8 h-8 rounded-lg bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white transition-all flex items-center justify-center">
                                        <span class="text-xl">×</span>
                                    </button>
                                </div>

                                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-end mt-4 gap-4">
                                    {{-- Quantity Control --}}
                                    <div class="flex items-center space-x-2">
                                        <button @click="updateQuantity(item.id, item.quantity - 1)"
                                                class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-[#262626] border border-gray-700 text-white hover:bg-red-600 hover:border-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="item.quantity <= 1">
                                            <span class="text-base md:text-lg font-bold">−</span>
                                        </button>
                                        <span class="w-8 md:w-12 text-center text-white font-bold text-base md:text-lg" x-text="item.quantity"></span>
                                        <button @click="updateQuantity(item.id, item.quantity + 1)"
                                                class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-[#262626] border border-gray-700 text-white hover:bg-red-600 hover:border-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="item.quantity >= (item.stock || 999)">
                                            <span class="text-base md:text-lg font-bold">+</span>
                                        </button>
                                    </div>

                                    {{-- Price --}}
                                    <div class="text-right">
                                        <div class="text-xs md:text-sm text-gray-400 mb-1">Subtotal</div>
                                        <div class="text-lg md:text-xl lg:text-2xl font-bold text-red-500"
                                             x-text="'Rp ' + ((item.price * item.quantity) || 0).toLocaleString('id-ID')"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4">
                    <button @click="clearCart()"
                            class="w-full sm:w-auto text-gray-400 hover:text-red-500 transition flex items-center justify-center gap-2 bg-[#262626] hover:bg-red-600/10 px-4 md:px-6 py-2 md:py-3 rounded-xl border border-gray-700 hover:border-red-500 text-sm md:text-base">
                        <span class="text-lg md:text-xl">🗑️</span>
                        <span class="font-medium">Kosongkan Keranjang</span>
                    </button>

                    <a href="{{ route('products') }}"
                       class="w-full sm:w-auto text-gray-400 hover:text-red-500 transition flex items-center justify-center gap-2 bg-[#262626] hover:bg-red-600/10 px-4 md:px-6 py-2 md:py-3 rounded-xl border border-gray-700 hover:border-red-500 text-sm md:text-base">
                        <span>←</span>
                        <span class="font-medium">Lanjut Belanja</span>
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="sambal-card p-4 md:p-6 sticky top-20 md:top-24">
                    <h3 class="text-lg md:text-xl font-bold text-white mb-4 md:mb-6 flex items-center gap-2">
                        <span class="bg-red-600/20 p-1.5 md:p-2 rounded-lg text-red-500 text-lg md:text-xl">📋</span>
                        Ringkasan Belanja
                    </h3>

                    {{-- Cart Items List Mini --}}
                    <div class="space-y-2 md:space-y-3 max-h-48 overflow-y-auto mb-3 md:mb-4 pr-2">
                        <template x-for="item in items" :key="item.id">
                            <div class="flex justify-between items-center text-xs md:text-sm py-1.5 md:py-2 border-b border-gray-700/50 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400 font-medium" x-text="item.quantity + 'x'"></span>
                                    <span class="text-white truncate max-w-[120px] md:max-w-[150px]" x-text="item.name"></span>
                                </div>
                                <span class="text-gray-300 font-medium" x-text="'Rp ' + ((item.price * item.quantity) || 0).toLocaleString('id-ID')"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Totals Calculation --}}
                    <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                        <div class="flex justify-between text-xs md:text-sm text-gray-400">
                            <span>Subtotal</span>
                            <span class="text-white font-medium" x-text="'Rp ' + (subtotal || 0).toLocaleString('id-ID')"></span>
                        </div>

                        <div class="flex justify-between text-xs md:text-sm text-gray-400">
                            <span>Estimasi Ongkir</span>
                            <span class="text-green-500 font-medium">Dihitung nanti</span>
                        </div>

                        <div class="border-t border-gray-700 my-2 md:my-3"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm md:text-base text-white font-bold">Total</span>
                            <div class="text-right">
                                <span class="text-lg md:text-xl lg:text-2xl font-bold text-red-500" x-text="'Rp ' + (subtotal || 0).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <a href="{{ route('orders.checkout') }}"
                       class="sambal-btn-primary w-full py-3 md:py-4 text-sm md:text-base font-bold flex items-center justify-center gap-2 group"
                       :class="{ 'opacity-50 pointer-events-none': items.length === 0 }">
                        <span>Checkout Sekarang</span>
                        <span class="group-hover:translate-x-1 transition-transform text-lg">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cartManager() {
        return {
            items: [],
            loading: true,

            get subtotal() {
                return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },

            async loadCart() {
                this.loading = true;
                try {
                    const response = await axios.get('/api/cart');
                    if (response.data.success && response.data.data) {
                        this.items = response.data.data.items || [];
                    }
                } catch (error) {
                    console.error('Error loading cart:', error);
                    if (error.response?.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                } finally {
                    this.loading = false;
                }
            },

            async updateQuantity(itemId, newQuantity) {
                if (newQuantity < 1) return;

                try {
                    const response = await axios.put(`/api/cart/item/${itemId}`, {
                        quantity: newQuantity
                    });

                    if (response.data.success) {
                        await this.loadCart();
                    }
                } catch (error) {
                    console.error('Error updating quantity:', error);
                    alert('Gagal mengupdate quantity');
                }
            },

            async removeItem(itemId) {
                if (!confirm('Hapus item ini dari keranjang?')) return;

                try {
                    const response = await axios.delete(`/api/cart/item/${itemId}`);
                    if (response.data.success) {
                        await this.loadCart();
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                    alert('Gagal menghapus item');
                }
            },

            async clearCart() {
                if (!confirm('Kosongkan seluruh keranjang?')) return;

                try {
                    const response = await axios.delete('/api/cart/clear');
                    if (response.data.success) {
                        await this.loadCart();
                    }
                } catch (error) {
                    console.error('Error clearing cart:', error);
                    alert('Gagal mengosongkan keranjang');
                }
            }
        }
    }
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #1a1a1a; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #dc2626; border-radius: 4px; }
</style>
@endpush