@extends('layouts.app')

@section('title', 'Detail Event')

@section('content')
<div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">

    <!-- Header -->
    <div class="p-6 border-b">
        <div class="flex items-center gap-4">
            <a href="{{ route('events.index') }}" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Event Poster -->
    <div class="p-6">
        <div class="bg-gray-200 rounded-2xl overflow-hidden mb-6" style="height: 400px;">

            @if ($event->poster)
            <img src="{{ asset('storage/' . $event->poster) }}"
                class="w-full h-full object-cover cursor-pointer"
                onclick="openModal(`{{ asset('storage/' . $event->poster) }}`)">


            @else
                <div class="w-full h-full flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="w-20 h-20 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="font-medium">Tidak Ada Poster</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Carousel Dots -->
        <div class="flex justify-center gap-2 mb-6">
            <div class="w-2 h-2 bg-gray-800 rounded-full"></div>
            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
        </div>
    </div>

    <!-- Event Details -->
    <div class="px-6 pb-6">

        <!-- Nama Event -->
        <h1 class="text-2xl font-bold mb-2">
            {{ $event->nama_kegiatan }}
        </h1>

        <!-- Tanggal Event -->
        <p class="text-gray-600 mb-6">
            {{ \Carbon\Carbon::parse($event->start_at)->translatedFormat('d F Y') }}
            -
            {{ \Carbon\Carbon::parse($event->end_at)->translatedFormat('d F Y') }}
        </p>

        <!-- Lokasi -->
        <p class="text-gray-700 mb-2">
            📍 <strong>{{ $event->lokasi }}</strong>
        </p>

        <!-- Jam -->
        <p class="text-gray-700 mb-6">
            🕒 {{ $event->start_time }} - {{ $event->end_time }}
        </p>

        <!-- Deskripsi -->
        <h2 class="text-lg font-bold mb-3">Deskripsi</h2>
        <p class="text-gray-700 mb-6 leading-relaxed">
            {{ $event->description }}
        </p>

        <!-- Peserta Info -->
        <div class="mb-6">
            <p class="text-sm text-gray-600">
                Peserta Terdaftar:
                <span class="font-semibold">{{ $event->attendees }}</span> /
                <span class="font-semibold">{{ $event->kuota ?? '∞' }}</span>
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Action Buttons for Jemaah -->
        @auth
            @if(auth()->user()->role === 'jemaah' && $event->status === 'published')
                @php
                    $isRegistered = \App\Models\PesertaEvent::whereHas('sesiEvent', function($q) use ($event) {
                        $q->where('event_id', $event->event_id);
                    })->where('jemaah_id', auth()->id())->exists();
                @endphp

                @if($isRegistered)
                    <form action="{{ route('events.unregister', $event->event_id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="w-full bg-red-600 text-white font-semibold py-3 px-6 rounded-full 
                                   hover:bg-red-700 transition flex items-center justify-center gap-2"
                            onclick="return confirm('Yakin ingin membatalkan pendaftaran?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal Daftar
                        </button>
                    </form>
                @else
                    <form action="{{ route('events.register', $event->event_id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="w-full bg-white border-2 border-gray-800 text-gray-800 font-semibold py-3 px-6 rounded-full 
                                   hover:bg-gray-800 hover:text-white transition flex items-center justify-center gap-2"
                            @if($event->kuota && $event->attendees >= $event->kuota) disabled @endif>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            @if($event->kuota && $event->attendees >= $event->kuota)
                                Kuota Penuh
                            @else
                                Daftar Event
                            @endif
                        </button>
                    </form>
                @endif
            @elseif($event->status === 'draft')
                <div class="w-full bg-yellow-100 border border-yellow-400 text-yellow-800 font-semibold py-3 px-6 rounded-full text-center">
                    Event sedang menunggu persetujuan
                </div>
            @elseif($event->status === 'cancelled')
                <div class="w-full bg-red-100 border border-red-400 text-red-800 font-semibold py-3 px-6 rounded-full text-center">
                    Event ditolak/dibatalkan
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" 
               class="w-full bg-white border-2 border-gray-800 text-gray-800 font-semibold py-3 px-6 rounded-full 
                      hover:bg-gray-800 hover:text-white transition flex items-center justify-center gap-2">
                Login untuk mendaftar
            </a>
        @endauth
    </div>

    <!-- MODAL ZOOM IMAGE -->
    <div id="imageModal"
        class="fixed inset-0 bg-white bg-opacity-70 hidden flex items-center justify-center z-50"
        onclick="closeModal()">

        <img id="modalImage" src="" class="max-w-3xl max-h-[90vh] rounded-lg shadow-lg">
    </div>

</div>

<script>
let scale = 1;

function openModal(imageUrl) {
    const modal = document.getElementById('imageModal');
    const image = document.getElementById('modalImage');
    scale = 1;

    modal.classList.remove('hidden');
    image.src = imageUrl;
    image.style.transform = `scale(${scale})`;

    image.onclick = () => {
        scale += 0.5; // setiap klik zoom 50%
        if(scale > 3) scale = 1; // max zoom 3x, lalu reset
        image.style.transform = `scale(${scale})`;
    };
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}

// klik di background modal untuk menutup
document.getElementById('imageModal').addEventListener('click', function(e){
    if(e.target.id === 'imageModal') closeModal();
});
</script>
@endsection
