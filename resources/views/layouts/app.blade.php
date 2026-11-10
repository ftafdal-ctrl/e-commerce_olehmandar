<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'olehMandar') — Oleh-Oleh Khas Mandar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-mandar-600 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-horizontal.svg') }}" alt="OlehMandar" class="h-10 w-auto brightness-0 invert">
                </a>

                {{-- Navigation Links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-cream-50 hover:text-gold-400 text-sm font-medium transition">Beranda</a>
                    <a href="{{ route('products.index') }}" class="text-cream-50 hover:text-gold-400 text-sm font-medium transition">Produk</a>
                </div>

                {{-- Right Side --}}
                <div class="flex items-center gap-4">
                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative text-cream-50 hover:text-gold-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @php $cartCount = session('cart_count', 0); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-gold-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Auth --}}
                    @guest
                        <a href="{{ route('login') }}" class="text-cream-50 hover:text-gold-400 text-sm font-medium transition">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-secondary text-sm py-1.5 px-4">Daftar</a>
                    @else
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-cream-50 hover:text-gold-400 text-sm font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                {{ Auth::user()->name }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-cream-50 hover:text-mandar-600 rounded-t-lg">Pesanan Saya</a>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-cream-50 hover:text-mandar-600">Panel Admin</a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 max-w-7xl mx-auto w-full mt-4 rounded-r-lg flex items-center justify-between" role="alert">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 max-w-7xl mx-auto w-full mt-4 rounded-r-lg flex items-center justify-between" role="alert">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-mandar-900 text-cream-50 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <img src="{{ asset('images/logo-horizontal.svg') }}" alt="OlehMandar" class="h-10 w-auto brightness-0 invert mb-3">
                    <p class="text-cream-200 text-sm leading-relaxed">Toko oleh-oleh khas Mandar, menghadirkan cita rasa autentik Sulawesi Barat ke seluruh Indonesia.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Produk Unggulan</h4>
                    <ul class="space-y-1 text-sm text-cream-200">
                        <li><a href="{{ route('products.index') }}?category=baje" class="hover:text-gold-400 transition">Baje</a></li>
                        <li><a href="{{ route('products.index') }}?category=golla-kambu" class="hover:text-gold-400 transition">Golla Kambu</a></li>
                        <li><a href="{{ route('products.index') }}?category=kasippi" class="hover:text-gold-400 transition">Kasippi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3">Layanan</h4>
                    <ul class="space-y-1 text-sm text-cream-200">
                        <li><a href="{{ route('orders.index') }}" class="hover:text-gold-400 transition">Lacak Pesanan</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-gold-400 transition">Keranjang Belanja</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-mandar-700 mt-8 pt-6 text-center text-sm text-cream-200">
                &copy; {{ date('Y') }} olehMandar — Oleh-Oleh Khas Mandar. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
