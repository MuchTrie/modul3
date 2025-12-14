<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Masjid Al-Nassr</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ Auth::user()->nama_lengkap }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm mb-2">Total Users</div>
                    <div class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm mb-2">Admin</div>
                    <div class="text-3xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm mb-2">Pengurus</div>
                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\User::where('role', 'pengurus')->count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-gray-500 text-sm mb-2">Jemaah</div>
                    <div class="text-3xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'jemaah')->count() }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="#" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <div>
                            <div class="font-semibold">Tambah User</div>
                            <div class="text-sm text-gray-500">Buat user baru</div>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <div>
                            <div class="font-semibold">Kelola User</div>
                            <div class="text-sm text-gray-500">CRUD semua user</div>
                        </div>
                    </a>
                    <a href="{{ route('events.index') }}" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <div class="font-semibold">Kelola Event</div>
                            <div class="text-sm text-gray-500">Lihat semua event</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Users</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Username</th>
                                <th class="text-left py-3 px-4">Nama</th>
                                <th class="text-left py-3 px-4">Role</th>
                                <th class="text-left py-3 px-4">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::latest()->take(10)->get() as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $user->username }}</td>
                                <td class="py-3 px-4">{{ $user->nama_lengkap }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($user->role === 'admin') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'pengurus') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
