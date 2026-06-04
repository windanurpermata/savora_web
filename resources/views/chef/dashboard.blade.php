@extends('chef.layout')
@section('title', 'Dashboard Chef')
@section('page-title', 'Dashboard Chef')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

    {{-- Greeting --}}
    <div class="mb-6">
        <h2 class="font-serif text-2xl text-cokelat-800">Halo, {{ $chef->name }}! 👨‍🍳</h2>
        <p class="text-sm text-cokelat-400 mt-1">Berikut ringkasan aktivitas resep kamu.</p>
    </div>

    {{-- Shortcut Buat Resep --}}
    <div class="mb-6">
        <a href="{{ route('chef.recipes.create') }}"
            class="inline-flex items-center gap-2 bg-cokelat-700 hover:bg-cokelat-800 text-white
               font-bold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Resep Baru
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Total Resep --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 uppercase tracking-wider font-bold">Total Resep</p>
                <div class="w-8 h-8 bg-cokelat-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-cokelat-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl text-cokelat-800">{{ $totalResep }}</p>
            <p class="text-xs text-cokelat-400 mt-1">resep dipublikasikan</p>
        </div>

        {{-- Total Rating --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 uppercase tracking-wider font-bold">Total Rating</p>
                <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl text-cokelat-800">{{ $totalRating }}</p>
            <p class="text-xs text-cokelat-400 mt-1">rating diterima</p>
        </div>

        {{-- Rata-rata Rating --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 uppercase tracking-wider font-bold">Rata-rata Rating</p>
                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl text-cokelat-800">{{ $rataRating ? number_format($rataRating, 1) : '—' }}</p>
            <p class="text-xs text-cokelat-400 mt-1">dari skala 5</p>
        </div>

        {{-- Resep Terpopuler --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 uppercase tracking-wider font-bold">Terpopuler</p>
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                </div>
            </div>
            @if ($resepTerpopuler)
                <p class="font-serif text-sm text-cokelat-800 font-bold leading-tight line-clamp-2">
                    {{ $resepTerpopuler->judul }}</p>
                <p class="text-xs text-cokelat-400 mt-1">{{ $resepTerpopuler->ratings_count }} rating</p>
            @else
                <p class="text-sm text-cokelat-400">Belum ada</p>
            @endif
        </div>

    </div>

    {{-- Grafik + Resep Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Grafik Rating --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-cokelat-100 p-5">
            <h3 class="font-serif text-base text-cokelat-800 mb-4">Grafik Rating (6 Bulan Terakhir)</h3>
            @if ($grafikRating->count() > 0)
                <canvas id="grafikRating" class="w-full" height="120"></canvas>
            @else
                <div class="flex items-center justify-center h-32 text-cokelat-400 text-sm">
                    Belum ada data rating.
                </div>
            @endif
        </div>

        {{-- Resep Rating Tertinggi --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <h3 class="font-serif text-base text-cokelat-800 mb-4">Rating Tertinggi ⭐</h3>
            @forelse ($resepTerbaik as $resep)
                <div class="flex items-center gap-3 py-2.5 border-b border-cokelat-50 last:border-0">
                    <div class="w-8 h-8 rounded-lg bg-cokelat-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-cokelat-600">{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-cokelat-800 truncate">{{ $resep->judul }}</p>
                        <p class="text-xs text-cokelat-400">{{ number_format($resep->ratings_avg_nilai, 1) }} ★ ·
                            {{ $resep->ratings_count }} rating</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-cokelat-400 text-center py-4">Belum ada rating.</p>
            @endforelse
        </div>

    </div>

    {{-- Resep Terbaru --}}
    <div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-cokelat-50 flex items-center justify-between">
            <h3 class="font-serif text-base text-cokelat-800">Resep Terbaru</h3>
            <a href="{{ route('chef.recipes.create') }}"
                class="text-xs text-cokelat-500 hover:text-cokelat-700 font-bold transition-colors">
                + Tambah Resep
            </a>
        </div>
        <div class="divide-y divide-cokelat-50">
            @forelse ($resepTerbaru as $resep)
                <div class="flex items-center gap-4 px-5 py-3">
                    {{-- Gambar --}}
                    <div class="w-12 h-12 rounded-lg bg-cokelat-100 flex-shrink-0 overflow-hidden">
                        @if ($resep->gambar)
                            <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-cokelat-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-cokelat-800 truncate">{{ $resep->judul }}</p>
                        <div class="flex items-center gap-3 mt-0.5">
                            <span class="text-xs text-cokelat-400">{{ $resep->category->nama ?? 'Tanpa Kategori' }}</span>
                            <span class="text-xs text-cokelat-300">·</span>
                            <span class="text-xs text-cokelat-400">{{ $resep->waktu_memasak }} menit</span>
                            @if ($resep->ratings_avg_nilai)
                                <span class="text-xs text-cokelat-300">·</span>
                                <span class="text-xs text-yellow-500 font-bold">★
                                    {{ number_format($resep->ratings_avg_nilai, 1) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tanggal + Aksi --}}
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-cokelat-400">{{ $resep->created_at->format('d M Y') }}</p>
                        <div class="flex items-center gap-2 mt-1 justify-end">
                            <a href="{{ route('recipes.show', $resep->id) }}"
                                class="text-xs text-cokelat-500 hover:text-cokelat-700 transition-colors">Lihat</a>
                            <span class="text-cokelat-200">|</span>
                            <a href="{{ route('chef.recipes.edit', $resep->id) }}"
                                class="text-xs text-cokelat-500 hover:text-cokelat-700 transition-colors">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-cokelat-400 text-sm mb-3">Belum ada resep. Yuk buat resep pertamamu!</p>
                    <a href="{{ route('chef.recipes.create') }}"
                        class="inline-flex items-center gap-1 bg-cokelat-700 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-cokelat-800 transition-colors">
                        + Buat Resep
                    </a>
                </div>
            @endforelse
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        @if ($grafikRating->count() > 0)
            const labels = @json($grafikRating->map(fn($r) => \Carbon\Carbon::createFromDate($r->tahun, $r->bulan, 1)->translatedFormat('M Y')));
            const dataRata = @json($grafikRating->map(fn($r) => round($r->rata, 2)));
            const dataTotal = @json($grafikRating->map(fn($r) => $r->total));

            const ctx = document.getElementById('grafikRating').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Rata-rata Rating',
                            data: dataRata,
                            borderColor: '#C8843A',
                            backgroundColor: 'rgba(200, 132, 58, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#C8843A',
                            pointRadius: 4,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Jumlah Rating',
                            data: dataTotal,
                            borderColor: '#B89070',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            tension: 0.4,
                            pointBackgroundColor: '#B89070',
                            pointRadius: 4,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 11
                                },
                                color: '#5C3317'
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            min: 0,
                            max: 5,
                            ticks: {
                                color: '#B89070',
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: 'rgba(184, 144, 112, 0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            ticks: {
                                color: '#B89070',
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#B89070',
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: 'rgba(184, 144, 112, 0.1)'
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
