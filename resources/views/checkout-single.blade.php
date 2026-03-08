{{-- resources/views/checkout-single.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout - ' . $product->name . ' - Sambal Ibu Sundari')

@section('content')
<div class="container mx-auto px-6 py-12" x-data="checkoutSingle()" x-init="initCheckout()">
    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="spice-tag inline-block mb-4">⚡ BELI SEKARANG</span>
        <h1 class="text-5xl font-bold text-white mb-4">Checkout Produk</h1>
        <p class="text-gray-400 max-w-2xl mx-auto">
            Lengkapi data di bawah untuk membeli <span class="text-red-500 font-bold">{{ $product->name }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Checkout --}}
        <div class="lg:col-span-2">
            <form @submit.prevent="submitOrder" class="space-y-6">
                {{-- ========== INFORMASI PENERIMA ========== --}}
                <div class="sambal-card p-6 hover:border-red-500/30 transition-all duration-300">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3 border-b border-gray-700 pb-3">
                        <span class="bg-red-600/20 p-2 rounded-lg text-red-500">📋</span>
                        <span>Informasi Penerima</span>
                    </h3>

                    <div class="space-y-5">
                        {{-- Nama Lengkap --}}
                        <div class="group">
                            <label class="block text-gray-300 text-sm font-medium mb-2 flex items-center justify-between">
                                <span class="flex items-center gap-1">
                                    <span class="text-red-500">👤</span>
                                    Nama Lengkap
                                </span>
                                <span class="text-red-500 text-xs bg-red-500/10 px-2 py-0.5 rounded-full">wajib</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       x-model="customerName"
                                       class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3.5 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all duration-200 group-hover:border-gray-600"
                                       placeholder="Masukkan nama lengkap Anda"
                                       :class="{ 'border-red-500 ring-4 ring-red-500/10': !customerName && submitted }">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span x-show="customerName" class="text-green-500">✓</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                <span>ℹ️</span>
                                <span>Nama sesuai KTP/identitas</span>
                            </p>
                        </div>

                        {{-- No. HP --}}
                        <div class="group">
                            <label class="block text-gray-300 text-sm font-medium mb-2 flex items-center justify-between">
                                <span class="flex items-center gap-1">
                                    <span class="text-red-500">📱</span>
                                    Nomor HP
                                </span>
                                <span class="text-red-500 text-xs bg-red-500/10 px-2 py-0.5 rounded-full">wajib</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-400">+62</span>
                                <input type="tel"
                                       x-model="customerPhone"
                                       class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3.5 pl-12 pr-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all duration-200 group-hover:border-gray-600"
                                       placeholder="81234567890"
                                       :class="{ 'border-red-500 ring-4 ring-red-500/10': !customerPhone && submitted }">
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                <span>📱</span>
                                <span>Pastikan nomor aktif untuk konfirmasi</span>
                            </p>
                        </div>

                        {{-- Email --}}
                        <div class="group">
                            <label class="block text-gray-300 text-sm font-medium mb-2 flex items-center gap-1">
                                <span class="text-red-500">📧</span>
                                Email
                                <span class="text-gray-500 text-xs ml-1">(opsional)</span>
                            </label>
                            <div class="relative">
                                <input type="email"
                                       x-model="customerEmail"
                                       class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3.5 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all duration-200 group-hover:border-gray-600"
                                       placeholder="nama@email.com"
                                       value="{{ auth()->user()->email ?? '' }}">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span x-show="customerEmail" class="text-green-500 text-xs">✓</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                <span>📨</span>
                                <span>Untuk mengirim invoice dan notifikasi</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ========== METODE PENGIRIMAN ========== --}}
                <div class="sambal-card p-6">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="bg-red-600/20 p-2 rounded-lg text-red-500">🚚</span>
                        Metode Pengiriman
                    </h3>

                    <div class="space-y-3">
                        {{-- Dikirim --}}
                        <label class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer hover:border-red-500 transition-all"
                               :class="{ 'border-red-500 bg-red-500/10': shippingMethod === 'shipping' }">
                            <input type="radio" value="shipping" x-model="shippingMethod" class="mr-3">
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-white font-bold">🚚 Dikirim ke Alamat</span>
                                    <span class="text-green-500 font-medium">menyesuaikan wilayah</span>
                                </div>
                                <p class="text-sm text-gray-400">Pesanan akan diantar ke alamat tujuan</p>
                            </div>
                        </label>
                        <div x-show="shippingMethod === 'shipping'"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 transform -translate-y-4"
x-transition:enter-end="opacity-100 transform translate-y-0"
class="mt-6 pt-4 border-t border-gray-700">

<h4 class="text-white font-semibold mb-4">Alamat Pengiriman</h4>

<div class="space-y-4">
   {{-- Provinsi --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Provinsi <span class="text-red-500">*</span>
       </label>
       <select x-model="province"
               class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
           <option value="" class="bg-[#262626]">Pilih Provinsi</option>
           <option value="Jawa Tengah" class="bg-[#262626]">Jawa Tengah</option>
           <option value="Jawa Barat" class="bg-[#262626]">Jawa Barat</option>
           <option value="Jawa Timur" class="bg-[#262626]">Jawa Timur</option>
           <option value="DKI Jakarta" class="bg-[#262626]">DKI Jakarta</option>
           <option value="Banten" class="bg-[#262626]">Banten</option>
           <option value="DIY Yogyakarta" class="bg-[#262626]">DIY Yogyakarta</option>
       </select>
   </div>

   {{-- Kota/Kabupaten --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Kota / Kabupaten <span class="text-red-500">*</span>
       </label>
       <input type="text"
              x-model="city"
              placeholder="Contoh: Purbalingga"
              class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
   </div>

   {{-- Kecamatan --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Kecamatan <span class="text-red-500">*</span>
       </label>
       <input type="text"
              x-model="district"
              placeholder="Contoh: Kalimanah"
              class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
   </div>

   {{-- Kelurahan/Desa --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Kelurahan/Desa <span class="text-red-500">*</span>
       </label>
       <input type="text"
              x-model="village"
              placeholder="Contoh: Babakan"
              class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
   </div>

   {{-- Kode Pos --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Kode Pos <span class="text-red-500">*</span>
       </label>
       <input type="text"
              x-model="postalCode"
              placeholder="53371"
              class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
   </div>

   {{-- Alamat Lengkap --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Detail Alamat <span class="text-red-500">*</span>
       </label>
       <textarea x-model="detailAddress"
                 rows="3"
                 placeholder="Contoh: Jl. Letnan Yusuf, RT 01 RW 02, Karang Asem"
                 class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all resize-none"></textarea>
       <p class="text-xs text-gray-500 mt-1">Nama jalan, nomor rumah, RT/RW</p>
   </div>

   {{-- Catatan untuk Kurir (Opsional) --}}
   <div>
       <label class="block text-gray-300 text-sm font-medium mb-2">
           Catatan untuk Kurir <span class="text-gray-500 text-xs">(opsional)</span>
       </label>
       <input type="text"
              x-model="courierNotes"
              placeholder="Contoh: Pintu pagar warna hijau / dekat masjid"
              class="w-full bg-[#262626] border-2 border-gray-700 rounded-xl py-3 px-4 text-white placeholder-gray-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all">
   </div>
</div>
</div>

                        {{-- Ambil di Toko --}}
                        <label class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer hover:border-red-500 transition-all"
                               :class="{ 'border-red-500 bg-red-500/10': shippingMethod === 'pickup' }">
                            <input type="radio" value="pickup" x-model="shippingMethod" class="mr-3">
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-white font-bold">🏪 Ambil di Toko</span>
                                    <span class="text-green-500 font-medium">Gratis</span>
                                </div>
                                <p class="text-sm text-gray-400">Ambil langsung di dapur kami</p>
                            </div>
                        </label>
                    </div>

                    {{-- Maps Section - Muncul hanya jika pilih pickup --}}
                    <div x-show="shippingMethod === 'pickup'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-6 pt-4 border-t border-gray-700">

                        <div class="mb-3">
                            <p class="text-sm text-gray-400 leading-relaxed">
                                Jl. Letnan Yusuf, Karang Asem, Babakan, Kec. Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371
                            </p>
                        </div>

                        {{-- Klikable Map --}}
                        <a href="https://maps.google.com/?q=sambal+dapur+ibu+sundari+Jl.+Letnan+Yusuf,+Karang+Asem,+Babakan,+Kalimanah,+Purbalingga"
                           target="_blank"
                           class="block rounded-xl overflow-hidden border border-gray-700 h-48 md:h-64 w-full hover:border-red-500 transition-all duration-300 group">

                            {{-- Google Maps Embed (dijadikan background) --}}
                            <div class="relative w-full h-full">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.123456789!2d109.123456!3d-7.123456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7...!2sJl.%20Letnan%20Yusuf%2C%20Karang%20Asem%2C%20Babakan%2C%20Kec.%20Kalimanah%2C%20Kabupaten%20Purbalingga%2C%20Jawa%20Tengah%2053371!5e0!3m2!1sid!2sid!4v1234567890"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="w-full h-full object-cover pointer-events-none">
                                </iframe>

                                {{-- Overlay saat hover --}}
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <span class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg transform group-hover:scale-105 transition-transform">
                                        Klik untuk buka Google Maps
                                    </span>
                                </div>
                            </div>
                        </a>

                        {{-- Alamat singkat di bawah map --}}
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <span>📍</span>
                            <span>Jl. Letnan Yusuf, Purbalingga (Klik map untuk petunjuk arah)</span>
                        </p>
                    </div>
                </div>


                {{-- ========== SUBMIT BUTTON ========== --}}
                <button type="submit"
                        class="sambal-btn-primary w-full py-5 text-lg font-bold flex items-center justify-center gap-3"
                        :disabled="submitting">
                    <span x-show="!submitting">Buat Pesanan & Lanjutkan ke Pembayaran</span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <span class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        Memproses...
                    </span>
                </button>

                <p class="text-xs text-gray-500 text-center mt-4">
                    Dengan melanjutkan, Anda menyetujui <a href="#" class="text-red-500 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-red-500 hover:underline">Kebijakan Privasi</a> kami.
                </p>
            </form>
        </div>

        {{-- ========== RINGKASAN PRODUK ========== --}}
        <div class="lg:col-span-1">
            <div class="sambal-card p-6 sticky top-24 hover:border-red-500/30 transition-all duration-300">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3 border-b border-gray-700 pb-3">
                    <span class="bg-red-600/20 p-2 rounded-lg text-red-500">📦</span>
                    <span>Ringkasan Produk</span>
                </h3>

                {{-- Produk --}}
                <div class="flex gap-4 pb-6 border-b border-gray-700">
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-[#262626]">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1621243452912-99d7e96b5cf9?w=200&h=200&fit=crop' }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold mb-1">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-400 mb-2">{{ $product->variant }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Jumlah:</span>
                            <div class="flex items-center gap-2">
                                <button @click="decreaseQuantity"
                                        class="w-6 h-6 rounded bg-[#262626] text-white hover:bg-red-600 transition flex items-center justify-center"
                                        :disabled="quantity <= 1">-</button>
                                <span class="text-white font-bold w-8 text-center" x-text="quantity"></span>
                                <button @click="increaseQuantity"
                                        class="w-6 h-6 rounded bg-[#262626] text-white hover:bg-red-600 transition flex items-center justify-center"
                                        :disabled="quantity >= {{ $product->stock }}">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Perhitungan Harga --}}
                <div class="space-y-3 mt-6">
                    <div class="flex justify-between text-gray-400">
                        <span>Harga Satuan</span>
                        <span class="text-white">Rp {{ number_format($product->price) }}</span>
                    </div>

                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal</span>
                        <span class="text-white font-bold" x-text="'Rp ' + ({{ $product->price }} * quantity).toLocaleString()"></span>
                    </div>

                    <div class="flex justify-between text-gray-400">
                        <span>Ongkos Kirim</span>
                        <span class="text-green-500" x-text="shippingMethod === 'shipping' ? 'Dihitung nanti' : 'gratis'"></span>
                    </div>

                    <div class="border-t border-gray-700 my-3"></div>

                    <div class="flex justify-between items-center">
                        <span class="text-white font-bold text-lg">Total</span>
                        <span class="text-2xl font-bold text-red-500"
                              x-text="'Rp ' + ({{ $product->price }} * quantity + (shippingMethod === 'shipping' ? 10000 : 0)).toLocaleString()"></span>
                    </div>
                </div>

                {{-- Stok Info --}}
                <div class="mt-6 p-4 bg-[#262626] rounded-xl">
                    <p class="text-xs text-gray-400 flex items-start gap-2">
                        <span class="text-yellow-500 mt-0.5">📦</span>
                        <span>
                            Stok tersedia: <span class="text-white font-bold">{{ $product->stock }}</span> pcs
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkoutSingle() {
        return {
            customerName: '{{ auth()->user()->name ?? '' }}',
            customerPhone: '',
            customerEmail: '{{ auth()->user()->email ?? '' }}',
            shippingMethod: 'shipping',
            quantity: 1,
            submitted: false,
            submitting: false,

            // Variable untuk alamat (field baru)
            province: '',
            city: '',
            district: '',
            village: '',
            postalCode: '',
            detailAddress: '',
            courierNotes: '',

            increaseQuantity() {
                if (this.quantity < {{ $product->stock }}) {
                    this.quantity++;
                }
            },

            decreaseQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },

            // Fungsi untuk menggabungkan alamat lengkap
            get fullAddress() {
                if (this.shippingMethod === 'pickup') {
                    return 'Ambil di toko';
                }

                let addressParts = [];
                if (this.detailAddress) addressParts.push(this.detailAddress);
                if (this.village) addressParts.push(this.village);
                if (this.district) addressParts.push('Kec. ' + this.district);
                if (this.city) addressParts.push(this.city);
                if (this.province) addressParts.push(this.province);

                let address = addressParts.join(', ');
                if (this.postalCode) address += ' ' + this.postalCode;

                return address;
            },

            validateShippingAddress() {
                if (this.shippingMethod === 'shipping') {
                    if (!this.detailAddress?.trim()) {
                        alert('Detail alamat harus diisi');
                        return false;
                    }
                    if (!this.city?.trim()) {
                        alert('Kota/Kabupaten harus diisi');
                        return false;
                    }
                    if (!this.province) {
                        alert('Provinsi harus dipilih');
                        return false;
                    }
                }
                return true;
            },

            async submitOrder() {
                this.submitted = true;

                // Validasi dasar
                if (!this.customerName?.trim()) {
                    alert('Nama lengkap harus diisi');
                    return;
                }

                if (!this.customerPhone?.trim()) {
                    alert('Nomor HP harus diisi');
                    return;
                }

                // Validasi alamat jika pilih shipping
                if (!this.validateShippingAddress()) {
                    return;
                }

                this.submitting = true;

                try {
                    const orderData = {
                        product_id: {{ $product->id }},
                        quantity: this.quantity,
                        shipping_method: this.shippingMethod,
                        shipping_name: this.customerName,
                        shipping_phone: this.customerPhone,
                        shipping_email: this.customerEmail,
                        shipping_address: this.fullAddress,
                        notes: this.courierNotes || ''
                    };

                    console.log('Order Data:', orderData); // Untuk debugging

                    const response = await axios.post('/api/orders/buy-now', orderData);

                    if (response.data.success) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        alert(response.data.message || 'Terjadi kesalahan');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal: ' + (error.response?.data?.message || 'Terjadi kesalahan'));
                } finally {
                    this.submitting = false;
                }
            }
        }
    }
</script>
@endpush
@endsection