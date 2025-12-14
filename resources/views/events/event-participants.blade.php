<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Peserta - ' . $event->nama_kegiatan) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $event->nama_kegiatan }}</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ $event->jenis_kegiatan ?? 'Umum' }}</p>
                        </div>
                        <a href="{{ route('events.manage-participants') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <!-- Event Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-600 text-sm">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->start_at)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-600 text-sm">Jam Mulai</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->start_at)->format('H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-600 text-sm">Lokasi</p>
                            <p class="font-semibold text-gray-900">{{ Str::limit($event->lokasi, 20) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-600 text-sm">Peserta Terdaftar</p>
                            <p class="font-semibold text-gray-900">{{ $registeredParticipants->count() }}/{{ $event->kuota ?? '∞' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Add Participant Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Tambah Peserta</h3>
                        </div>
                        <div class="p-6">
                            @if($availableJamaah->count() > 0)
                                <form action="{{ route('events.register-participant', $event->event_id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="jemaah_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Jamaah
                                        </label>
                                        <select name="jemaah_id" id="jemaah_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900" required>
                                            <option value="">-- Pilih Jamaah --</option>
                                            @foreach($availableJamaah as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->nama_lengkap }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jemaah_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Daftarkan
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5-4a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-600">Semua jamaah sudah terdaftar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Participants List Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Daftar Peserta ({{ $registeredParticipants->count() }})
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            @if($registeredParticipants->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. HP</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($registeredParticipants as $participant)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <p class="text-sm font-medium text-gray-900">{{ $participant->user->nama_lengkap }}</p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <p class="text-sm text-gray-600">{{ $participant->user->email }}</p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <p class="text-sm text-gray-600">{{ $participant->user->no_hp ?? '-' }}</p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <form action="{{ route('events.remove-participant', [$event->event_id, $participant->peserta_event_id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-12 text-center">
                                    <svg class="mx-auto w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 6H7a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2V8a2 2 0 00-2-2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Peserta</h3>
                                    <p class="text-gray-600">Tambahkan peserta menggunakan form di sebelah kiri</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
