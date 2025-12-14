<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('events.mine') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Event Saya
                        </a>
                    </div>

                    <form action="{{ route('events.update', $event->event_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kegiatan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $event->nama_kegiatan) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama kegiatan">
                                @error('nama_kegiatan')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>

                            <!-- Jenis Kegiatan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kegiatan</label>
                                <input type="text" name="jenis_kegiatan" value="{{ old('jenis_kegiatan', $event->jenis_kegiatan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Misal: Kajian, Pengajian, Lainnya">
                                @error('jenis_kegiatan')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>

                            <!-- Lokasi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan lokasi">
                                @error('lokasi')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>

                            <!-- Waktu Mulai -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="start_at" value="{{ old('start_at', \Carbon\Carbon::parse($event->start_at)->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('start_at')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>

                            <!-- Waktu Selesai -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="end_at" value="{{ old('end_at', \Carbon\Carbon::parse($event->end_at)->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('end_at')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>

                            <!-- Kuota -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kuota (Opsional)</label>
                                <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kuota" min="0">
                                @error('kuota')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Rule -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Aturan / Rule (Opsional)</label>
                            <textarea name="rule" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Masukkan aturan event...">{{ old('rule', $event->rule) }}</textarea>
                            @error('rule')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Tulis deskripsi event...">{{ old('description', $event->description) }}</textarea>
                            @error('description')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>

                        <!-- Current Poster -->
                        @if($event->poster)
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Saat Ini</label>
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="Current poster" class="w-full max-w-md h-48 object-cover rounded-lg mb-4">
                        </div>
                        @endif

                        <!-- Poster -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar/Poster Baru (Opsional)</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                                <input type="file" name="poster" accept="image/*" class="hidden" id="file-upload">
                                <label for="file-upload" class="cursor-pointer">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-gray-600">Klik untuk upload atau drag & drop</p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF (Max. 2MB)</p>
                                </label>
                                <p class="text-xs text-gray-500 mt-4" id="file-name"></p>
                            </div>
                            @error('poster')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                                Update Event
                            </button>
                            <a href="{{ route('events.mine') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fileUpload = document.getElementById('file-upload');
        const fileName = document.getElementById('file-name');
        
        fileUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileName.textContent = 'File: ' + this.files[0].name;
            }
        });
    </script>
</x-app-layout>
