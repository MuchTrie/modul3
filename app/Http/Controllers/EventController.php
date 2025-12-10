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

    // Ambil 3 event terdekat (published) bulan ini
    $events = Event::where('status', 'published')
                   ->whereMonth('start_at', $month)
                   ->whereYear('start_at', $year)
                   ->orderBy('start_at', 'asc')
                   ->take(3)
                   ->get();

    return view('events.index', compact(
        'currentMonth', 'currentYear', 'daysInMonth', 'startDay', 'monthName', 'today', 'events'
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
}
