{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Sambal Kitchen - Pedasnya Mantap!')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    {{-- ==================== HERO SECTION ==================== --}}
    <section class="min-h-screen flex items-center py-12 md:py-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            {{-- Left Content --}}
            <div class="space-y-6 md:space-y-8 order-2 lg:order-1">
                {{-- Badge Collection --}}
                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    <span class="spice-tag text-xs md:text-sm px-2 py-1 md:px-3 md:py-1.5">🌶️ Extra Hot</span>
                    <span class="spice-tag text-xs md:text-sm px-2 py-1 md:px-3 md:py-1.5">🧄 Fresh Garlic</span>
                    <span class="spice-tag text-xs md:text-sm px-2 py-1 md:px-3 md:py-1.5">🐟 Premium Tuna</span>
                </div>

                {{-- Heading --}}
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                    <span class="bg-gradient-to-r from-red-500 to-orange-500 bg-clip-text text-transparent">Sambal</span>
                    <br>
                    <span class="text-white">Ibu Sundari</span>
                </h1>

                {{-- Description --}}
                <p class="text-base md:text-lg lg:text-xl text-gray-400 leading-relaxed max-w-xl">
                    Resep turun-temurun dengan sentuhan modern. Dibuat dengan cinta di dapur kami,
                    menggunakan bahan-bahan pilihan dan rempah segar.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
                    <a href="{{ route('products') }}" class="sambal-btn-primary text-center px-6 py-3 md:px-8 md:py-4 text-base md:text-lg">
                        Belanja Sekarang 🔥
                    </a>
                    <a href="#about" class="sambal-btn-secondary text-center px-6 py-3 md:px-8 md:py-4 text-base md:text-lg">
                        Cerita Kami
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 md:gap-8 pt-4 md:pt-8">
                    <div>
                        <div class="text-2xl md:text-3xl font-bold text-red-500">10+</div>
                        <div class="text-xs md:text-sm text-gray-400">Tahun pengalaman</div>
                    </div>
                    <div>
                        <div class="text-2xl md:text-3xl font-bold text-red-500">10k+</div>
                        <div class="text-xs md:text-sm text-gray-400">Pelanggan</div>
                    </div>
                    <div>
                        <div class="text-2xl md:text-3xl font-bold text-red-500">100%</div>
                        <div class="text-xs md:text-sm text-gray-400">Natural</div>
                    </div>
                </div>
            </div>

            {{-- Right Image --}}
            <div class="relative order-1 lg:order-2 mb-8 lg:mb-0">
                <div class="absolute inset-0 bg-gradient-to-r from-red-500/20 to-orange-500/20 rounded-full blur-3xl"></div>
                <div class="relative sambal-card p-2 rotate-2 hover:rotate-0 transition-all duration-500 max-w-md mx-auto lg:max-w-full">
                    <img src="{{ url('image/menu.png') }}"
                         alt="Sambal Ibu Sundari"
                         class="rounded-2xl w-full h-auto aspect-square object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== FEATURED PRODUCTS SECTION ==================== --}}
    <section class="py-12 md:py-20">
        <div class="text-center mb-8 md:mb-12">
            <span class="spice-tag inline-block mb-4 text-sm md:text-base px-3 py-1 md:px-4 md:py-2">🔥 PILIHAN SAMBAL KAMI</span>
            <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-white max-w-2xl mx-auto px-4">
                Pilihan sesuai dengan seleramu!
            </h2>
            <div class="w-20 md:w-24 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto mt-4 md:mt-6"></div>
        </div>

        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                @foreach($featuredProducts as $product)
                    <div class="sambal-card p-3 md:p-4 hover:scale-[1.02] transition-transform duration-300">
                        {{-- Product Image --}}
                        <div class="relative mb-3 md:mb-4">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1621243452912-99d7e96b5cf9?w=500' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-40 sm:h-48 object-cover rounded-xl">

                            {{-- Stock Badge --}}
                            <span class="absolute top-2 left-2 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium backdrop-blur-sm border border-white/20
                                {{ $product->stock > 10 ? 'bg-green-600/40' : ($product->stock > 0 ? 'bg-yellow-600/40' : 'bg-red-600/40') }}">
                                {{ $product->stock > 0 ? $product->stock . ' pcs' : 'Habis' }}
                            </span>
                        </div>

                        {{-- Product Info --}}
                        <h3 class="text-lg md:text-xl font-bold text-white mb-1 md:mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-xs md:text-sm text-gray-400 mb-2 md:mb-4 line-clamp-2">{{ $product->description ?? 'Sambal khas dengan cita rasa pedas mantap' }}</p>

                        {{-- Price --}}
                        <div class="text-xl md:text-2xl font-bold text-red-500 mb-3 md:mb-4">Rp {{ number_format($product->price) }}</div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-2">
                            {{-- Beli Sekarang --}}
                            <form action="{{ route('orders.checkout.single', $product->id) }}" method="GET" class="w-full sm:flex-1">
                                <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold py-2 md:py-3 px-4 rounded-full text-sm md:text-base shadow-lg shadow-red-900/40 transition-all duration-300">
                                    Beli
                                </button>
                            </form>

                            {{-- Keranjang --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="w-full sm:flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                        class="w-full bg-transparent border-2 border-red-600 text-red-500 hover:bg-red-600 hover:text-white font-bold py-2 md:py-3 px-4 rounded-full text-sm md:text-base transition-all duration-300">
                                    + Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback Products (sama dengan di atas) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                @foreach([
                    ['Tuna', 'Sambal Tuna Original', 'Sambal dengan ikan tuna pilihan', 35000],
                    ['Cumi', 'Sambal Cumi Asin', 'Sambal dengan cumi asin pilihan', 42000],
                    ['Bawang', 'Sambal Bawang Special', 'Sambal bawang dengan bawang goreng', 28000]
                ] as $product)
                    <div class="sambal-card p-3 md:p-4">
                        <div class="relative mb-3 md:mb-4">
                            <img src="{{ url('image/menu2.jpg') }}" class="w-full h-40 sm:h-48 object-cover rounded-xl">
                            <span class="absolute top-2 right-2 bg-red-600 text-white px-2 md:px-3 py-1 rounded-full text-xs md:text-sm">{{ $product[0] }}</span>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-white mb-1 md:mb-2">{{ $product[1] }}</h3>
                        <p class="text-xs md:text-sm text-gray-400 mb-2 md:mb-4">{{ $product[2] }}</p>
                        <div class="text-xl md:text-2xl font-bold text-red-500 mb-3 md:mb-4">Rp {{ number_format($product[3]) }}</div>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 md:py-3 rounded-full text-sm md:text-base">Beli</button>
                            <button class="flex-1 bg-transparent border-2 border-red-600 text-red-500 hover:bg-red-600 hover:text-white py-2 md:py-3 rounded-full text-sm md:text-base">+ Keranjang</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ==================== ABOUT US SECTION ==================== --}}
    <section id="about" class="py-12 md:py-20 bg-gradient-to-b from-[#0a0a0a] to-[#111111]">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- Image --}}
                <div class="relative order-2 lg:order-1">
                    <div class="sambal-card p-3 md:p-4">
                        <img src="{{ url('image/sambal.jpg') }}"
                             alt="Our Kitchen"
                             class="rounded-xl w-full h-64 sm:h-80 lg:h-[400px] object-cover">
                    </div>
                </div>

                {{-- Content --}}
                <div class="space-y-4 md:space-y-6 order-1 lg:order-2">
                    <span class="spice-tag text-sm md:text-base px-3 py-1 md:px-4 md:py-2">🏠 FROM OUR KITCHEN</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">Cerita Dapur Kami</h2>
                    <p class="text-sm md:text-base text-gray-400 leading-relaxed">
                        Berawal dari dapur kecil di rumah, kami meracik sambal dengan resep
                        turun-temurun. Setiap batch dimasak dengan penuh cinta dan perhatian,
                        menggunakan cabai segar pilihan dan rempah berkualitas.
                    </p>
                    <div class="grid grid-cols-2 gap-3 md:gap-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-red-500 text-lg md:text-xl">🌶️</span>
                            <span class="text-sm md:text-base text-gray-300">Cabai Segar</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-red-500 text-lg md:text-xl">🧄</span>
                            <span class="text-sm md:text-base text-gray-300">Bawang Pilihan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-red-500 text-lg md:text-xl">🐟</span>
                            <span class="text-sm md:text-base text-gray-300">Ikan Premium</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-red-500 text-lg md:text-xl">🌿</span>
                            <span class="text-sm md:text-base text-gray-300">Rempah Alami</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== TESTIMONIALS SECTION ==================== --}}
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 md:mb-12">
                <span class="spice-tag inline-block mb-4 text-sm md:text-base px-3 py-1 md:px-4 md:py-2">💬 TESTIMONIALS</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Apa Kata Mereka</h2>
                <div class="w-20 md:w-24 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach([
                    ['Budi', 'Sambalnya benar-benar nendang! Rasanya seperti masakan rumah. Tuna nya berasa banget.'],
                    ['Siti', 'Cumi nya besar-besar, porsi banyak. Cocok buat stok di rumah.'],
                    ['Andi', 'Pengiriman cepat, packing rapi. Sambal bawangnya wangi banget!']
                ] as $testimonial)
                    <div class="sambal-card p-4 md:p-6 hover:scale-[1.02] transition-transform duration-300">
                        <div class="flex text-red-500 mb-3 md:mb-4 text-sm md:text-base">⭐⭐⭐⭐⭐</div>
                        <p class="text-sm md:text-base text-gray-300 mb-3 md:mb-4">"{{ $testimonial[1] }}"</p>
                        <div class="font-bold text-white text-sm md:text-base">- {{ $testimonial[0] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== FAQ SECTION ==================== --}}
    <section class="py-12 md:py-20 bg-[#0f0f0f]">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 md:mb-12">
                <span class="spice-tag inline-block mb-4 text-sm md:text-base px-3 py-1 md:px-4 md:py-2">❓ FAQ</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Pertanyaan Umum</h2>
                <div class="w-20 md:w-24 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 max-w-4xl mx-auto">
                @foreach([
                    ['🌶️', 'Berapa lama ketahanan sambal?', 'Tahan hingga 1 -2 bulan jika disimpan  di lemari pendingin.'],
                    ['🚚', 'Apakah gratis ongkir?', 'Gratis ongkir minimal belanja Rp 100.000 di purbalingga .'],
                    ['🛒', 'Cara order gimana?', 'Bisa via website, WhatsApp, atau datang langsung.'],
                    ['🌿', 'Apakah ada pengawet?', '100% alami, tanpa pengawet dan MSG.']
                ] as $faq)
                    <div class="sambal-card p-4 md:p-6 hover:scale-[1.02] transition-transform duration-300">
                        <h3 class="text-base md:text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <span>{{ $faq[0] }}</span>
                            <span>{{ $faq[1] }}</span>
                        </h3>
                        <p class="text-sm md:text-base text-gray-400">{{ $faq[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles untuk responsive */
    @media (max-width: 640px) {
        .sambal-card {
            padding: 1rem;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
        }
    }

    /* Animasi smooth untuk hover */
    .hover\:scale-\[1\.02\] {
        transition: transform 0.3s ease;
    }

    /* Line clamp untuk deskripsi produk */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush