<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Masjid Al-Nassr</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-4xl w-full">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block mb-6">
                    <svg class="w-24 h-24 text-indigo-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Selamat Datang</h1>
                <h2 class="text-3xl font-semibold text-indigo-600 mb-2">Masjid Al-Nassr</h2>
                <p class="text-xl text-gray-600">Sistem Manajemen Kegiatan & Event Masjid</p>
            </div>

            <!-- Welcome Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 mb-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Assalamu'alaikum Warahmatullahi Wabarakatuh
                    </p>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Selamat datang di Sistem Manajemen Kegiatan Masjid Al-Nassr. 
                        Platform ini dirancang untuk memudahkan pengelolaan event, kegiatan, 
                        dan administrasi masjid secara terpadu.
                    </p>
                </div>

                <!-- Features -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center p-6 bg-blue-50 rounded-xl">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Kelola Event</h4>
                        <p class="text-sm text-gray-600">Manajemen event dan kegiatan masjid</p>
                    </div>

                    <div class="text-center p-6 bg-green-50 rounded-xl">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Jemaah</h4>
                        <p class="text-sm text-gray-600">Pendaftaran dan partisipasi event</p>
                    </div>

                    <div class="text-center p-6 bg-purple-50 rounded-xl">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Administrasi</h4>
                        <p class="text-sm text-gray-600">Pengelolaan data dan laporan</p>
                    </div>
                </div>

                <!-- Login CTA -->
                <div class="text-center">
                    <p class="text-gray-700 mb-6 text-lg">Silakan login untuk mengakses sistem</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg transform transition hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white hover:bg-gray-50 text-indigo-600 font-semibold rounded-lg shadow-lg border-2 border-indigo-600 transform transition hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Daftar Akun
                        </a>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Lihat Kalender Event
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-600">
                <p class="mb-2">© {{ date('Y') }} Masjid Al-Nassr. All rights reserved.</p>
                <p class="text-sm">Sistem Manajemen Kegiatan Masjid</p>
            </div>
        </div>
    </div>
</body>
</html>
