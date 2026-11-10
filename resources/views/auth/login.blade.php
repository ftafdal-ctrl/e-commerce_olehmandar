@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo.svg') }}" alt="OlehMandar" class="h-24 w-auto mx-auto">
            </a>
            <h2 class="mt-4 text-2xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
            <p class="text-gray-500 text-sm mt-1">Belum punya akun? <a href="{{ route('register') }}" class="text-mandar-600 hover:underline font-medium">Daftar sekarang</a></p>
        </div>

        <div class="card p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 focus:border-transparent @error('email') border-red-400 @enderror"
                           placeholder="email@anda.com" required>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 focus:border-transparent"
                           placeholder="Password Anda" required>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-mandar-600 focus:ring-mandar-500">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary justify-center">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
