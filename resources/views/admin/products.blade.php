{{-- resources/views/admin/products.blade.php --}}
@extends('layouts.admin')

@section('title', 'Products - Admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-white">Products</h1>
        <p class="text-gray-400 mt-1">Manage your sambal products</p>
    </div>
    <button class="sambal-btn-primary px-6 py-3">
        + Add New Product
    </button>
</div>

<div class="admin-card p-6">
    <table class="w-full">
        <thead>
            <tr class="text-gray-400 text-sm border-b border-gray-700">
                <th class="text-left py-3">Product</th>
                <th class="text-left py-3">Variant</th>
                <th class="text-left py-3">Price</th>
                <th class="text-left py-3">Stock</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @for($i=1; $i<=5; $i++)
            <tr class="border-b border-gray-800">
                <td class="py-3">
                    <div class="flex items-center space-x-3">
                        <img src="https://images.unsplash.com/photo-1621243452912-99d7e96b5cf9?w=50&h=50&fit=crop"
                             class="w-10 h-10 rounded-lg object-cover">
                        <span class="text-white">Sambal Tuna {{ $i }}</span>
                    </div>
                </td>
                <td class="py-3">
                    <span class="px-2 py-1 bg-red-500/20 text-red-500 rounded-full text-xs">Tuna</span>
                </td>
                <td class="py-3 text-white">Rp {{ rand(30, 50) }}k</td>
                <td class="py-3">
                    <span class="{{ rand(0,1) ? 'text-green-500' : 'text-red-500' }}">
                        {{ rand(0, 100) }} pcs
                    </span>
                </td>
                <td class="py-3">
                    <span class="px-2 py-1 {{ rand(0,1) ? 'bg-green-500/20 text-green-500' : 'bg-gray-500/20 text-gray-400' }} rounded-full text-xs">
                        {{ rand(0,1) ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="py-3">
                    <button class="text-blue-500 hover:text-blue-400 mr-2">✏️</button>
                    <button class="text-red-500 hover:text-red-400">🗑️</button>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection