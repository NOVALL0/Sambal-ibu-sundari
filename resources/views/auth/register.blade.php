{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register - Sambal Kitchen')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            {{-- Container utama --}}
            <div class="flex flex-col items-center">

                <div class="relative group mb-6">



                    {{-- Logo --}}
                    <div class="">
                        <img src="{{ asset('image/logo.png') }}"
                             alt="Logo Sambal Ibu Sundari"
                             class="w-70 h-70 object-contain  transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                    </div>


                {{-- Teks dengan efek --}}
                <h1 class="text-4xl font-bold bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 bg-clip-text text-transparent animate-gradient">
                    Sambal Ibu Sundari

                </h1>




            </div>
        </div>



        <div class="sambal-card p-8">
            {{-- Tampilkan error session jika ada --}}
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500 rounded-lg text-red-500 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tampilkan success session jika ada --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-500/20 border border-green-500 rounded-lg text-green-500 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" x-data="registerForm()" @submit.prevent="submitForm">
                @csrf

                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text"
                               name="name"
                               x-model="form.name"
                               @input="clearError('name')"
                               class="w-full px-3 py-2.5 bg-gray-700 border rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200"
                               :class="{
                                   'border-gray-600': !errors.name,
                                   'border-red-500': errors.name,
                                   'opacity-50': loading
                               }"
                               placeholder="John Doe"
                               :disabled="loading">
                        <template x-if="errors.name">
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1" x-text="errors.name"></p>
                        </template>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">
                            Email
                        </label>
                        <input type="email"
                               name="email"
                               x-model="form.email"
                               @input="clearError('email')"
                               class="w-full px-3 py-2.5 bg-gray-700 border rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200"
                               :class="{
                                   'border-gray-600': !errors.email,
                                   'border-red-500': errors.email,
                                   'opacity-50': loading
                               }"
                               placeholder="your@email.com"
                               :disabled="loading">
                        <template x-if="errors.email">
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1" x-text="errors.email"></p>
                        </template>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">
                            Password
                        </label>
                        <input type="password"
                               name="password"
                               x-model="form.password"
                               @input="clearError('password')"
                               class="w-full px-3 py-2.5 bg-gray-700 border rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200"
                               :class="{
                                   'border-gray-600': !errors.password,
                                   'border-red-500': errors.password,
                                   'opacity-50': loading
                               }"
                               placeholder="Min. 8 karakter"
                               :disabled="loading">
                        <template x-if="errors.password">
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1" x-text="errors.password"></p>
                        </template>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               x-model="form.password_confirmation"
                               @input="clearError('password_confirmation')"
                               class="w-full px-3 py-2.5 bg-gray-700 border rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200"
                               :class="{
                                   'border-gray-600': !errors.password_confirmation,
                                   'border-red-500': errors.password_confirmation,
                                   'opacity-50': loading
                               }"
                               placeholder="••••••••"
                               :disabled="loading">
                        <template x-if="errors.password_confirmation">
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1" x-text="errors.password_confirmation"></p>
                        </template>
                    </div>

                    {{-- Terms --}}
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="terms"
                                   x-model="form.terms"
                                   class="w-4 h-4 bg-gray-700 border-gray-600 rounded text-red-500 focus:ring-red-500 focus:ring-offset-0"
                                   :disabled="loading">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="text-gray-300">
                                Saya setuju dengan
                                <a href="#" class="text-red-500 hover:text-red-400 hover:underline transition duration-200">
                                    Syarat & Ketentuan
                                </a>
                            </span>
                        </div>
                    </div>
                    <template x-if="errors.terms">
                        <p class="text-red-500 text-sm mt-1" x-text="errors.terms"></p>
                    </template>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-md transition duration-200 ease-in-out hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading">
                        <span x-show="!loading" class="flex items-center justify-center gap-2">
                            Register Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                        <span x-show="loading" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>

                    {{-- Login Link --}}
                    <p class="text-center text-gray-400 text-sm">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 hover:underline font-medium transition duration-200">
                            Login disini
                        </a>
                    </p>
                </div>
            </form>
        </div>

       
    </div>
</div>

@push('scripts')
<script>
    function registerForm() {
        return {
            loading: false,
            form: {
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
                password: '',
                password_confirmation: '',
                terms: false
            },
            errors: {},

            validateForm() {
                this.errors = {};

                // Validasi nama
                if (!this.form.name || this.form.name.trim() === '') {
                    this.errors.name = 'Nama lengkap harus diisi';
                } else if (this.form.name.trim().length < 3) {
                    this.errors.name = 'Nama minimal 3 karakter';
                }

                // Validasi email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.form.email) {
                    this.errors.email = 'Email harus diisi';
                } else if (!emailRegex.test(this.form.email)) {
                    this.errors.email = 'Format email tidak valid';
                }

                // Validasi password
                if (!this.form.password) {
                    this.errors.password = 'Password harus diisi';
                } else if (this.form.password.length < 8) {
                    this.errors.password = 'Password minimal 8 karakter';
                }

                // Validasi konfirmasi password
                if (!this.form.password_confirmation) {
                    this.errors.password_confirmation = 'Konfirmasi password harus diisi';
                } else if (this.form.password !== this.form.password_confirmation) {
                    this.errors.password_confirmation = 'Password tidak cocok';
                }

                // Validasi terms
                if (!this.form.terms) {
                    this.errors.terms = 'Anda harus menyetujui syarat & ketentuan';
                }

                return Object.keys(this.errors).length === 0;
            },

            clearError(field) {
                if (this.errors[field]) {
                    delete this.errors[field];
                }
            },

            async submitForm() {
                if (!this.validateForm()) {
                    return;
                }

                this.loading = true;

                // Submit form biasa (bukan AJAX)
                // Karena form ini menggunakan method POST biasa
                document.querySelector('form').submit();
            }
        }
    }
</script>
@endpush
@endsection