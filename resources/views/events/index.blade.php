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
// Ambil 3 event terdekat (yang akan datang)
    $upcomingEvents = collect($events)
        ->filter(function($event) {
            return Carbon::parse($event->start_at)->gte(Carbon::today());
        })
        ->sortBy('start_at')
        ->take(3);
@endphp

@extends('layouts.app')

@section('title', 'Jadwal Kegiatan & Event - Masjid Al-Nassr')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero/Jumbotron Section -->
    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-cyan-700 border-t-8 border-b-8 border-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center bg-white/10 backdrop-blur-sm rounded-3xl py-12 px-8 shadow-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 text-gray-900">
                    Selamat Datang di Masjid Al-Nassr
                </h1>
                <p class="text-lg md:text-xl text-gray-800 font-medium mb-0 max-w-3xl mx-auto leading-relaxed">
                    Pusat kegiatan keagamaan dan kemasyarakatan. Bergabunglah dengan berbagai event dan kegiatan rutin kami untuk mempererat ukhuwah islamiyah.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#calendar" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-700 bg-white hover:bg-emerald-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Lihat Jadwal Event
                    </a>
                    @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-white hover:text-emerald-700 transition-colors duration-200">
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Calendar Section (Left/Top) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Kalender Event</h2>
        <div class="bg-gray-100 rounded-2xl p-4">
            <div class="text-center mb-4 flex justify-between items-center">
                <form method="GET" class="flex gap-3 w-full justify-center">
                    {{-- Dropdown Bulan --}}
                    <select name="month" onchange="this.form.submit()" class="bg-white border-2 border-gray-300 text-gray-900 text-base font-semibold rounded-xl px-6 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all cursor-pointer hover:border-emerald-400">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Dropdown Tahun --}}
                    <select name="year" onchange="this.form.submit()" class="bg-white border-2 border-gray-300 text-gray-900 text-base font-semibold rounded-xl px-6 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all cursor-pointer hover:border-emerald-400">
                        @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            {{-- Days of Week --}}
            <div class="grid grid-cols-7 gap-2 mb-2 text-sm font-medium text-gray-600">
                <div class="text-center p-2">M</div>
                <div class="text-center p-2">S</div>
                <div class="text-center p-2">S</div>
                <div class="text-center p-2">R</div>
                <div class="text-center p-2">K</div>
                <div class="text-center p-2">J</div>
                <div class="text-center p-2">S</div>
            </div>

            {{-- Calendar Dates --}}
            <div class="grid grid-cols-7 gap-2">
                {{-- Kosongkan kotak sebelum tanggal 1 --}}
                @for ($i = 0; $i < $startDay; $i++)
                    <div class="aspect-square"></div>
                @endfor

                {{-- Tanggal --}}
                @for ($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $hasEvent = isset($eventsByDate[$i]);
                        
                    @endphp

                    <button 
                        class="aspect-square rounded-lg p-2 text-sm font-medium 
                        {{ $today->day == $i && $today->month == $currentMonth && $today->year == $currentYear ? 'bg-gray-800 text-white' : ($hasEvent ? 'bg-green-400 text-black hover:bg-green-600' : 'bg-white hover:bg-gray-50') }}"
                        
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

            <!-- Event Terdekat Sidebar (Right/Bottom) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 sticky top-24">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Event Terdekat</h2>
            @if(count($upcomingEvents) > 3)
                <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    Lihat Semua
                </a>
            @endif
        </div>
        
        <div class="space-y-3">
            @forelse($upcomingEvents as $event)
                @php
                    $eventDate = Carbon::parse($event->start_at);
                    $isToday = $eventDate->isToday();
                    $isTomorrow = $eventDate->isTomorrow();
                @endphp
                
                    <div 
                        class="bg-white border border-gray-200 rounded-xl p-4 hover:border-[#EDD06B] hover:shadow-md transition-all duration-300 cursor-pointer"
                        data-event='@json($event)'
                        onclick="openModal(JSON.parse(this.getAttribute('data-event')))"
                    >
                    <div class="flex gap-3">
                        <!-- Date Badge -->
                        <div class="flex flex-col items-center justify-center w-14 flex-shrink-0">
                            <div class="text-lg font-bold text-gray-800">
                                {{ $eventDate->format('d') }}
                            </div>
                            <div class="text-xs text-gray-600">
                                {{ $eventDate->format('M') }}
                            </div>
                            @if($isToday)
                                <div class="mt-1 px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded-full">
                                    Hari Ini
                                </div>
                            @elseif($isTomorrow)
                                <div class="mt-1 px-2 py-0.5 bg-blue-100 text-blue-600 text-xs rounded-full">
                                    Besok
                                </div>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-bold text-gray-900 truncate">
                                    {{ $event->nama_kegiatan }}
                                </h3>
                                <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                    {{ $eventDate->format('H:i') }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 line-clamp-2 mb-2">
                                {{ Str::limit($event->description ?? 'Tidak ada deskripsi', 80) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>{{ $event->attendees ?? 0 }} peserta</span>
                                </div>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $eventDate->isPast() ? 'bg-gray-100 text-gray-800' : 'bg-[#EDD06B] text-gray-900' }}">
                                    {{ $event->category ?? 'Event' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-3 text-gray-300">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Tidak ada event yang akan datang</p>
                    <p class="text-sm text-gray-400 mt-1">Cek bulan lainnya untuk melihat event</p>
                </div>
            @endforelse
        </div>
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
        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-900">Kegiatan Rutin</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($kegiatanRutin as $kegiatan)
                <div class="group hover:bg-emerald-50 transition-colors duration-200 flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-emerald-300">
                    <!-- Icon -->
                    <div class="w-14 h-14 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex-shrink-0 flex items-center justify-center transition-colors duration-200">
                        @if($kegiatan['icon'] === 'prayer')
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        @elseif($kegiatan['icon'] === 'book')
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        @elseif($kegiatan['icon'] === 'academic')
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $kegiatan['nama'] }}</h3>
                        <p class="text-sm text-emerald-600 font-medium mb-1">{{ $kegiatan['jadwal'] }}</p>
                        <p class="text-sm text-gray-600">{{ $kegiatan['deskripsi'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .animate-slide-up { animation: slide-up 0.5s ease-out; }
</style>
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
@endpush