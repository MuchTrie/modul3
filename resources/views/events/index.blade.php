@php
use Carbon\Carbon;

// daftar bulan
$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
    6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober',
    11 => 'November', 12 => 'Desember',
];

// bulan & tahun aktif
$currentMonth = request('month', now()->month);
$currentYear = request('year', now()->year);

$date = Carbon::create($currentYear, $currentMonth, 1);

$monthName = $date->translatedFormat('F Y');
$daysInMonth = $date->daysInMonth;
$startDay = $date->dayOfWeek;
$today = Carbon::now('Asia/Jakarta'); 
$eventsByDate = [];

foreach ($events as $ev) {
    $day = Carbon::parse($ev->start_at)->day;

    if (!isset($eventsByDate[$day])) {
        $eventsByDate[$day] = [];
    }

    $eventsByDate[$day][] = $ev;
}
// $upcomingEvents sudah dikirim dari controller, tidak perlu difilter ulang di sini
@endphp


@extends('layouts.app')

@section('title', 'Jadwal Kegiatan & Event - Masjid Al-Nassr')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero/Jumbotron Section -->
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-600 border-t-8 border-b-8 border-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 lg:py-32">
            <div class="text-center bg-white/10 backdrop-blur-sm rounded-3xl py-10 md:py-14 px-6 md:px-10 shadow-2xl">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-4 md:mb-6 text-white">
                    Selamat Datang di Masjid Al-Nassr
                </h1>
                <p class="text-sm md:text-base lg:text-lg text-white font-medium mb-6 md:mb-8 max-w-3xl mx-auto leading-relaxed">
                    Pusat kegiatan keagamaan dan kemasyarakatan. Bergabunglah dengan berbagai event dan kegiatan rutin kami untuk mempererat ukhuwah islamiyah.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <a href="#calendar" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-3.5 border border-transparent text-sm sm:text-base font-bold rounded-xl text-blue-700 bg-white hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Lihat Jadwal Event
                    </a>
                    @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-3.5 border-2 border-white text-sm sm:text-base font-bold rounded-xl text-white hover:bg-white hover:text-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login untuk Daftar Event
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="calendar" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Event Terdekat (Left) -->
            <div>
                <div class="bg-white rounded-2xl shadow-lg p-5 md:p-6 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-2xl font-bold text-gray-900">Event Terdekat</h2>
                        <span class="text-sm text-gray-500">3 Event Mendatang</span>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        @forelse($upcomingEvents as $event)
                            @php
                                $eventDate = Carbon::parse($event->start_at);
                                $isToday = $eventDate->isToday();
                                $isTomorrow = $eventDate->isTomorrow();
                            @endphp
                            
                            <div 
                                class="bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:shadow-xl transition-all duration-300 cursor-pointer group hover:scale-[1.02]"
                                data-event='@json($event)'
                                onclick="openModal(JSON.parse(this.getAttribute('data-event')))"
                            >
                                <div class="flex gap-4">
                                    <!-- Date Badge -->
                                    <div class="flex flex-col items-center justify-center bg-blue-600 rounded-lg p-3 flex-shrink-0 min-w-[70px] group-hover:bg-blue-700 transition-colors">
                                        <div class="text-2xl font-bold text-white">
                                            {{ $eventDate->format('d') }}
                                        </div>
                                        <div class="text-xs text-blue-100 uppercase font-semibold">
                                            {{ $eventDate->format('M') }}
                                        </div>
                                        @if($isToday)
                                            <div class="mt-2 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-bold">
                                                HARI INI
                                            </div>
                                        @elseif($isTomorrow)
                                            <div class="mt-2 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full font-bold">
                                                BESOK
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Event Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col gap-1 mb-2">
                                            <h3 class="font-bold text-gray-900 text-lg leading-tight">
                                                {{ $event->nama_kegiatan }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 text-sm text-blue-700 font-semibold bg-blue-50 px-2 py-1 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $eventDate->format('H:i') }} WIB
                                                </span>
                                                <span class="inline-flex items-center gap-1 text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $event->lokasi ?? 'Masjid Al-Nassr' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 line-clamp-2 mb-3 leading-relaxed">
                                            {{ Str::limit($event->description ?? 'Tidak ada deskripsi', 90) }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <div class="flex items-center gap-1.5 text-sm text-gray-700 font-medium">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <span>{{ $event->attendees ?? 0 }} / {{ $event->kuota ?? '∞' }} Peserta</span>
                                            </div>
                                            
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase 
                                                {{ $eventDate->isPast() ? 'bg-gray-200 text-gray-700' : 'bg-yellow-400 text-gray-900' }}">
                                                {{ $event->category ?? 'Event' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-bold text-lg mb-2">Tidak ada event yang akan datang</p>
                                <p class="text-sm text-gray-500 mb-4">Belum ada event yang dijadwalkan dalam waktu dekat</p>
                                @auth
                                    @if(auth()->user()->role === 'panitia')
                                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Buat Event Baru
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Kalender (Right) -->
            <div>
                <div class="bg-white rounded-2xl shadow-lg p-5 md:p-6 h-full">
                    <h2 class="text-2xl font-bold mb-5 text-gray-900">Kalender Event</h2>
                    <div class="bg-gradient-to-br from-blue-50 to-gray-50 rounded-xl p-4 border border-blue-100">
                        <div class="mb-4">
                            <form method="GET" class="flex gap-3 justify-center items-center">
                                {{-- Dropdown Bulan --}}
                                <select name="month" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-900 text-base font-medium rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer hover:border-blue-400 shadow-sm min-w-[140px]">
                                    @foreach($months as $num => $name)
                                        <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Dropdown Tahun --}}
                                <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-900 text-base font-medium rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer hover:border-blue-400 shadow-sm min-w-[100px]">
                                    @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
                        </div>

                        {{-- Days of Week --}}
                        <div class="grid grid-cols-7 gap-1 mb-2 text-sm font-bold text-gray-700">
                            <div class="text-center py-1">M</div>
                            <div class="text-center py-1">S</div>
                            <div class="text-center py-1">S</div>
                            <div class="text-center py-1">R</div>
                            <div class="text-center py-1">K</div>
                            <div class="text-center py-1">J</div>
                            <div class="text-center py-1">S</div>
                        </div>

                        {{-- Calendar Dates --}}
                        <div class="grid grid-cols-7 gap-1">
                            {{-- Kosongkan kotak sebelum tanggal 1 --}}
                            @for ($i = 0; $i < $startDay; $i++)
                                <div class="aspect-square"></div>
                            @endfor

                            {{-- Tanggal --}}
                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    $hasEvent = isset($eventsByDate[$i]);
                                    // Cek apakah ada event yang akan datang di tanggal ini
                                    $hasUpcomingEvent = false;
                                    if ($hasEvent) {
                                        foreach ($eventsByDate[$i] as $ev) {
                                            if (Carbon::parse($ev->start_at)->isFuture() || Carbon::parse($ev->start_at)->isToday()) {
                                                $hasUpcomingEvent = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp

                                <button 
                                    class="aspect-square rounded-lg p-1 text-sm font-semibold transition-all duration-200
                                    {{ $today->day == $i && $today->month == $currentMonth && $today->year == $currentYear 
                                        ? 'bg-blue-600 text-white ring-2 ring-blue-400 shadow-lg' 
                                        : ($hasUpcomingEvent 
                                            ? 'bg-blue-500 text-white hover:bg-blue-600 hover:shadow-md hover:scale-105' 
                                            : ($hasEvent 
                                                ? 'bg-gray-300 text-gray-700 hover:bg-gray-400' 
                                                : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200')) }}"
                                    
                                    @if($hasEvent)
                                        onclick='openCalendarEvent(@json($eventsByDate[$i]))'
                                    @endif
                                >
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Sholat (Full Width Below) -->
        <div class="mt-8">
            <div class="bg-gradient-to-br from-blue-700 to-blue-600 rounded-2xl shadow-2xl p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        Jadwal Sholat Hari Ini
                    </h2>
                    <div class="text-sm text-blue-100 bg-white/20 px-4 py-2 rounded-lg font-semibold backdrop-blur">
                        {{ now('Asia/Jakarta')->isoFormat('DD MMM YYYY') }}
                    </div>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    @php
                        $prayers = [
                            ['name' => 'Subuh', 'icon' => '🌙'],
                            ['name' => 'Dzuhur', 'icon' => '☀️'],
                            ['name' => 'Ashar', 'icon' => '🌤️'],
                            ['name' => 'Magrib', 'icon' => '🌅'],
                            ['name' => 'Isya', 'icon' => '⭐'],
                        ];
                        $now = now('Asia/Jakarta');
                        $nowTime = $now->format('H:i');
                    @endphp
                    
                    @foreach($prayers as $prayer)
                        @php
                            $prayerTime = $prayerTimes[$prayer['name']];
                            $isPast = $nowTime > $prayerTime;
                            $isNext = !$isPast && !isset($nextFound);
                            if ($isNext) $nextFound = true;
                        @endphp
                        <div class="flex flex-col items-center text-center p-5 rounded-xl transition-all duration-300 {{ $isNext ? 'bg-yellow-400 shadow-xl scale-105 ring-4 ring-yellow-300' : 'bg-white hover:shadow-lg' }}">
                            <span class="text-4xl mb-3">{{ $prayer['icon'] }}</span>
                            <p class="font-bold text-base mb-2 {{ $isNext ? 'text-gray-900' : 'text-gray-800' }}">{{ $prayer['name'] }}</p>
                            @if($isNext)
                                <div class="mb-2 px-3 py-1 bg-gray-900 text-yellow-300 text-xs font-bold rounded-full uppercase animate-pulse">
                                    BERIKUTNYA
                                </div>
                            @elseif($isPast)
                                <div class="mb-2 px-3 py-1 bg-gray-200 text-gray-600 text-xs font-semibold rounded-full">
                                    Terlewat
                                </div>
                            @else
                                <div class="mb-2 h-6"></div>
                            @endif
                            <p class="font-bold text-2xl {{ $isNext ? 'text-gray-900' : 'text-blue-700' }}">{{ $prayerTime }}</p>
                            <p class="text-xs {{ $isNext ? 'text-gray-700' : 'text-gray-500' }} font-medium mt-1">WIB</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-end justify-center p-4">
        <div class="bg-white rounded-t-3xl w-full max-w-md max-h-[90vh] p-3 overflow-y-auto animate-slide-up" style="margin-top: auto;">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center rounded-t-3xl z-20">
                <h2 class="text-xl font-bold text-[#315A62]">Detail Event</h2>
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-xl transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="mb-4 bg-gradient-to-br from-gray-200 to-gray-100 rounded-2xl overflow-hidden shadow-lg" style="height: 300px;">
                    <img id="modalEventPoster" class="w-full h-full object-cover" src="https://via.placeholder.com/300x300?text=Event" alt="Poster Event">
                </div>

                <h3 id="modalEventTitle" class="text-xl font-bold text-[#315A62] mb-2"></h3>
                <div class="flex items-center gap-2 text-gray-600 mb-4">
                    <svg class="w-5 h-5 text-[#EDD06B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span id="modalEventDate" class="text-sm"></span>
                </div>

                <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-bold text-[#315A62] mb-2 text-sm">Deskripsi</h4>
                    <p id="modalEventDesc" class="text-gray-700 text-sm leading-relaxed"></p>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 mb-6 bg-blue-50 p-3 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Peserta Terdaftar: <strong id="modalEventAttendees"></strong></span>
                </div>

                <a href="" id="modalEventLink" class="block w-full bg-black text-white text-center font-bold py-4 rounded-2xl hover:shadow-lg transition-all duration-300">
                    Lihat Detail Lengkap
                </a>
            </div>
        </div>
    </div>

    <div id="calendarEventModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-6">
            <h2 class="text-xl font-bold mb-4">Event Pada Tanggal Ini</h2>

            <div id="calendarEventList" class="space-y-3 mb-4"></div>

            <button onclick="closeCalendarModal()" class="mt-5 w-full py-2 bg-gray-800 text-white rounded-xl">
                Tutup
            </button>
        </div>
    </div>

    <!-- Kegiatan Rutin -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Kegiatan Rutin</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($kegiatanRutin as $kegiatan)
                <div class="group hover:bg-blue-50 transition-all duration-300 flex items-start gap-4 p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:shadow-lg">
                    <!-- Icon -->
                    <div class="w-16 h-16 bg-blue-100 group-hover:bg-blue-500 rounded-xl flex-shrink-0 flex items-center justify-center transition-all duration-300 shadow-md group-hover:shadow-lg group-hover:scale-110">
                        @if($kegiatan['icon'] === 'prayer')
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        @elseif($kegiatan['icon'] === 'book')
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        @elseif($kegiatan['icon'] === 'academic')
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 mb-2 text-base group-hover:text-blue-700 transition-colors">{{ $kegiatan['nama'] }}</h3>
                        <p class="text-sm text-blue-600 font-semibold mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $kegiatan['jadwal'] }}
                        </p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $kegiatan['deskripsi'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .animate-slide-up { animation: slide-up 0.5s ease-out; }
</style>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-100 mt-16 w-full pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- About -->
            <div>
                <h4 class="font-bold text-lg text-white mb-4">Tentang Kami</h4>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Masjid Al-Nassr adalah pusat kegiatan keagamaan dan kemasyarakatan yang melayani masyarakat dengan berbagai program dan event islami.
                </p>
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Jl. Masjid Al-Nassr, Indonesia</span>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-bold text-lg text-white mb-4">Menu Cepat</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('events.index') }}" class="text-gray-400 hover:text-blue-400 text-sm flex items-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Kalender Event
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-gray-400 hover:text-blue-400 text-sm flex items-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="text-gray-400 hover:text-blue-400 text-sm flex items-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Daftar Akun
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="font-bold text-lg text-white mb-4">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-gray-400 text-sm">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>info@alnassr.com</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-400 text-sm">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>(021) 1234-5678</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-600 text-sm">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Senin - Jumat: 08:00 - 17:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-8 border-t border-gray-700 text-center">
            <p class="text-gray-400 font-medium mb-2">© {{ date('Y') }} Masjid Al-Nassr. All rights reserved.</p>
            <p class="text-sm text-gray-500">Sistem Manajemen Kegiatan Masjid - Dibuat dengan ❤️ untuk kemudahan umat</p>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
function openModal(eventData) {
    // Jika eventData sudah string (dari JSON), parse. Jika object, gunakan langsung
    const event = typeof eventData === 'string' ? JSON.parse(eventData) : eventData;
    
    document.getElementById('modalEventTitle').textContent = event.nama_kegiatan;
    document.getElementById('modalEventDesc').textContent = event.description ?? '-';
    document.getElementById('modalEventAttendees').textContent = (event.attendees ?? 0) + ' orang';
    
    const startDate = new Date(event.start_at);
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('modalEventDate').textContent = startDate.toLocaleDateString('id-ID', options);
    
    document.getElementById('modalEventPoster').src = event.poster ? 
        `/storage/${event.poster}` : 
        'https://via.placeholder.com/400x300?text=No+Poster';

    document.getElementById('modalEventLink').href = `/events/${event.event_id}`;
    
    const modal = document.getElementById('eventModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('eventModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openCalendarEvent(events) {
    let html = "";
    events.forEach(ev => {
        html += `
            <div 
                onclick='selectEventFromCalendar(${JSON.stringify(ev)})'
                class="p-4 border rounded-xl hover:bg-gray-100 cursor-pointer transition-colors"
            >
                <div class="font-bold text-[#315A62] mb-1">${ev.nama_kegiatan}</div>
                <div class="text-sm text-gray-600">
                    ${new Date(ev.start_at).toLocaleString('id-ID', { 
                        weekday: 'long', 
                        day: 'numeric', 
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}
                </div>
            </div>
        `;
    });

    document.getElementById('calendarEventList').innerHTML = html;
    document.getElementById('calendarEventModal').classList.remove('hidden');
}

function selectEventFromCalendar(ev) {
    closeCalendarModal(); // ← TUTUP MODAL LIST EVENT
    openModal(ev);        // ← BUKA MODAL DETAIL EVENT
}


function closeCalendarModal() {
    document.getElementById('calendarEventModal').classList.add('hidden');
}

document.getElementById('eventModal').addEventListener('click', function(e) {
    if(e.target === this) closeModal();
});

document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') closeModal();
});
</script>

<style>
/* Scrollbar styling for Event Terdekat section */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endpush