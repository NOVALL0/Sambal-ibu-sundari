]{{-- resources/views/admin/users.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Users - Sambal Kitchen')

@section('content')
<div class="container mx-auto px-6 py-12" x-data="adminUsers()" x-init="loadUsers()">
    <div class="mb-8">
        <span class="spice-tag inline-block mb-4">👥 USERS</span>
        <h1 class="text-4xl font-bold text-white">Manage Users</h1>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <input type="text"
               x-model="search"
               @keyup.enter="loadUsers()"
               placeholder="Search users..."
               class="sambal-input w-64">
    </div>

    {{-- Users Table --}}
    <div class="sambal-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-gray-400 text-sm border-b border-gray-700">
                        <th class="text-left py-3">ID</th>
                        <th class="text-left py-3">Name</th>
                        <th class="text-left py-3">Email</th>
                        <th class="text-left py-3">Role</th>
                        <th class="text-left py-3">Joined</th>
                        <th class="text-left py-3">Orders</th>
                        <th class="text-left py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in users" :key="user.id">
                        <tr class="border-b border-gray-800 hover:bg-[#262626]">
                            <td class="py-3 text-white" x-text="user.id"></td>
                            <td class="py-3 text-white" x-text="user.name"></td>
                            <td class="py-3 text-gray-400" x-text="user.email"></td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs"
                                      :class="user.role === 'admin' ? 'bg-red-500/20 text-red-500' : 'bg-blue-500/20 text-blue-500'"
                                      x-text="user.role"></span>
                            </td>
                            <td class="py-3 text-gray-400" x-text="new Date(user.created_at).toLocaleDateString('id-ID')"></td>
                            <td class="py-3 text-white" x-text="user.orders_count || 0"></td>
                            <td class="py-3">
                                <button @click="toggleRole(user)" class="text-blue-500 hover:text-blue-400 mr-2">
                                    🔄 Toggle Role
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function adminUsers() {
        return {
            users: [],
            search: '',

            async loadUsers() {
                try {
                    let url = '/api/admin/users';
                    if (this.search) url += '?search=' + this.search;

                    const response = await axios.get(url);
                    this.users = response.data.data.data || [];
                } catch (error) {
                    console.error('Error loading users:', error);
                }
            },

            async toggleRole(user) {
                if (!confirm(`Change ${user.name}'s role from ${user.role} to ${user.role === 'admin' ? 'user' : 'admin'}?`)) return;

                try {
                    await axios.put(`/api/admin/users/${user.id}/role`, {
                        role: user.role === 'admin' ? 'user' : 'admin'
                    });
                    await this.loadUsers();
                    alert('Role updated successfully');
                } catch (error) {
                    alert('Error updating role');
                }
            }
        }
    }
</script>
@endpush
@endsection