<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pending Approval
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Pending Events -->
            @if($pendingEvents->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Menunggu Persetujuan ({{ $pendingEvents->total() }})</h3>
                    
                    <div class="space-y-4">
                        @foreach($pendingEvents as $event)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="text-xl font-bold text-gray-900">{{ $event->nama_kegiatan }}</h4>
                                        <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Jenis:</span> {{ $event->jenis_kegiatan ?? 'Umum' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Lokasi:</span> {{ $event->lokasi ?? '-' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Kuota:</span> {{ $event->kuota ?? 'Tidak terbatas' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Tanggal Mulai:</span> {{ \Carbon\Carbon::parse($event->start_at)->format('d M Y, H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Tanggal Selesai:</span> {{ \Carbon\Carbon::parse($event->end_at)->format('d M Y, H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Diajukan oleh:</span> {{ $event->creator ? $event->creator->nama_lengkap : 'Unknown' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($event->description)
                                    <div class="mb-4">
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Deskripsi:</p>
                                        <p class="text-sm text-gray-700">{{ $event->description }}</p>
                                    </div>
                                    @endif

                                    @if($event->rule)
                                    <div class="mb-4">
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Ketentuan:</p>
                                        <p class="text-sm text-gray-700">{{ $event->rule }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if($event->poster)
                                <div class="ml-6">
                                    <img src="{{ Storage::url($event->poster) }}" alt="{{ $event->nama_kegiatan }}" class="w-32 h-32 object-cover rounded-lg">
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                                <form action="{{ route('pengurus.approve', $event->event_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui
                                    </button>
                                </form>

                                <form action="{{ route('pengurus.reject', $event->event_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition" onclick="return confirm('Yakin ingin menolak event ini?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak
                                    </button>
                                </form>

                                <a href="{{ route('events.show', $event->event_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pendingEvents->links() }}
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Event Pending</h3>
                    <p class="text-gray-600">Semua event sudah ditinjau</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
