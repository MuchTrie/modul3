<!-- Wrapper (tetap relatif di konten) -->
<div id="debugMenu" class="relative z-50">

    <!-- Sidebar -->
    <div id="debugSidebar"
         class="fixed md:absolute left-0 top-0 h-full w-64 bg-white shadow-xl border-r transform transition-transform duration-300 -translate-x-full z-50">

        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg">Debug Menu</h3>
                <button id="closeMenu" class="p-2 hover:bg-gray-100 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-2">
                <!-- Homepage -->
                <a href="{{ route('events.index') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.index') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Homepage</span>
                </a>

                <!-- Kehadiran -->
                <a href="{{ route('attendance.list') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.attendance') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Kehadiran</span>
                </a>

                <!-- Detail Event -->
                <a href="{{ route('events.show', 1) }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.show') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                    <span>Detail Event</span>
                </a>

                <!-- Tambah Event -->
                <a href="{{ route('events.create') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.create') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Event</span>
                </a>

                <!-- Tambah Acara -->
                <a href="{{ route('events.create-routine') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.create-routine') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6M5 6h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                    </svg>
                    <span>Tambah Acara</span>
                </a>

                <!-- Jadwal Sholat -->
                <a href="{{ route('events.jadwal-solat') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.jadwal-solat') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 2L2 7h20L12 2zM12 22V12m0 0L4 7h16l-8 5z" />
                    </svg>
                    <span>Jadwal Sholat</span>
                </a>

                <!-- Pengajuan Event -->
                <a href="{{ route('events.pengajuan-event') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.pengajuan-event') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>

                <a href="{{ route('events.jadwal-solat') }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.jadwal-solat') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>🕌</span>
                    <span>Jadwal Sholat</span>
                </a>

                 <a href="{{ route('events.pengajuan-event') }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.pengajuan-event') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>📝</span>

                    <span>Pengajuan Event</span>
                </a>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const debugSidebar = document.getElementById('debugSidebar');
    const closeMenu = document.getElementById('closeMenu');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            debugSidebar.classList.remove('-translate-x-full');
        });
    }

    if (closeMenu) {
        closeMenu.addEventListener('click', () => {
            debugSidebar.classList.add('-translate-x-full');
        });
    }
});
</script>
@endpush
@endonce
