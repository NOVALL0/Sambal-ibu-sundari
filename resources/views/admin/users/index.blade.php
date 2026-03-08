{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Users</h1>
    <p class="text-gray-400 mt-1">Manage registered users</p>
</div>

{{-- Search --}}
<div class="mb-6">
    <form method="GET" action="{{ url('/admin/users') }}" class="flex gap-2">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by name or email..."
               class="admin-input w-64">
        <button type="submit" class="btn-secondary">Search</button>
        <a href="{{ url('/admin/users') }}" class="btn-secondary">Reset</a>
    </form>
</div>

{{-- Users Table --}}
<div class="admin-card p-0 overflow-hidden">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Orders</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td class="font-medium">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role == 'admin' ? 'badge-danger' : 'badge-info' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>{{ $user->orders_count ?? 0 }}</td>
                
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-400">
                    No users found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $users->links() }}
</div>
@endsection