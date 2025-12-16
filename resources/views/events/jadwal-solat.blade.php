@php
    date_default_timezone_set('Asia/Jakarta');

    // Mapping hari & bulan ke Bahasa Indonesia
    $namaHari = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];

    $namaBulan = [
        'January'   => 'Januari',
        'February'  => 'Februari',
        'March'     => 'Maret',
        'April'     => 'April',
        'May'       => 'Mei',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'August'    => 'Agustus',
        'September' => 'September',
        'October'   => 'Oktober',
        'November'  => 'November',
        'December'  => 'Desember',
    ];

    // Tanggal yang dipilih dari query (?date=YYYY-MM-DD), default: hari ini
    $selectedDateStr = request('date');
    $selectedDate = $selectedDateStr
        ? DateTime::createFromFormat('Y-m-d', $selectedDateStr) ?: new DateTime()
        : new DateTime();

    // Untuk highlight "hari ini" di kalender
    $today = new DateTime();

    // Bulan yang sedang ditampilkan di kalender = bulan dari selectedDate
    $monthStart = (clone $selectedDate)->modify('first day of this month');

    // Bulan & tahun (contoh: "Desember 2025")
    $bulanEnglish = $monthStart->format('F');
    $monthName = ($namaBulan[$bulanEnglish] ?? $bulanEnglish) . ' ' . $monthStart->format('Y');

    // Tanggal lengkap dari selectedDate (contoh: "Senin, 09 Desember 2025")
    $hariEnglish = $selectedDate->format('l');
    $hariIndo = $namaHari[$hariEnglish] ?? $hariEnglish;

    $bulanTodayEnglish = $selectedDate->format('F');
    $bulanTodayIndo = $namaBulan[$bulanTodayEnglish] ?? $bulanTodayEnglish;

    $fullDate = $hariIndo . ', ' . $selectedDate->format('d ') . $bulanTodayIndo . $selectedDate->format(' Y');

    // Jumlah hari dalam bulan yang sedang ditampilkan
    $daysInMonth = (int) $monthStart->format('t');

    // Index hari pertama di bulan ini: 0 = Minggu ... 6 = Sabtu
    $firstDayWeekIndex = (int) $monthStart->format('w');

    // Batas maksimal bulan (minimal sampai Januari 2026)
    $maxMonth = new DateTime('2026-01-01');
    $canGoNext = $monthStart < $maxMonth;

    // Prev & next month untuk tombol navigasi
    $prevMonth = (clone $monthStart)->modify('-1 month');
    $nextMonth = (clone $monthStart)->modify('+1 month');

    // Contoh jadwal sholat (sementara sama untuk semua hari)
    $jadwalSholat = [
        'Subuh'   => '04:15',
        'Dzuhur'  => '11:45',
        'Ashar'   => '15:10',
        'Maghrib' => '17:55',
        'Isya'    => '19:05',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Sholat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            background: #f4f5f7;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 24px;
        }

        .card-main {
            background: #ffffff;
            width: 100%;
            max-width: 850px;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
            padding: 24px 24px 32px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .menu-icon {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .menu-icon span {
            display: block;
            width: 14px;
            height: 2px;
            background: #111827;
            position: relative;
        }

        .menu-icon span::before,
        .menu-icon span::after {
            content: '';
            position: absolute;
            left: 0;
            width: 14px;
            height: 2px;
            background: #111827;
        }

        .menu-icon span::before { top: -5px; }
        .menu-icon span::after  { top:  5px; }

        .title-wrapper {
            flex: 1;
            text-align: center;
        }

        .title-main {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .title-sub {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 12px 0 20px;
        }

        .layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Calendar card */
        .calendar-card {
            background: #f9fafb;
            border-radius: 24px;
            padding: 18px 18px 22px;
        }

        .calendar-month {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .calendar-month-title {
            font-weight: 600;
            color: #111827;
            font-size: 16px;
        }

        .calendar-nav {
            display: flex;
            gap: 6px;
        }

        .nav-btn {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #6b7280;
            background: #ffffff;
        }

        .nav-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .nav-btn a {
            text-decoration: none;
            color: inherit;
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
        }

        .weekday-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            margin-bottom: 8px;
        }

        .day-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
        }

        .day-cell {
            height: 36px;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
            font-size: 13px;
            color: #111827;
        }

        .day-cell.empty {
            background: transparent;
            box-shadow: none;
        }

        .day-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            border-radius: inherit;
        }

        .day-cell.selected {
            background: #111827;
            color: #f9fafb;
            font-weight: 600;
        }

        .day-cell.today-outline {
            border: 2px solid #111827;
        }

        /* Jadwal sholat card */
        .jadwal-card {
            background: #f9fafb;
            border-radius: 24px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .jadwal-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 4px;
        }

        .jadwal-date {
            font-size: 12px;
            color: #6b7280;
            margin: 0 0 8px;
        }

        .jadwal-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .jadwal-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }

        .jadwal-left {
            display: flex;
            flex-direction: column;
        }

        .jadwal-name {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .jadwal-label {
            font-size: 11px;
            color: #9ca3af;
        }

        .jadwal-time {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .badge-today {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 9px;
            border-radius: 999px;
            background: #111827;
            color: #f9fafb;
            font-size: 11px;
            margin-top: 4px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #22c55e;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="card-main">
        <!-- HEADER -->
        <div class="header">
            <div class="menu-icon">
                <span></span>
            </div>
            <div class="title-wrapper">
                <h1 class="title-main">Jadwal Sholat Berjamaah</h1>
                <p class="title-sub">Masjid Al-Nassr</p>
            </div>
        </div>

        <div class="divider"></div>

        <!-- KONTEN -->
        <div class="layout-grid">
            <!-- KALENDER -->
            <div class="calendar-card">
                <div class="calendar-month">
                    <div class="calendar-month-title">
                        {{ $monthName }}
                    </div>
                    <div class="calendar-nav">
                        {{-- Tombol bulan sebelumnya --}}
                        <div class="nav-btn">
                            <a href="{{ route('events.jadwal-solat', ['date' => $prevMonth->format('Y-m-d')]) }}">‹</a>
                        </div>

                        {{-- Tombol bulan berikutnya (dibatasi sampai maxMonth) --}}
                        <div class="nav-btn {{ $canGoNext ? '' : 'disabled' }}">
                            @if($canGoNext)
                                <a href="{{ route('events.jadwal-solat', ['date' => $nextMonth->format('Y-m-d')]) }}">›</a>
                            @else
                                <span>›</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="weekday-row">
                    <div>S</div>
                    <div>M</div>
                    <div>T</div>
                    <div>W</div>
                    <div>T</div>
                    <div>F</div>
                    <div>S</div>
                </div>

                <div class="day-grid">
                    {{-- Slot kosong sebelum tanggal 1 --}}
                    @for ($i = 0; $i < $firstDayWeekIndex; $i++)
                        <div class="day-cell empty"></div>
                    @endfor

                    {{-- Tanggal-tanggal --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            // DateTime untuk hari ini dalam bulan yang sedang ditampilkan
                            $thisDate = (clone $monthStart)->setDate(
                                (int) $monthStart->format('Y'),
                                (int) $monthStart->format('m'),
                                $day
                            );
                            $isSelected = $thisDate->format('Y-m-d') === $selectedDate->format('Y-m-d');
                            $isToday = $thisDate->format('Y-m-d') === $today->format('Y-m-d');

                            $classes = 'day-cell';
                            if ($isSelected) {
                                $classes .= ' selected';
                            } elseif ($isToday) {
                                $classes .= ' today-outline';
                            }
                        @endphp
                        <div class="{{ $classes }}">
                            <a class="day-link"
                               href="{{ route('events.jadwal-solat', ['date' => $thisDate->format('Y-m-d')]) }}">
                                {{ $day }}
                            </a>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- JADWAL SHOLAT -->
            <div class="jadwal-card">
                <div>
                    <p class="jadwal-title">Jadwal Sholat</p>
                    <p class="jadwal-date">
                        {{ $fullDate }}
                    </p>
                    <span class="badge-today">
                        <span class="badge-dot"></span>
                        Jadwal sholat untuk tanggal ini
                    </span>
                </div>

                <div class="jadwal-list">
                    @foreach ($jadwalSholat as $nama => $jam)
                        <div class="jadwal-item">
                            <div class="jadwal-left">
                                <span class="jadwal-name">{{ $nama }}</span>
                                <span class="jadwal-label">Masjid Al-Nassr</span>
                            </div>
                            <div class="jadwal-time">
                                {{ $jam }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>