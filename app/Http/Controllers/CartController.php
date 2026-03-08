<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart
     */
    public function index()
    {
        $cart = Auth::user()->cart()->with('items.product')->firstOrCreate();

        $cartItems = $cart->items;
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'subtotal'));
    }

    /**
     * API: Ambil data cart
     */
    public function apiIndex()
    {
        $cart = Auth::user()->cart()->with('items.product')->firstOrCreate();

        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'items' => $cart->items,
                'items_count' => $cart->items->count(),
                'subtotal' => $cart->items->sum(function($item) {
                    return $item->price * $item->quantity;
                })
            ]
        ]);
    }

    /**
     * Tambah item ke cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = Auth::user()->cart()->firstOrCreate();

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price
            ]);
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang',
                'data' => [
                    'cart_item' => $cartItem->load('product'),
                    'items_count' => $cart->items->count()
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Update quantity item
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        if ($cartItem->product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui',
            'data' => [
                'cart_item' => $cartItem->fresh('product'),
                'subtotal' => $cartItem->price * $cartItem->quantity
            ]
        ]);
    }

    /**
     * Hapus item dari cart
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang'
        ]);
    }

    /**
     * Kosongkan cart
     */
    public function clear()
    {
        $cart = Auth::user()->cart;
        $cart->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang dikosongkan'
        ]);
    }

    /**
     * Hitung jumlah item di cart
     */
    public function count()
    {
        $count = Auth::user()->cart->items->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}