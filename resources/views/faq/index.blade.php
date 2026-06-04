@extends('layouts.app')
@section('title', 'FAQ')

@section('content')

    {{-- Header --}}
    <section class="bg-cokelat-800 py-12">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <span
                class="inline-block bg-cokelat-500/20 text-yellow-300 text-xs px-4 py-1.5
                     rounded-full mb-4 tracking-widest font-bold uppercase">
                Pusat Bantuan
            </span>
            <h1 class="font-serif text-3xl md:text-4xl text-cokelat-50 mb-3">
                Pertanyaan yang Sering Ditanyakan
            </h1>
            <p class="text-cokelat-300 text-sm">
                Temukan jawaban atas pertanyaan umum seputar Savora
            </p>
        </div>
    </section>

    {{-- FAQ Content --}}
    <section class="py-12 bg-cokelat-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            @foreach ($faqGroups as $group)
                <div class="mb-10">

                    {{-- Group Header --}}
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-2xl">{{ $group['emoji'] }}</span>
                        <h2 class="font-serif text-xl text-cokelat-800">{{ $group['title'] }}</h2>
                    </div>

                    {{-- FAQ Items --}}
                    <div class="space-y-3">
                        @foreach ($group['items'] as $i => $faq)
                            <div class="bg-white border border-cokelat-100 rounded-xl overflow-hidden"
                                id="faq-{{ $loop->parent->index }}-{{ $i }}">

                                <button onclick="toggleFaq('faq-{{ $loop->parent->index }}-{{ $i }}')"
                                    class="w-full flex items-center justify-between gap-4
                                           px-5 py-4 text-left">
                                    <span class="text-sm font-bold text-cokelat-800 leading-snug">
                                        {{ $faq['q'] }}
                                    </span>
                                    <div
                                        class="faq-icon w-6 h-6 rounded-full bg-cokelat-50
                                            border border-cokelat-200 flex items-center justify-center
                                            text-cokelat-500 flex-shrink-0 transition-all duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </button>

                                <div class="faq-answer hidden px-5 pb-4 border-t border-cokelat-50">
                                    <p class="text-sm text-cokelat-500 leading-relaxed pt-3">
                                        {{ $faq['a'] }}
                                    </p>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach

            {{-- CTA ke Contact --}}
            <div class="bg-cokelat-800 rounded-2xl p-8 text-center mt-4">
                <p class="font-serif text-xl text-cokelat-50 mb-2">
                    Masih ada pertanyaan?
                </p>
                <p class="text-cokelat-300 text-sm mb-6">
                    Tim admin kami siap membantu Anda. Kirim pesan dan kami akan merespons dalam 1x24 jam.
                </p>
                <a href="{{ route('contact') }}"
                    class="inline-block bg-cokelat-500 hover:bg-cokelat-400 text-white
                      font-bold px-6 py-3 rounded-lg text-sm transition-colors">
                    Hubungi Admin →
                </a>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function toggleFaq(id) {
            const item = document.getElementById(id);
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');
            const isOpen = !answer.classList.contains('hidden');

            answer.classList.toggle('hidden', isOpen);
            icon.classList.toggle('bg-cokelat-500', !isOpen);
            icon.classList.toggle('text-white', !isOpen);
            icon.classList.toggle('border-cokelat-500', !isOpen);
            icon.classList.toggle('rotate-45', !isOpen);
        }
    </script>
@endpush