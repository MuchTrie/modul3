<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\SesiEvent;
use App\Models\PesertaEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    // Method untuk fetch jadwal sholat dari API
    private function getPrayerTimes()
    {
        try {
            // Koordinat Bandung (sesuaikan dengan lokasi masjid Anda)
            $latitude = -6.9271;  // Latitude Bandung
            $longitude = 107.6411; // Longitude Bandung
            $date = now('Asia/Jakarta')->format('d-m-Y');
            
            $response = Http::get('https://api.aladhan.com/v1/timings/' . $date, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'method' => 2, // ISNA method
                'tune' => '0,0,0,0,0,0,0,0,3'
            ]);
            
            if ($response->successful()) {
                $timings = $response->json()['data']['timings'];
                return [
                    'Subuh' => $timings['Fajr'],
                    'Dzuhur' => $timings['Dhuhr'],
                    'Ashar' => $timings['Asr'],
                    'Magrib' => $timings['Maghrib'],
                    'Isya' => $timings['Isha']
                ];
            }
        } catch (\Exception $e) {
            // Fallback default times jika API tidak bisa diakses
        }
        
        // Default prayer times (fallback)
        return [
            'Subuh' => '05:15',
            'Dzuhur' => '12:15',
            'Ashar' => '15:30',
            'Magrib' => '17:50',
            'Isya' => '19:15'
        ];
    }

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

    // Ambil event bulan yang dipilih untuk kalender (published)
    $events = Event::where('status', 'published')
                   ->whereMonth('start_at', $month)
                   ->whereYear('start_at', $year)
                   ->orderBy('start_at', 'asc')
                   ->get();

    // Ambil 3 event terdekat yang akan datang (untuk section "Event Terdekat")
    // Tidak terbatas pada bulan yang dipilih di kalender
    $upcomingEvents = Event::where('status', 'published')
                           ->where('start_at', '>=', now())
                           ->orderBy('start_at', 'asc')
                           ->limit(3)
                           ->get();

    // Get prayer times
    $prayerTimes = $this->getPrayerTimes();

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
        'currentMonth', 'currentYear', 'daysInMonth', 'startDay', 'monthName', 'today', 'events', 'upcomingEvents', 'kegiatanRutin', 'prayerTimes'
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
    $event->status = 'draft'; // Panitia always submits as draft/pending
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

    return redirect()->route('events.mine')->with('success', 'Event berhasil diajukan! Menunggu approval dari DKM.');
}

    /**
     * Show create form for admin
     */
    public function adminCreate()
    {
        return view('admin.events.create');
    }

    /**
     * Store event by admin (auto-published)
     */
    public function adminStore(Request $request)
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
        $event->status = 'published'; // Admin creates event as published
        $event->attendees = 0;
        $event->created_by = auth()->id();

        // Save poster
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $event->poster = $posterPath;
        } else {
            $event->poster = null;
        }

        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
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

    /**
     * List events created by the authenticated panitia
     */
    public function mine(Request $request)
    {
        $events = Event::where('created_by', auth()->id())
            ->orderBy('start_at', 'desc')
            ->paginate(15);

        return view('events.mine', compact('events'));
    }

    /**
     * Cancel an event created by the authenticated user
     */
    public function cancel($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();

        // only creator or admin can cancel
        if ($event->created_by != auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $event->status = 'cancelled';
        $event->save();

        return redirect()->back()->with('success', 'Event berhasil dibatalkan.');
    }

    public function jadwalSolat()
    {
        return view('events.jadwal-solat');
    }

    public function adminIndex(Request $request)
    {
        $query = Event::with('creator')->orderBy('start_at', 'desc');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan/tahun
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('start_at', $request->month);
        }
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('start_at', $request->year);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }

        $events = $query->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function edit($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();

        // Check authorization
        if ($event->created_by != auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jenis_kegiatan' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'kuota' => 'nullable|integer|min:1',
            'status' => 'nullable|in:draft,published,cancelled',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event->fill($validated);

        // Panitia cannot change status, only admin can
        if (auth()->user()->role === 'panitia') {
            // Don't update status for panitia
            $event->status = $event->getOriginal('status');
        }

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($event->poster && \Storage::disk('public')->exists($event->poster)) {
                \Storage::disk('public')->delete($event->poster);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
            $event->poster = $posterPath;
        }

        $event->save();

        // Redirect based on user role
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate!');
        } else {
            return redirect()->route('events.mine')->with('success', 'Event berhasil diupdate!');
        }
    }

    public function destroy($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Delete poster if exists
        if ($event->poster && \Storage::disk('public')->exists($event->poster)) {
            \Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    public function storeRoutine(Request $request)
    {
        // Placeholder untuk create routine events
        return redirect()->route('events.mine')->with('success', 'Event rutin berhasil ditambahkan!');
    }

    /**
     * Show panitia's published events for managing participants
     */
    public function manageParticipants()
    {
        $user = auth()->user();
        
        // Get only published events created by panitia
        $events = Event::where('created_by', $user->id)
            ->where('status', 'published')
            ->with(['peserta' => function($q) {
                $q->with('user');
            }])
            ->orderBy('start_at', 'desc')
            ->paginate(15);

        return view('events.manage-participants', compact('events'));
    }

    /**
     * Show participants for a specific event (panitia view)
     */
    public function eventParticipants($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if panitia is the creator
        if ($event->created_by != auth()->id()) {
            abort(403);
        }

        // Get registered participants with their details
        $registeredParticipants = PesertaEvent::whereHas('sesiEvent', function($q) use ($event_id) {
            $q->where('event_id', $event_id);
        })->with('user')->get();

        // Get registered user IDs
        $registeredUserIds = $registeredParticipants->pluck('jemaah_id')->toArray();
        
        // Get available jamaah (not yet registered to this event)
        $availableJamaah = User::where('role', 'jemaah')
            ->whereNotIn('id', $registeredUserIds)
            ->orderBy('nama_lengkap')
            ->get();

        return view('events.event-participants', compact('event', 'availableJamaah', 'registeredParticipants'));
    }

    /**
     * Register a participant to event (panitia register jamaah)
     */
    public function registerParticipant(Request $request, $event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if panitia is the creator
        if ($event->created_by != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'jemaah_id' => 'required|exists:users,id',
        ]);

        // Check if event is published
        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'Event belum disetujui untuk pendaftaran!');
        }

        // Check if already registered
        $alreadyRegistered = PesertaEvent::whereHas('sesiEvent', function($q) use ($event_id) {
            $q->where('event_id', $event_id);
        })->where('jemaah_id', $request->jemaah_id)->exists();

        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Jamaah sudah terdaftar dalam event ini!');
        }

        // Get or create session
        $sesi = SesiEvent::firstOrCreate(
            ['event_id' => $event_id],
            [
                'nama_sesi' => 'Sesi Utama',
                'start_at' => $event->start_at,
                'end_at' => $event->end_at,
                'lokasi' => $event->lokasi,
                'kuota' => $event->kuota,
                'attendees' => 0,
            ]
        );

        // Register participant
        PesertaEvent::create([
            'sesi_event_id' => $sesi->sesi_event_id,
            'jemaah_id' => $request->jemaah_id,
            'status_daftar' => 'pending',
            'registered_at' => now(),
        ]);

        $event->increment('attendees');

        return redirect()->back()->with('success', 'Jamaah berhasil didaftarkan ke event!');
    }

    /**
     * Remove a participant from event (panitia)
     */
    public function removeParticipant($event_id, $peserta_event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if panitia is the creator
        if ($event->created_by != auth()->id()) {
            abort(403);
        }

        $peserta = PesertaEvent::where('peserta_event_id', $peserta_event_id)->firstOrFail();
        
        $sesi = $peserta->sesiEvent;
        
        // Delete registration
        $peserta->delete();
        
        // Decrement attendees
        $event->decrement('attendees');

        return redirect()->back()->with('success', 'Peserta berhasil dihapus dari event!');
    }

    /**
     * View attendance list for event (panitia)
     */
    public function attendanceView($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if panitia is the creator
        if ($event->created_by != auth()->id()) {
            abort(403);
        }

        // Get all participants registered for this event
        $participants = PesertaEvent::whereHas('sesiEvent', function($q) use ($event_id) {
            $q->where('event_id', $event_id);
        })->with(['user', 'sesiEvent.event'])->get();

        return view('events.attendance', compact('event', 'participants'));
    }

    /**
     * Mark attendance for participants (panitia)
     */
    public function markAttendance(Request $request, $event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if panitia is the creator
        if ($event->created_by != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.peserta_event_id' => 'required|exists:peserta_event,peserta_event_id',
            'attendance.*.status_hadir' => 'required|in:hadir,tidak hadir,belum diketahui',
        ]);

        foreach ($request->input('attendance') as $data) {
            $peserta = PesertaEvent::where('peserta_event_id', $data['peserta_event_id'])->firstOrFail();
            
            // Update attendance status
            $peserta->update([
                'status_hadir' => $data['status_hadir'],
                'marked_at' => now(),
                'checkin_at' => now(),
                'checkout_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Absen berhasil dicatat!');
    }

    /**
     * Display calendar page for jemaah
     */
    public function calendar(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year  = $request->query('year', now()->year);

        $date = \Carbon\Carbon::create($year, $month, 1);

        $currentMonth = $date->month;
        $currentYear  = $date->year;
        $daysInMonth  = $date->daysInMonth;
        $startDay     = $date->dayOfWeek;
        $today = \Carbon\Carbon::today();

        // Ambil semua event published
        $events = Event::where('status', 'published')
                       ->whereMonth('start_at', $month)
                       ->whereYear('start_at', $year)
                       ->orderBy('start_at', 'asc')
                       ->get();

        // Group events by date
        $eventsByDate = [];
        foreach ($events as $event) {
            $day = \Carbon\Carbon::parse($event->start_at)->day;
            if (!isset($eventsByDate[$day])) {
                $eventsByDate[$day] = [];
            }
            $eventsByDate[$day][] = $event;
        }

        // Months for dropdown
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // All events list (upcoming and past)
        // Upcoming: event yang belum selesai (end_at >= hari ini)
        $upcomingEvents = Event::where('status', 'published')
                               ->where('end_at', '>=', now())
                               ->orderBy('start_at', 'asc')
                               ->paginate(10, ['*'], 'upcoming');

        // Past: event yang sudah selesai (end_at < hari ini)
        $pastEvents = Event::where('status', 'published')
                           ->where('end_at', '<', now())
                           ->orderBy('start_at', 'desc')
                           ->paginate(10, ['*'], 'past');

        return view('jemaah.calendar', compact(
            'currentMonth', 'currentYear', 'daysInMonth', 'startDay', 
            'today', 'eventsByDate', 'months', 'upcomingEvents', 'pastEvents'
        ));
    }

    /**
     * Display my events for jemaah
     */
    public function myEvents()
    {
        $jemaahId = auth()->id();
        
        // Get events that the user is registered for
        $myEvents = PesertaEvent::with(['sesiEvent.event', 'sesiEvent'])
                                ->where('jemaah_id', $jemaahId)
                                ->get()
                                ->map(function($peserta) {
                                    return [
                                        'event' => $peserta->sesiEvent->event,
                                        'sesi' => $peserta->sesiEvent,
                                        'attendance_status' => $peserta->status_hadir, // Changed from attendance_status
                                        'registered_at' => $peserta->created_at
                                    ];
                                })
                                ->sortByDesc(function($item) {
                                    return $item['event']->start_at;
                                });

        return view('jemaah.my-events', compact('myEvents'));
    }
}

