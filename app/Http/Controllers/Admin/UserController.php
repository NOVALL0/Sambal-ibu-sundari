<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }


    public function destroy(User $user)
{
    // Cek apakah user mencoba menghapus diri sendiri
    if ($user->id === auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat menghapus akun sendiri'
        ], 403);
    }

    try {
        // Hapus cart user terlebih dahulu
        if ($user->cart) {
            $user->cart->items()->delete();
            $user->cart->delete();
        }

        // Hapus orders terkait (opsional, bisa di-set null atau cascade)
        // $user->orders()->delete();

        // Hapus user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus user: ' . $e->getMessage()
        ], 500);
    }
}
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        // Prevent user from changing their own role
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully');
    }
}