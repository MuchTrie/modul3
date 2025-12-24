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
                            <h3 class="text-2xl font-bold text-gray-800">Assalamu'alaikum, {{ Auth::user()->nama_lengkap }}!</h3>
                            <p class="text-sm text-gray-600">Selamat datang di Dashboard Jemaah Masjid Al-Nassr</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Event Saya</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ Auth::user()->pesertaEvents()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Event Tersedia</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ \App\Models\Event::where('status', 'published')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-w-[200px]">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Events</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Event::where('status', 'published')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('jemaah.calendar') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Kalender Event</p>
                                <p class="text-sm text-gray-600">Lihat semua event</p>
                            </div>
                        </a>

                        <a href="{{ route('jemaah.my-events') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">Event Saya</p>
                                <p class="text-sm text-gray-600">Event yang diikuti</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Events Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Event Yang Saya Ikuti</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(Auth::user()->pesertaEvents()->with('sesiEvent.event')->latest()->take(5)->get() as $peserta)
                                @php $event = $peserta->sesiEvent->event; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->nama_kegiatan }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($event->description ?? '', 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $peserta->sesiEvent->start_at ? $peserta->sesiEvent->start_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $peserta->sesiEvent->location_override ?? $event->lokasi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($event->status == 'published')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @elseif($event->status == 'draft')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Anda belum mengikuti event apapun
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Event Yang Akan Datang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(\App\Models\Event::where('status', 'published')
                                    ->where('start_at', '>=', now())
                                    ->orderBy('start_at')
                                    ->take(5)
                                    ->get() as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->nama_kegiatan }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($event->description ?? '', 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->start_at ? $event->start_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->lokasi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->attendees ?? 0 }} / {{ $event->kuota ?? '∞' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada event yang akan datang
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Kalender & Jadwal Sholat (Smooth) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Kalender & Jadwal Sholat</h3>
                            <p class="text-sm text-gray-600">Masjid Al-Nassr</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">{{ now()->translatedFormat('l, j F Y') }}</div>
                            <div class="text-xs text-gray-500">{{ now()->format('H:i') }} WIB</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-5 gap-4">
                        {{-- KALENDER --}}
                        <div class="xl:col-span-3 bg-gray-50 rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-semibold text-gray-800">{{ $monthName }}</div>

                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('jemaah.dashboard', ['date' => $prevMonth->format('Y-m-d')]) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-100"
                                        title="Bulan sebelumnya"
                                    >
                                        ‹
                                    </a>

                                    @if($canGoNext)
                                        <a
                                            href="{{ route('jemaah.dashboard', ['date' => $nextMonth->format('Y-m-d')]) }}"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-100"
                                            title="Bulan berikutnya"
                                        >
                                            ›
                                        </a>
                                    @else
                                        <span
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-300 cursor-not-allowed"
                                            title="Tidak bisa lanjut"
                                        >
                                            ›
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Nama hari --}}
                            <div class="grid grid-cols-7 text-center text-xs text-gray-400 mb-2">
                                <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                            </div>

                            {{-- Grid tanggal --}}
                            <div class="grid grid-cols-7 gap-2">
                                @for ($i = 0; $i < $firstDayWeekIndex; $i++)
                                    <div class="h-10"></div>
                                @endfor

                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $thisDate = $monthStart->copy()->setDay($day)->startOfDay();

                                        $isSelected = $thisDate->format('Y-m-d') === $selectedDate->format('Y-m-d');
                                        $isToday    = $thisDate->format('Y-m-d') === $today->format('Y-m-d');

                                        $btnClass = 'h-10 w-full rounded-xl bg-white border border-gray-200 text-sm text-gray-800 shadow-sm hover:bg-gray-100 flex items-center justify-center';
                                        if ($isSelected) $btnClass = 'h-10 w-full rounded-xl bg-gray-900 text-white text-sm font-semibold flex items-center justify-center';
                                        elseif ($isToday) $btnClass = 'h-10 w-full rounded-xl bg-white border-2 border-gray-900 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 flex items-center justify-center';
                                    @endphp

                                    <button
                                        type="button"
                                        data-date="{{ $thisDate->format('Y-m-d') }}"
                                        class="js-sholat-day {{ $btnClass }}"
                                        title="{{ $thisDate->translatedFormat('j F Y') }}"
                                    >
                                        {{ $day }}
                                    </button>
                                @endfor
                            </div>
                        </div>

                        {{-- JADWAL SHOLAT --}}
                        <div class="xl:col-span-2 bg-gray-50 rounded-2xl p-4">
                            <div class="mb-3">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-800">Jadwal Sholat</p>
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-900 text-white text-xs">
                                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                        Tanggal dipilih
                                    </span>
                                </div>

                                <p id="js-fullDate" class="text-sm text-gray-600 mt-1">{{ $fullDate }}</p>

                                @if(!empty($apiError))
                                    <div id="js-apiError" class="mt-3 p-3 rounded-xl bg-white border border-red-200 text-red-700 text-sm">
                                        {{ $apiError }}
                                    </div>
                                @else
                                    <div id="js-apiError" class="hidden mt-3 p-3 rounded-xl bg-white border border-red-200 text-red-700 text-sm"></div>
                                @endif
                            </div>

                            <div id="js-jadwalList" class="space-y-2">
                                @foreach ($jadwalSholat as $nama => $jam)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-gray-200 shadow-sm">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-800">{{ $nama }}</span>
                                            <span class="text-xs text-gray-400">Masjid Al-Nassr</span>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $jam }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- JS Smooth Update --}}
            <script>
            document.addEventListener('DOMContentLoaded', () => {
              const buttons = document.querySelectorAll('.js-sholat-day');
              const fullDateEl = document.getElementById('js-fullDate');
              const listEl = document.getElementById('js-jadwalList');
              const errEl = document.getElementById('js-apiError');

              let activeBtn = null;

              function setLoading(state) {
                if (!listEl) return;
                if (state) {
                  listEl.style.opacity = '0.6';
                  listEl.style.pointerEvents = 'none';
                } else {
                  listEl.style.opacity = '1';
                  listEl.style.pointerEvents = 'auto';
                }
              }

              function renderList(jadwal) {
                const entries = Object.entries(jadwal || {});
                if (!entries.length) {
                  listEl.innerHTML = `<div class="text-sm text-gray-500">Tidak ada data jadwal.</div>`;
                  return;
                }

                listEl.innerHTML = entries.map(([nama, jam]) => `
                  <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-gray-200 shadow-sm">
                    <div class="flex flex-col">
                      <span class="text-sm font-semibold text-gray-800">${nama}</span>
                      <span class="text-xs text-gray-400">Masjid Al-Nassr</span>
                    </div>
                    <div class="text-sm font-semibold text-gray-900">${jam}</div>
                  </div>
                `).join('');
              }

              async function fetchSholat(date) {
                setLoading(true);
                try {
                  const res = await fetch(`{{ route('jemaah.dashboard.sholat') }}?date=${encodeURIComponent(date)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                  });

                  const data = await res.json();

                  if (fullDateEl) fullDateEl.textContent = data.fullDate || '';

                  if (data.apiError) {
                    errEl.classList.remove('hidden');
                    errEl.textContent = data.apiError;
                  } else {
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                  }

                  renderList(data.jadwalSholat);
                } catch (e) {
                  errEl.classList.remove('hidden');
                  errEl.textContent = 'Gagal memuat jadwal (cek koneksi / API).';
                } finally {
                  setLoading(false);
                }
              }

              buttons.forEach(btn => {
                btn.addEventListener('click', async () => {
                  const date = btn.dataset.date;

                  // highlight tombol yang dipilih
                  if (activeBtn) activeBtn.classList.remove('ring-2', 'ring-gray-900');
                  btn.classList.add('ring-2', 'ring-gray-900');
                  activeBtn = btn;

                  await fetchSholat(date);
                });
              });

              // auto highlight tanggal yang pertama kali dipilih (optional)
              // cari tombol yang date nya sama dengan selectedDate dari server
              const initial = `{{ $selectedDate->format('Y-m-d') }}`;
              const initBtn = Array.from(buttons).find(b => b.dataset.date === initial);
              if (initBtn) {
                initBtn.classList.add('ring-2', 'ring-gray-900');
                activeBtn = initBtn;
              }
            });
            </script>
        </div>
    </div>
</x-app-layout>
