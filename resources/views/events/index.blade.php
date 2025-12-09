@php
    use Carbon\Carbon;

    // daftar bulan
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    // bulan & tahun aktif
    $currentMonth = request('month', now()->month);
    $currentYear = request('year', now()->year);

    $date = Carbon::create($currentYear, $currentMonth, 1);

    $monthName = $date->translatedFormat('F Y');
    $daysInMonth = $date->daysInMonth;
    $startDay = $date->dayOfWeek;

    $today = Carbon::today();
@endphp



@extends('layouts.app')

@section('title', 'Jadwal Kegiatan & Event - Masjid Al-Nassr')

@section('content')
<div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">
    <!-- Header -->
    <div class="p-6 border-b">
        <div class="flex items-center justify-between mb-4">
            <button id="menuToggle" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <h1 class="text-xl font-bold text-center">Jadwal Kegiatan & Event</h1>
        <p class="text-center text-gray-600">Masjid Al-Nassr</p>
    </div>

    @include('components.debug-sidebar')

        <!-- Calendar -->
        <div class="p-6">
            <div class="bg-gray-100 rounded-2xl p-4">
                <div class="text-center mb-4 flex justify-between items-center">

                    <form method="GET" class="flex gap-2 w-full justify-center">

                        {{-- Dropdown Bulan --}}
                        <select name="month" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-1.5">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown Tahun --}}
                        <select name="year" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-1.5">
                            @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>

                    </form>

                </div>


                
                <!-- Days of Week -->
                <div class="grid grid-cols-7 gap-2 mb-2">
                    <div class="text-center text-sm font-medium text-gray-600 p-2">M</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">S</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">S</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">R</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">K</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">J</div>
                    <div class="text-center text-sm font-medium text-gray-600 p-2">S</div>
                </div>

                <!-- Calendar Dates -->
                <!-- Calendar Dates -->
                <div class="grid grid-cols-7 gap-2">

                    {{-- Kosongkan kotak sebelum tanggal 1 --}}
                    @for ($i = 0; $i < $startDay; $i++)
                        <div class="aspect-square"></div>
                    @endfor

                    {{-- Tanggal sebenarnya --}}
                    @for ($i = 1; $i <= $daysInMonth; $i++)
                        @if (
                                $today->day == $i &&
                                $today->month == $currentMonth &&
                                $today->year == $currentYear
                            )
                            {{-- Hari ini (highlight) --}}
                            <button class="aspect-square bg-gray-800 text-white rounded-lg p-2 text-sm font-medium hover:bg-gray-700">
                                {{ $i }}
                            </button>
                        @else
                            <button class="aspect-square bg-white rounded-lg p-2 text-sm font-medium hover:bg-gray-50">
                                {{ $i }}
                            </button>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Event Terdekat -->
        <div class="px-6 pb-4">
            <h2 class="text-lg font-bold mb-4">Event Terdekat</h2>
            <div class="grid grid-cols-3 gap-3">
                <!-- Event Card 1 -->
                <button onclick="openModal(1)" class="block">
                    <div class="bg-gray-100 rounded-xl p-4 hover:bg-gray-200 transition cursor-pointer">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="h-16 bg-gray-200 rounded mb-2 flex items-center justify-center text-xs text-gray-500">
                            Event 1
                        </div>
                    </div>
                </button>

                <!-- Event Card 2 -->
                <button onclick="openModal(2)" class="block">
                    <div class="bg-gray-100 rounded-xl p-4 hover:bg-gray-200 transition cursor-pointer">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="h-16 bg-gray-200 rounded mb-2 flex items-center justify-center text-xs text-gray-500">
                            Event 2
                        </div>
                    </div>
                </button>

                <!-- Event Card 3 -->
                <button onclick="openModal(3)" class="block">
                    <div class="bg-gray-100 rounded-xl p-4 hover:bg-gray-200 transition cursor-pointer">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="h-16 bg-gray-200 rounded mb-2 flex items-center justify-center text-xs text-gray-500">
                            Event 3
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div id="eventModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-end justify-center p-4">
            <div class="bg-white rounded-t-3xl sm:rounded-t-3xl w-full max-w-md max-h-[90vh] p-3 overflow-y-auto animate-slide-up"
                style="margin-top: auto;">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center rounded-t-3xl z-20">
                    <h2 class="text-xl font-bold text-[#315A62]">Detail Event</h2>
                    <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-xl transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <!-- Event Poster -->
                    <div class="mb-4 bg-gradient-to-br from-gray-200 to-gray-100 rounded-2xl overflow-hidden shadow-lg" style="height: 300px;">
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="font-medium">Poster Event</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <h3 id="modalEventTitle" class="text-xl font-bold text-[#315A62] mb-2">Kajian - Ust. Adi Hidayat</h3>
                    <div class="flex items-center gap-2 text-gray-600 mb-4">
                        <svg class="w-5 h-5 text-[#EDD06B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span id="modalEventDate" class="text-sm">Kamis, 5 Desember 2025</span>
                    </div>
                    
                    <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                        <h4 class="font-bold text-[#315A62] mb-2 text-sm">Deskripsi</h4>
                        <p id="modalEventDesc" class="text-gray-700 text-sm leading-relaxed">Topik: Cinta Sempurna | Nabi Hud & Nabi Shaleh Mengajarhkan Cinta Dalam Hidup...</p>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-6 bg-blue-50 p-3 rounded-xl">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Peserta Terdaftar: <strong id="modalEventAttendees">245 orang</strong></span>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('events.show', 1) }}" id="modalEventLink" class="block w-full bg-black text-white text-center font-bold py-4 rounded-2xl hover:shadow-lg transition-all duration-300">
                        Lihat Detail Lengkap
                    </a>
                </div>
            </div>
        </div>



        <!-- Kegiatan Rutin -->
        <div class="px-6 pb-6">
            <h2 class="text-lg font-bold mb-4">Kegiatan Rutin</h2>
            <div class="space-y-3">
                <!-- Kegiatan Item 1 -->
                <div class="flex items-center gap-3 p-4 bg-gray-100 rounded-xl">
                    <div class="w-16 h-16 bg-gray-300 rounded-lg flex-shrink-0"></div>
                    <div class="flex-1 h-4 bg-gray-300 rounded"></div>
                </div>

                <!-- Kegiatan Item 2 -->
                <div class="flex items-center gap-3 p-4 bg-gray-100 rounded-xl">
                    <div class="w-16 h-16 bg-gray-300 rounded-lg flex-shrink-0"></div>
                    <div class="flex-1 h-4 bg-gray-300 rounded"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    @keyframes slide-up {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .animate-slide-up {
        animation: slide-up 0.5s ease-out;
    }
</style>
@endsection

@push('scripts')
<script>
    // Modal functions
     function openModal(eventId) {
        const modal = document.getElementById('eventModal');
        const modalLink = document.getElementById('modalEventLink');
        
        modalLink.href = `/events/${eventId}`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('eventModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }


    // Close modal when clicking outside
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
