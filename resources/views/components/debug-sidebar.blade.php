<!-- Debug Menu Sidebar -->
<div id="debugMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="fixed left-0 top-0 h-full w-64 bg-white shadow-xl transform transition-transform duration-300 -translate-x-full" id="debugSidebar">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg">🔧 Debug Menu</h3>
                <button id="closeMenu" class="p-2 hover:bg-gray-100 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-2">
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.index') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg font-medium">
                    <span>📅</span>
                    <span>Homepage</span>
                </a>
                <a href="{{ route('events.attendance', 1) }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.attendance') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>✅</span>
                    <span>Kehadiran</span>
                </a>
                <a href="{{ route('events.show', 1) }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.show') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>📄</span>
                    <span>Detail Event</span>
                </a>
                <a href="{{ route('events.create') }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.create') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>➕</span>
                    <span>Tambah Event</span>
                </a>
                <a href="{{ route('events.create-routine') }}" class="flex items-center gap-3 px-4 py-3 {{ Request::routeIs('events.create-routine') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-100' }} rounded-lg">
                    <span>📋</span>
                    <span>Tambah Acara</span>
                </a>
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

<script>
    // Debug Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle   = document.getElementById('menuToggle');
        const debugMenu    = document.getElementById('debugMenu');
        const debugSidebar = document.getElementById('debugSidebar');
        const closeMenu    = document.getElementById('closeMenu');

        if (menuToggle && debugMenu && debugSidebar) {
            menuToggle.addEventListener('click', () => {
                debugMenu.classList.remove('hidden');
                setTimeout(() => {
                    debugSidebar.classList.remove('-translate-x-full');
                }, 10);
            });
        }

        if (closeMenu && debugMenu && debugSidebar) {
            closeMenu.addEventListener('click', () => {
                debugSidebar.classList.add('-translate-x-full');
                setTimeout(() => {
                    debugMenu.classList.add('hidden');
                }, 300);
            });
        }

        if (debugMenu && debugSidebar) {
            debugMenu.addEventListener('click', (e) => {
                if (e.target === debugMenu) {
                    debugSidebar.classList.add('-translate-x-full');
                    setTimeout(() => {
                        debugMenu.classList.add('hidden');
                    }, 300);
                }
            });
        }
    });
</script>
