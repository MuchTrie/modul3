@php
    // Contoh data dummy (nanti bisa diganti dari database)
    $pengajuanEvents = [
        [
            'id_jemaah'       => 'J001',
            'judul'           => 'Kajian Rutin Malam Jumat',
            'deskripsi'       => 'Pengajuan kajian rutin setiap malam Jumat ba\'da Isya.',
            'rule_usulan'     => 'Untuk umum, wajib daftar, maksimal 100 peserta.',
            'tgl_mulai'       => '2025-12-20',
            'tgl_selesai'     => '2025-12-20',
            'status'          => 'Pending',
            'catatan'         => '-',
        ],
        [
            'id_jemaah'       => 'J014',
            'judul'           => 'Pesantren Kilat Liburan',
            'deskripsi'       => 'Kegiatan pesantren kilat selama 3 hari untuk remaja.',
            'rule_usulan'     => 'Untuk remaja masjid, izin orang tua wajib.',
            'tgl_mulai'       => '2025-12-26',
            'tgl_selesai'     => '2025-12-28',
            'status'          => 'Disetujui',
            'catatan'         => 'Silakan lanjut ke tahap persiapan panitia.',
        ],
        [
            'id_jemaah'       => 'J007',
            'judul'           => 'Lomba Adzan Anak',
            'deskripsi'       => 'Lomba adzan untuk anak-anak tingkat SD.',
            'rule_usulan'     => 'Peserta laki-laki, memakai pakaian muslim rapi.',
            'tgl_mulai'       => '2026-01-05',
            'tgl_selesai'     => '2026-01-05',
            'status'          => 'Ditolak',
            'catatan'         => 'Jadwal bentrok dengan agenda lain, mohon ajukan jadwal baru.',
        ],
    ];

    function statusClass($status) {
        return match ($status) {
            'Pending'   => 'bg-yellow-100 text-yellow-800',
            'Disetujui' => 'bg-green-100 text-green-800',
            'Ditolak'   => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-700',
        };
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Event</title>
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
            max-width: 960px;
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

        .table-wrapper {
            margin-top: 8px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #f3f4f6;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            font-weight: 600;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:nth-child(every) {
            background: #ffffff;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:hover {
            background: #eef2ff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .text-muted {
            color: #6b7280;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .notes {
            font-size: 11px;
            color: #6b7280;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .card-main {
                padding: 16px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }

            .title-main {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="card-main">
        <!-- HEADER -->
        <div class="header">
            <div class="menu-icon" id="menuToggle">
                <span></span>
            </div>
            <div class="title-wrapper">
                <h1 class="title-main">Pengajuan Event</h1>
                <p class="title-sub">Daftar usulan event yang menunggu persetujuan admin</p>
            </div>
        </div>

        <div class="divider"></div>

        <!-- TABEL PENGAJUAN -->
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>ID Jemaah</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Rule Usulan</th>
                    <th class="text-nowrap">Tgl Mulai Usulan</th>
                    <th class="text-nowrap">Tgl Selesai Usulan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pengajuanEvents as $event)
                    <tr>
                        <td>{{ $event['id_jemaah'] }}</td>
                        <td>{{ $event['judul'] }}</td>
                        <td class="text-muted">{{ $event['deskripsi'] }}</td>
                        <td class="text-muted">{{ $event['rule_usulan'] }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($event['tgl_mulai'])->format('d-m-Y') }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($event['tgl_selesai'])->format('d-m-Y') }}</td>
                        <td>
                            @php
                                $status = $event['status'];
                                $badgeClass = $status === 'Pending'
                                    ? 'badge-yellow'
                                    : ($status === 'Disetujui' ? 'badge-green' : 'badge-red');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        </td>
                        <td class="text-muted">{{ $event['catatan'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <p class="notes">
            *Halaman ini hanya tampilan daftar pengajuan. Aksi ACC / tolak bisa ditambahkan nanti di kolom aksi
            jika sudah ada alur persetujuan dari backend.
        </p>
    </div>
</div>
</body>
</html>
