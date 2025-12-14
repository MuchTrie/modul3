<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;   // ← tambahkan ini
use Carbon\Carbon;

class EventController extends Controller
{
public function index(Request $request)
{
    $month = $request->query('month', now()->month);
    $year  = $request->query('year', now()->year);

    $date = \Carbon\Carbon::create($year, $month, 1);

    $currentMonth = $date->month;
    $currentYear  = $date->year;
    $monthName    = $date->translatedFormat('F Y');
    $daysInMonth  = $date->daysInMonth;
    $startDay     = $date->dayOfWeek;

    $today = \Carbon\Carbon::today();

    // Ambil event bulan yang dipilih (published)
    $events = Event::where('status', 'published')
                   ->whereMonth('start_at', $month)
                   ->whereYear('start_at', $year)
                   ->orderBy('start_at', 'asc')
                   ->get();

    // Kegiatan rutin (tidak terikat tanggal spesifik)
    $kegiatanRutin = [
        [
            'nama' => 'Sholat Berjamaah 5 Waktu',
            'jadwal' => 'Setiap hari',
            'deskripsi' => 'Sholat wajib berjamaah di masjid',
            'icon' => 'prayer'
        ],
        [
            'nama' => 'Kajian Rutin Malam Jumat',
            'jadwal' => 'Setiap Kamis malam, 19:30 WIB',
            'deskripsi' => 'Kajian Islam dengan tema yang berbeda setiap minggu',
            'icon' => 'book'
        ],
        [
            'nama' => 'TPA (Taman Pendidikan Al-Quran)',
            'jadwal' => 'Senin - Jumat, 16:00 - 17:30 WIB',
            'deskripsi' => 'Belajar membaca Al-Quran untuk anak-anak',
            'icon' => 'academic'
        ],
        [
            'nama' => 'Tarawih & Tahajud Ramadhan',
            'jadwal' => 'Setiap bulan Ramadhan',
            'deskripsi' => 'Sholat tarawih dan tahajud berjamaah',
            'icon' => 'moon'
        ],
    ];

    return view('events.index', compact(
        'currentMonth', 'currentYear', 'daysInMonth', 'startDay', 'monthName', 'today', 'events', 'kegiatanRutin'
    ));
}


    public function create()
    {
        return view('events.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nama_kegiatan' => 'required|string|max:255',
        'jenis_kegiatan' => 'nullable|string|max:100',
        'lokasi' => 'nullable|string|max:255',
        'start_at' => 'required|date',
        'end_at' => 'required|date|after_or_equal:start_at',
        'kuota' => 'nullable|integer|min:0',
        'rule' => 'nullable|string',
        'description' => 'nullable|string',
        'status' => 'required|in:draft,published,cancelled',
        'poster' => 'nullable|image|max:2048',
    ]);

    $event = new Event();
    $event->nama_kegiatan = $request->nama_kegiatan;
    $event->jenis_kegiatan = $request->jenis_kegiatan;
    $event->lokasi = $request->lokasi;
    $event->start_at = $request->start_at;
    $event->end_at = $request->end_at;
    $event->kuota = $request->kuota;
    $event->rule = $request->rule;
    $event->description = $request->description;
    $event->status = $request->status;
    $event->attendees = 0;
    $event->created_by = auth()->id(); // Track who created the event

    // === SIMPAN POSTER ===
    if ($request->hasFile('poster')) {
        $posterPath = $request->file('poster')->store('posters', 'public');
        $event->poster = $posterPath;   // ← WAJIB
    } else {
        $event->poster = null;
    }

    $event->save();

    return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan!');
}



    public function createRoutine()
    {
        return view('events.create-routine');
    }

    public function show($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();

        return view('events.show', compact('event'));
    }



    public function attendanceList()
    {
    $events = \App\Models\Event::orderBy('start_at', 'asc')->get();

    return view('events.attendance-list', compact('events'));
    }

    
    public function attendance(Event $event)
    {
        return view('events.attendance', compact('event'));
    }

    
     public function pengajuan()
    {
        // Ambil semua event dari DB
        $pengajuanEvents = Event::all()->map(function($event) {
            return [
                'id_jemaah'   => $event->event_id,
                'judul'       => $event->nama_kegiatan,
                'deskripsi'   => $event->description ?? '-',
                'rule_usulan' => $event->rule ?? '-',
                'tgl_mulai'   => $event->start_at,
                'tgl_selesai' => $event->end_at,
                'status'      => $event->status,
                'catatan'     => '-', // bisa diisi kolom lain kalau ada
            ];
        });

        return view('events.pengajuan-event', compact('pengajuanEvents'));
    }

    public function jadwalSolat()
    {
        return view('events.jadwal-solat');
    }
}
