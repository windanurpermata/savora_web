<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Savora') - Resep Masakan Terlengkap</title>

    {{-- Tailwind CSS via CDN (ganti dengan Vite jika sudah setup) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cokelat: {
                            50: '#F5F0E8',
                            100: '#E8DDD0',
                            200: '#D4BFA0',
                            300: '#B89070',
                            400: '#A0662A',
                            500: '#C8843A',
                            600: '#7A4520',
                            700: '#5C3317',
                            800: '#3B1F0D',
                            900: '#2A1508',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Lato', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-cokelat-50 text-cokelat-800 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="bg-cokelat-800 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="font-serif text-xl text-cokelat-50">
                        Sav<span class="text-cokelat-500">ora</span>
                    </span>
                </a>

                {{-- Menu Desktop --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}"
                        class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors
                              {{ request()->routeIs('home') ? 'text-cokelat-50 font-bold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('recipes.index') }}"
                        class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors
                              {{ request()->routeIs('recipes.*') ? 'text-cokelat-50 font-bold' : '' }}">
                        Resep
                    </a>
                    <a href="#chef" class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                        Contributor
                    </a>
                    <a href="#kategori" class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                        Kategori
                    </a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        {{-- Sudah login --}}
                        <span class="text-cokelat-200 text-sm">
                            Hai, {{ Auth::user()->name }}
                        </span>

                        <a href="{{ route('profile.edit') }}"
                            class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                            Edit Profil
                        </a>

                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                                Dashboard
                            </a>
                        @elseif(Auth::user()->role === 'chef')
                            <a href="{{ route('chef.dashboard') }}"
                                class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('bookmarks.index') }}"
                                class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                                Bookmark
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        {{-- Belum login --}}
                        <a href="{{ route('login') }}"
                            class="text-cokelat-200 hover:text-cokelat-50 text-sm transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-cokelat-500 hover:bg-cokelat-400 text-white text-sm
                                  px-4 py-2 rounded-lg transition-colors font-bold">
                            Daftar Gratis
                        </a>
                    @endauth
                </div>

                {{-- Hamburger Mobile --}}
                <button id="menu-toggle" class="md:hidden text-cokelat-200 hover:text-cokelat-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </div>
        </div>

        {{-- Menu Mobile --}}
        <div id="mobile-menu" class="hidden md:hidden bg-cokelat-900 px-4 pb-4">
            <div class="flex flex-col gap-3 pt-3">
                <a href="{{ route('home') }}" class="text-cokelat-200 text-sm">Beranda</a>
                <a href="{{ route('recipes.index') }}" class="text-cokelat-200 text-sm">Resep</a>
                <a href="#chef" class="text-cokelat-200 text-sm">Contributor</a>
                <a href="#kategori" class="text-cokelat-200 text-sm">Kategori</a>
                <hr class="border-cokelat-700">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-cokelat-200 text-sm">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-cokelat-200 text-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-cokelat-200 text-sm">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-cokelat-500 text-white text-sm px-4 py-2 rounded-lg text-center font-bold">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ===================== FLASH MESSAGE ===================== --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 text-sm text-center">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 text-sm text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    {{-- ===================== FOOTER ===================== --}}
    <footer class="mt-16">

        {{-- Bagian Atas --}}
        <div class="bg-cokelat-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                    {{-- Kolom 1: Brand + Sosmed + Newsletter --}}
                    <div class="md:col-span-1">
                        <p class="font-serif text-xl text-cokelat-50 mb-3">
                            Sav<span class="text-cokelat-500">ora</span>
                        </p>
                        <p class="text-xs text-cokelat-400 leading-relaxed mb-5 max-w-xs">
                            Platform resep masakan terpercaya, dikurasi langsung oleh Contributor berpengalaman.
                        </p>

                        {{-- Sosial Media --}}
                        <div class="flex gap-2 mb-5">
                            <a href="#" aria-label="Instagram"
                                class="w-8 h-8 rounded-full bg-cokelat-700 border border-cokelat-600
                                      flex items-center justify-center text-cokelat-500
                                      hover:text-cokelat-50 hover:border-cokelat-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691
                                             4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012
                                             3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058
                                             -1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149
                                             -4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849
                                             0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919
                                             1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014
                                             -4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073
                                             1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618
                                             6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668
                                             -.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28
                                             .073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196
                                             -4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949
                                             -.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163
                                             6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162
                                             -6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4
                                             4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0
                                             -1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439
                                             -.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="Facebook"
                                class="w-8 h-8 rounded-full bg-cokelat-700 border border-cokelat-600
                                      flex items-center justify-center text-cokelat-500
                                      hover:text-cokelat-50 hover:border-cokelat-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99
                                             4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0
                                             -3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953
                                             H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796
                                             v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="YouTube"
                                class="w-8 h-8 rounded-full bg-cokelat-700 border border-cokelat-600
                                      flex items-center justify-center text-cokelat-500
                                      hover:text-cokelat-50 hover:border-cokelat-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545
                                             12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502
                                             6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0
                                             2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015
                                             3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545
                                             15.568V8.432L15.818 12l-6.273 3.568z" />
                                </svg>
                            </a>
                            <a href="#" aria-label="TikTok"
                                class="w-8 h-8 rounded-full bg-cokelat-700 border border-cokelat-600
                                      flex items-center justify-center text-cokelat-500
                                      hover:text-cokelat-50 hover:border-cokelat-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75
                                             4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35
                                             -4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75
                                             -.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21
                                             -1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71
                                             -.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44
                                             3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15
                                             -.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14
                                             1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61
                                             .19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01
                                             -8.05.02-12.07z" />
                                </svg>
                            </a>
                        </div>

                        {{-- Newsletter --}}
                        <div class="bg-cokelat-700 border border-cokelat-600 rounded-xl p-4">
                            <p class="text-xs font-bold text-cokelat-50 mb-1">
                                📬 Newsletter Mingguan
                            </p>
                            <p class="text-xs text-cokelat-400 mb-3 leading-relaxed">
                                Resep pilihan Contributor langsung ke inbox Anda setiap minggu.
                            </p>
                            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex gap-2">
                                @csrf
                                <input type="email" name="email" placeholder="Email Anda..."
                                    class="flex-1 bg-cokelat-800 border border-cokelat-600 rounded-lg
                                              px-3 py-2 text-xs text-cokelat-50 placeholder-cokelat-600
                                              outline-none focus:border-cokelat-500 transition-colors">
                                <button type="submit"
                                    class="bg-cokelat-500 hover:bg-cokelat-400 text-white text-xs
                                               font-bold px-3 py-2 rounded-lg transition-colors flex-shrink-0">
                                    Daftar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Kolom 2: Jelajahi --}}
                    <div>
                        <p
                            class="text-xs font-bold text-cokelat-50 mb-4 pb-2
                                  border-b border-cokelat-700 uppercase tracking-wider">
                            Jelajahi
                        </p>
                        <ul class="space-y-2.5">
                            @foreach ([['route' => 'recipes.index', 'label' => 'Semua Resep'], ['route' => 'recipes.index', 'label' => 'Resep Populer', 'params' => ['sort' => 'populer']], ['route' => 'recipes.index', 'label' => 'Resep Terbaru', 'params' => ['sort' => 'terbaru']]] as $link)
                                <li>
                                    <a href="{{ route($link['route'], $link['params'] ?? []) }}"
                                        class="text-xs text-cokelat-400 hover:text-cokelat-50
                                              transition-colors flex items-center gap-2">
                                        <span class="text-cokelat-600 text-xs">›</span>
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <p
                            class="text-xs font-bold text-cokelat-50 mt-5 mb-4 pb-2
                                  border-b border-cokelat-700 uppercase tracking-wider">
                            Informasi
                        </p>
                        <ul class="space-y-2.5">
                            <li>
                                <a href="{{ route('faq') }}"
                                    class="text-xs text-cokelat-400 hover:text-cokelat-50
                                          transition-colors flex items-center gap-2">
                                    <span class="text-cokelat-600 text-xs">›</span> FAQ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}"
                                    class="text-xs text-cokelat-400 hover:text-cokelat-50
                                          transition-colors flex items-center gap-2">
                                    <span class="text-cokelat-600 text-xs">›</span> Hubungi Kami
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="text-xs text-cokelat-400 hover:text-cokelat-50
                                          transition-colors flex items-center gap-2">
                                    <span class="text-cokelat-600 text-xs">›</span> Kebijakan Privasi
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kolom 3: Akun --}}
                    <div>
                        <p
                            class="text-xs font-bold text-cokelat-50 mb-4 pb-2
                                  border-b border-cokelat-700 uppercase tracking-wider">
                            Akun
                        </p>
                        <ul class="space-y-2.5 mb-5">
                            @guest
                                <li>
                                    <a href="{{ route('login') }}"
                                        class="text-xs text-cokelat-400 hover:text-cokelat-50
                                              transition-colors flex items-center gap-2">
                                        <span class="text-cokelat-600 text-xs">›</span> Masuk
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}"
                                        class="text-xs text-cokelat-400 hover:text-cokelat-50
                                              transition-colors flex items-center gap-2">
                                        <span class="text-cokelat-600 text-xs">›</span> Daftar Member
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}?role=chef"
                                        class="text-xs text-cokelat-400 hover:text-cokelat-50
                                              transition-colors flex items-center gap-2">
                                        <span class="text-cokelat-600 text-xs">›</span> Daftar Contributor
                                    </a>
                                </li>
                            @endguest
                            @auth
                                @if (Auth::user()->role === 'member')
                                    <li>
                                        <a href="{{ route('bookmarks.index') }}"
                                            class="text-xs text-cokelat-400 hover:text-cokelat-50
                                                  transition-colors flex items-center gap-2">
                                            <span class="text-cokelat-600 text-xs">›</span> Bookmark Saya
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->role === 'chef')
                                    <li>
                                        <a href="{{ route('chef.dashboard') }}"
                                            class="text-xs text-cokelat-400 hover:text-cokelat-50
                                                  transition-colors flex items-center gap-2">
                                            <span class="text-cokelat-600 text-xs">›</span> Dashboard Contributor
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>

                        <p
                            class="text-xs font-bold text-cokelat-50 mb-3 pb-2
                                  border-b border-cokelat-700 uppercase tracking-wider">
                            Dukungan
                        </p>
                        <p class="text-xs text-cokelat-400 leading-relaxed">
                            Senin – Jumat<br>08.00 – 17.00 WIB
                        </p>
                        <a href="{{ route('contact') }}"
                            class="text-xs text-cokelat-500 hover:text-cokelat-400
                                  transition-colors mt-2 inline-block">
                            Tanya Admin →
                        </a>
                    </div>

                    {{-- Kolom 4: Kategori --}}
                    <div>
                        <p
                            class="text-xs font-bold text-cokelat-50 mb-4 pb-2
                                  border-b border-cokelat-700 uppercase tracking-wider">
                            Kategori Resep
                        </p>
                        <ul class="space-y-2.5">
                            @foreach ([['emoji' => '🌅', 'nama' => 'Sarapan'], ['emoji' => '🍲', 'nama' => 'Sup & Soto'], ['emoji' => '🥩', 'nama' => 'Daging'], ['emoji' => '🐟', 'nama' => 'Seafood'], ['emoji' => '🥗', 'nama' => 'Sayuran'], ['emoji' => '🍰', 'nama' => 'Kue & Dessert'], ['emoji' => '☕', 'nama' => 'Minuman']] as $kat)
                                <li>
                                    <a href="{{ route('recipes.index') }}"
                                        class="text-xs text-cokelat-400 hover:text-cokelat-50
                                              transition-colors flex items-center gap-2">
                                        <span>{{ $kat['emoji'] }}</span> {{ $kat['nama'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        {{-- Bottom Bar --}}
        <div class="bg-cokelat-900 py-4">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                        flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-cokelat-600">
                    © {{ date('Y') }} <span class="text-cokelat-500">Savora</span>
                    — Dibuat dengan ❤️ untuk seluruh keluarga Indonesia
                </p>
                <div class="flex gap-5">
                    <a href="#" class="text-xs text-cokelat-600 hover:text-cokelat-400 transition-colors">
                        Kebijakan Privasi
                    </a>
                    <a href="#" class="text-xs text-cokelat-600 hover:text-cokelat-400 transition-colors">
                        Syarat & Ketentuan
                    </a>
                    <a href="{{ route('contact') }}"
                        class="text-xs text-cokelat-600 hover:text-cokelat-400 transition-colors">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Toggle Mobile Menu --}}
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>

</html>
