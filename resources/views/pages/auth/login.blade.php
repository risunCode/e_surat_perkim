@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-2xl p-8 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Masuk ke akun Anda</h2>
    
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 bg-gray-50 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/20 focus:border-transparent"
                placeholder="nama@email.com">
            @error('email')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input type="password" name="password" id="password" required
                class="w-full px-4 py-3 bg-gray-50 dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/20 focus:border-transparent"
                placeholder="••••••••">
            @error('password')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="w-4 h-4 bg-gray-50 dark:bg-neutral-800 border-gray-300 dark:border-neutral-700 rounded text-black dark:text-white focus:ring-black/20 dark:focus:ring-white/20">
                <span class="text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
            </label>
        </div>
        
        <button type="submit" class="w-full py-3 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors">
            Masuk
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-black dark:text-white hover:underline font-medium">Daftar</a>
        </p>
    </div>
</div>
@endsection
