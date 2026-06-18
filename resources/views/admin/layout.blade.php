<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin DapurNusantara</title>
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
                <p class="text-xs text-cokelat-500 mt-0.5">Panel Admin</p>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="text-xs text-cokelat-600 uppercase tracking-wider px-4 mb-2">Menu</p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.dashboard')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                {{-- Kelola User (Super Admin Only) --}}
                @if(auth()->user()->email === 'admin@savora.com' || auth()->user()->email === 'windanur337@gmail.com' || str_contains(strtolower(auth()->user()->name), 'super'))
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.users.*')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Kelola User
                </a>
                @endif

                {{-- Kelola Resep --}}
                <a href="{{ route('admin.recipes.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.recipes.*')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Kelola Resep
                </a>

                {{-- Kategori (Super Admin Only) --}}
                @if(auth()->user()->email === 'admin@savora.com' || auth()->user()->email === 'windanur337@gmail.com' || str_contains(strtolower(auth()->user()->name), 'super'))
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.categories.*')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kategori
                </a>
                @endif

                {{-- Pesan Masuk --}}
                <a href="{{ route('admin.messages.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.messages.*')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Pesan Masuk
                    @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                    @if ($unread > 0)
                        <span
                            class="ml-auto bg-red-500 text-white text-xs w-5 h-5 rounded-full
                                 flex items-center justify-center font-bold flex-shrink-0">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </a>

                {{-- Newsletter --}}
                <a href="{{ route('admin.newsletter.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('admin.newsletter.*')
                          ? 'bg-cokelat-700 text-cokelat-50 font-bold'
                          : 'text-cokelat-300 hover:bg-cokelat-700 hover:text-cokelat-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Newsletter
                </a>

            </nav>

            {{-- User Info --}}
            <div class="px-4 py-4 border-t border-cokelat-700">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-cokelat-500 flex items-center
                            justify-center text-white text-xs font-bold flex-shrink-0 overflow-hidden">
                        @if (Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-cokelat-50 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-cokelat-500">Admin</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="block text-xs text-cokelat-400 hover:text-cokelat-50 mb-3 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Edit Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-xs text-cokelat-400 hover:text-cokelat-50
                               transition-colors text-left flex items-center gap-2">
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
                class="bg-white border-b border-cokelat-100 px-6 py-4 flex items-center
                       justify-between sticky top-0 z-30 shadow-sm">
                <h1 class="font-serif text-lg text-cokelat-800">@yield('page-title', 'Dashboard')</h1>
                <a href="{{ route('home') }}" target="_blank"
                    class="text-xs text-cokelat-400 hover:text-cokelat-600 transition-colors
                      flex items-center gap-1">
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