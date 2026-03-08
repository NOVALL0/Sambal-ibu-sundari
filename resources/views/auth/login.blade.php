{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - Sambal Kitchen')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">

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

            <p class="text-gray-400">Login ke dapur sambal kamu</p>
        </div>

        <div class="sambal-card p-8">
            <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <div class="space-y-6">
                    {{-- Email --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2 tracking-wide hover:text-gray-100 transition duration-200">
                            Email
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2.5 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200 @error('email') border-red-500 @enderror"
                               placeholder="your@email.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2 tracking-wide hover:text-gray-100 transition duration-200">
                            Password
                        </label>
                        <input type="password"
                               name="password"
                               class="w-full px-3 py-2.5 bg-gray-700 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-20 transition duration-200 @error('password') border-red-500 @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-300 hover:text-gray-100 transition duration-200">
                            <input type="checkbox" name="remember" class="mr-2 rounded bg-gray-700 border-gray-600 text-red-500 focus:ring-red-500 focus:ring-offset-0">
                            Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-red-500 hover:text-red-400 hover:underline transition duration-200">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-md transition duration-200 ease-in-out hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading">
                        <span x-show="!loading">Login 🔥</span>
                        <span x-show="loading">Loading...</span>
                    </button>

                    {{-- Register Link --}}
                    <p class="text-center text-gray-400 text-sm">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-red-500 hover:text-red-400 hover:underline transition duration-200">
                            Register disini
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection