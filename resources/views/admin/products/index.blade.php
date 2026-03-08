{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white">Products</h1>
        <p class="text-gray-400 mt-1">Manage your sambal products</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        + Add New Product
    </a>
</div>

{{-- Search & Filter --}}
<div class="mb-6 flex flex-col md:flex-row gap-4">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 flex gap-2">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search products..."
               class="admin-input flex-1">

        <select name="variant" class="admin-input w-32">
            <option value="">All</option>
            <option value="Tuna" {{ request('variant') == 'Tuna' ? 'selected' : '' }}>Tuna</option>
            <option value="Cumi" {{ request('variant') == 'Cumi' ? 'selected' : '' }}>Cumi</option>
            <option value="Bawang" {{ request('variant') == 'Bawang' ? 'selected' : '' }}>Bawang</option>
        </select>

        <select name="stock_status" class="admin-input w-32">
            <option value="">All Stock</option>
            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
        </select>

        <button type="submit" class="btn-secondary">Filter</button>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">Reset</a>
    </form>
</div>

{{-- Products Table --}}
<div class="admin-card p-0 overflow-hidden">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="w-10">
                    <input type="checkbox" id="select-all">
                </th>
                <th>Image</th>
                <th>Name</th>
                <th>Variant</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                </td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-lg object-cover">
                    @else
                        <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                            📸
                        </div>
                    @endif
                </td>
                <td class="font-medium">{{ $product->name }}</td>
                <td>
                    <span class="badge" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                        {{ $product->variant }}
                    </span>
                </td>
                <td>Rp {{ number_format($product->price) }}</td>
                <td>
                    <span class="{{ $product->stock < 10 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $product->stock }} pcs
                    </span>
                </td>
                <td>
                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-blue-500 hover:text-blue-400 transition"
                           title="Edit">
                            ✏️
                        </a>
                        <form method="POST"
                              action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Delete this product?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:text-red-400 transition"
                                    title="Delete">
                                🗑️
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-400">
                    No products found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $products->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
    });
</script>
@endpush
@endsection