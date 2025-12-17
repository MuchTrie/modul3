<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JemaahDashboardController extends Controller
{
    public function index(Request $request)
    {
        // tanggal terpilih dari query ?date=YYYY-MM-DD
        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->get('date'))->startOfDay()
            : now()->startOfDay();

        $today = now()->startOfDay();

        // kalender
        $monthStart = $selectedDate->copy()->startOfMonth();
        $daysInMonth = $monthStart->daysInMonth;
        $firstDayWeekIndex = $monthStart->dayOfWeek; // 0=Sunday ... 6=Saturday

        $monthName = $monthStart->translatedFormat('F Y');
        $fullDate  = $selectedDate->translatedFormat('l, j F Y');

        $prevMonth = $monthStart->copy()->subMonthNoOverflow()->startOfMonth();
        $nextMonth = $monthStart->copy()->addMonthNoOverflow()->startOfMonth();

        // batas maju (opsional)
        $maxFutureMonth = now()->copy()->addMonths(12)->startOfMonth();
        $canGoNext = $nextMonth->lte($maxFutureMonth);

        // jadwal sholat untuk tanggal terpilih (first load)
        [$jadwalSholat, $apiError] = $this->fetchSholatTimings($selectedDate);

        return view('dashboard.jemaah', compact(
            'selectedDate',
            'today',
            'monthStart',
            'daysInMonth',
            'firstDayWeekIndex',
            'monthName',
            'fullDate',
            'prevMonth',
            'nextMonth',
            'canGoNext',
            'jadwalSholat',
            'apiError'
        ));
    }

    public function sholatJson(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        $selectedDate = Carbon::parse($request->get('date'))->startOfDay();
        [$jadwalSholat, $apiError] = $this->fetchSholatTimings($selectedDate);

        return response()->json([
            'date' => $selectedDate->format('Y-m-d'),
            'fullDate' => $selectedDate->translatedFormat('l, j F Y'),
            'jadwalSholat' => $jadwalSholat,
            'apiError' => $apiError,
        ]);
    }

    private function fetchSholatTimings(Carbon $date): array
    {
        $jadwalSholat = [
            'Subuh' => '-', 'Dzuhur' => '-', 'Ashar' => '-', 'Maghrib' => '-', 'Isya' => '-',
        ];
        $apiError = null;

        try {
            // Koordinat Bandung (sesuaikan dengan lokasi masjid)
            $latitude = -6.9271;  // Latitude Bandung
            $longitude = 107.6411; // Longitude Bandung
            
            $url = 'https://api.aladhan.com/v1/timings/' . $date->format('d-m-Y');

            $res = Http::timeout(10)->get($url, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'method' => 2, // ISNA method
                'tune' => '0,0,0,0,0,0,0,0,3'
            ]);

            if (!$res->successful()) {
                return [$jadwalSholat, 'Gagal mengambil jadwal sholat (API error).'];
            }

            $data = $res->json();
            $timings = $data['data']['timings'] ?? null;

            if (!is_array($timings)) {
                return [$jadwalSholat, 'Format data jadwal sholat tidak valid.'];
            }

            $jadwalSholat = [
                'Subuh'   => $timings['Fajr'] ?? '-',
                'Dzuhur'  => $timings['Dhuhr'] ?? '-',
                'Ashar'   => $timings['Asr'] ?? '-',
                'Maghrib' => $timings['Maghrib'] ?? '-',
                'Isya'    => $timings['Isha'] ?? '-',
            ];

            return [$jadwalSholat, null];
        } catch (\Throwable $e) {
            // Fallback ke default jika error
            $jadwalSholat = [
                'Subuh' => '05:15',
                'Dzuhur' => '12:15',
                'Ashar' => '15:30',
                'Maghrib' => '17:50',
                'Isya' => '19:00',
            ];
            return [$jadwalSholat, 'Menggunakan jadwal default (API tidak tersedia)'];
        }
    }
}
