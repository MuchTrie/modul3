<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Absen Kegiatan: {{ $event->nama_kegiatan }}
            </h2>
            <a href="{{ route('panitia.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Event Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Kegiatan</p>
                            <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($event->start_at)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="text-lg font-semibold">{{ $event->lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Peserta</p>
                            <p class="text-lg font-semibold">{{ $participants->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($participants->count() > 0)
            <!-- Attendance Form -->
            <form id="attendanceForm" action="{{ route('events.mark-attendance', $event->event_id) }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kehadiran Jamaah</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jamaah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($participants as $index => $peserta)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $peserta->user->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">{{ $peserta->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $peserta->user->no_hp ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="attendance[{{ $index }}][peserta_event_id]" value="{{ $peserta->peserta_event_id }}">
                                            <select name="attendance[{{ $index }}][status_hadir]" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="hadir" {{ $peserta->status_hadir === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="tidak hadir" {{ $peserta->status_hadir === 'tidak hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                                <option value="belum diketahui" {{ $peserta->status_hadir === 'belum diketahui' ? 'selected' : '' }}>Belum Diketahui</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex gap-3">
                    <button type="submit" id="submitBtn" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg id="submitIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="submitText">Simpan Absensi</span>
                    </button>
                    <a href="{{ route('panitia.dashboard') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition">
                        Batal
                    </a>
                </div>
            </form>

            <!-- Loading Script -->
            <script>
                document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitBtn');
                    const submitIcon = document.getElementById('submitIcon');
                    const submitText = document.getElementById('submitText');

                    // Disable button
                    submitBtn.disabled = true;
                    
                    // Change icon to loading spinner
                    submitIcon.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    
                    // Change text
                    submitText.textContent = 'Menyimpan...';
                });
            </script>
            @else
            <!-- Empty State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Peserta Terdaftar</h3>
                        <p class="text-gray-600 mb-4">Tidak ada jamaah yang terdaftar untuk kegiatan ini</p>
                        <a href="{{ route('events.manage-participants') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Daftar Peserta
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>