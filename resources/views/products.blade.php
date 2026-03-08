{{-- resources/views/products.blade.php --}}
@extends('layouts.app')

@section('title', 'Products - Sambal Kitchen')

@section('content')


{{-- All Products Section --}}
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="spice-tag inline-block mb-4">🔥 PILIHAN SAMBAL KAMI</span>

            <h2 class="text-3xl font-semibold text-white-400 max-w-2xl mx-auto">Pilihan sesuai dengan seleramu !</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto mt-6"></div>
        </div>

        {{-- Products Grid --}}
        <div x-data="allProducts()" x-init="loadAllProducts()">
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-red-500 border-t-transparent"></div>
                <p class="text-gray-400 mt-4">Loading products...</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                <div class="sambal-card p-6 group hover:scale-105 transition-all duration-300">
                    <div class="relative mb-4 overflow-hidden rounded-xl">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1621243452912-99d7e96b5cf9?w=400&h=300&fit=crop' }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover group-hover:scale-110 transition duration-500">



                        <span class="absolute top-2 left-2 px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm border border-white/20
                        {{ $product->stock > 10 ? 'bg-green-600/40' : ($product->stock > 0 ? 'bg-yellow-600/40' : 'bg-red-600/40') }}">
                        {{ $product->stock > 0 ? $product->stock . ' pcs' : 'Habis' }}
                    </span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-red-500 transition">{{ $product->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $product->description ?? 'Sambal khas dengan cita rasa pedas mantap' }}</p>

                    <div class="text-2xl font-bold text-red-500 mb-4">Rp {{ number_format($product->price) }}</div>

                    <div class="flex space-x-2">
                        <form action="{{ route('cart.add') }}" method="POST" class="w-full sm:flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button
                                type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold py-4 px-10 rounded-full text-lg shadow-lg shadow-red-900/40 transition-all duration-300 flex items-center justify-center gap-3"
                            >
                                Beli
                            </button>
                        </form>

                        <!-- Tombol + Keranjang - outline merah -->
                        <form action="{{ route('cart.add') }}" method="POST" class="w-full sm:flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button
                                type="submit"
                                class="w-full bg-transparent border-2 border-red-600 text-red-500 hover:bg-red-950/30 hover:border-red-500 hover:text-red-400 active:bg-red-950/50 font-bold py-4 px-10 rounded-full text-lg transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                Keranjang
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-400">No products found</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>



@push('scripts')
<script>
    function featuredProducts() {
        return {
            featuredProducts: [],
            loading: true,
            quantity: 1,

            async loadFeaturedProducts() {
                this.loading = true;
                try {
                    const response = await axios.get('{{ url("/api/products") }}?limit=3');
                    this.featuredProducts = response.data.data || [];
                } catch (error) {
                    console.error('Error loading featured products:', error);
                } finally {
                    this.loading = false;
                }
            },

            showProductDetail(product) {
                this.selectedProduct = product;
                this.quantity = 1;
                this.showModal = true;
            },

            async addToCart(productId) {
                try {
                    await axios.post('{{ url("/api/cart/add") }}', {
                        product_id: productId,
                        quantity: 1
                    });

                    alert('Product added to cart!');
                    window.dispatchEvent(new CustomEvent('cart-updated'));

                } catch (error) {
                    if (error.response?.status === 401) {
                        if (confirm('Please login to add items to cart')) {
                            window.location.href = '{{ url("/login") }}';
                        }
                    } else {
                        alert('Error adding to cart: ' + (error.response?.data?.message || 'Unknown error'));
                    }
                }
            },

            buyNow(product) {
                if (!{{ auth()->check() ? 'true' : 'false' }}) {
                    if (confirm('Please login to buy products')) {
                        window.location.href = '{{ url("/login") }}?redirect=checkout';
                    }
                    return;
                }

                window.location.href = '{{ url("/checkout") }}?product_id=' + product.id + '&quantity=1';
            }
        }
    }

    function allProducts() {
        return {
            loading: false,
            // Add any all products specific functions here
        }
    }
</script>
@endpush
@endsection