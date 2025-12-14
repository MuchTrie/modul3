<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Selamat Datang, Panitia!</h3>
                            <p class="text-sm text-gray-600">{{ Auth::user()->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500 mt-1">Role: panitia</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                            <div class="text-xs text-gray-500">{{ now()->format('H:i') }} WIB</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="flex flex-row gap-4 mb-6 overflow-x-auto">
                <!-- My Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Event</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $events->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $events->where('status', 'draft')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Approved</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $events->where('status', 'published')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Cancelled</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $events->where('status', 'cancelled')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button type="button" onclick="window.location.href='{{ route('events.create') }}'" class="w-full flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition text-left">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Ajukan Event Baru</p>
                                <p class="text-sm text-gray-600">Submit event untuk approval</p>
                            </div>
                        </button>

                        <button type="button" onclick="window.location.href='{{ route('events.mine') }}'" class="w-full flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-left">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Lihat Semua Event</p>
                                <p class="text-sm text-gray-600">Kelola event saya</p>
                            </div>
                        </button>

                        <button type="button" onclick="window.location.href='{{ route('events.manage-participants') }}'" class="w-full flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-left">
                            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Daftar Jamaah</p>
                                <p class="text-sm text-gray-600">Kelola peserta event</p>
                            </div>
                        </button>

                        <button type="button" onclick="window.location.href='{{ route('events.manage-participants') }}'" class="w-full flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition text-left" title="Absen Kegiatan">
                            <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Absen Kegiatan</p>
                                <p class="text-sm text-gray-600">Catat kehadiran jamaah</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Message -->
            @if($events->count() > 0)
            <!-- My Events Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Saya (Terbaru)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($events->sortByDesc('start_at')->take(5) as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->nama_kegiatan }}</div>
                                        <div class="text-sm text-gray-500">{{ $event->jenis_kegiatan ?? 'Umum' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->start_at)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($event->status == 'draft')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($event->status == 'published')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->attendees }}/{{ $event->kuota ?? '∞' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Event</h3>
                        <p class="text-gray-600 mb-4">Anda belum mengajukan event apapun</p>
                        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajukan Event Pertama
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
