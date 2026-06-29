<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Chef DapurNusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap"
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

<body class="bg-cokelat-50 text-cokelat-800">

    <div class="flex min-h-screen">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="w-56 bg-cokelat-800 flex-shrink-0 flex flex-col fixed h-full z-40">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-cokelat-700">
                <a href="{{ route('home') }}" class="font-serif text-lg text-cokelat-50 block">
                    Dapur<span class="text-cokelat-500">Nusantara</span>
                </a>
                <p class="text-xs text-cokelat-500 mt-0.5">Panel Chef</p>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="text-xs text-cokelat-600 uppercase tracking-wider px-4 mb-2">Menu</p>

                <a href="{{ route('contributor.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('contributor.dashboard') ? 'bg-cokelat-700 text-cokelat-50 font-bold' : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('contributor.recipes.create') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('contributor.recipes.create') ? 'bg-cokelat-700 text-cokelat-50 font-bold' : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Resep
                </a>

                <a href="{{ route('contributor.profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('contributor.profile.*') ? 'bg-cokelat-700 text-cokelat-50 font-bold' : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Saya
                </a>
            </nav>

            {{-- User Info --}}
            <div class="px-4 py-4 border-t border-cokelat-700">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-cokelat-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-cokelat-50 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-cokelat-500">Chef</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-xs text-cokelat-400 hover:text-cokelat-50 transition-colors text-left flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- ===== KONTEN UTAMA ===== --}}
        <div class="flex-1 ml-56 min-w-0">

            {{-- Topbar --}}
            <header
                class="bg-white border-b border-cokelat-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <h1 class="font-serif text-lg text-cokelat-800">@yield('page-title', 'Dashboard')</h1>
                <a href="{{ route('home') }}" target="_blank"
                    class="text-xs text-cokelat-400 hover:text-cokelat-600 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Lihat Website
                </a>
            </header>

            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-green-50 border-b border-green-200 text-green-700 px-6 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-b border-red-200 text-red-700 px-6 py-3 text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <main class="p-6">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>

</html>
