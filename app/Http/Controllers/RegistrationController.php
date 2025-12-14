<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\SesiEvent;
use App\Models\PesertaEvent;

class RegistrationController extends Controller
{
    /**
     * Register user for an event
     */
    public function register($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Check if event is published
        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'Event tidak tersedia untuk pendaftaran!');
        }
        
        // Check if event has available slots
        if ($event->kuota && $event->attendees >= $event->kuota) {
            return redirect()->back()->with('error', 'Kuota event sudah penuh!');
        }
        
        // Check if already registered
        $alreadyRegistered = PesertaEvent::whereHas('sesiEvent', function($q) use ($event_id) {
            $q->where('event_id', $event_id);
        })->where('jemaah_id', auth()->id())->exists();
        
        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar dalam event ini!');
        }
        
        // Get or create a default session for this event
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
        
        // Register user to the event session
        PesertaEvent::create([
            'sesi_event_id' => $sesi->sesi_event_id,
            'jemaah_id' => auth()->id(),
            'status' => 'hadir',
        ]);
        
        // Increment attendees count
        $event->increment('attendees');
        $sesi->increment('attendees');
        
        return redirect()->back()->with('success', 'Berhasil mendaftar event!');
    }
    
    /**
     * Unregister user from an event
     */
    public function unregister($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Find registration
        $registration = PesertaEvent::whereHas('sesiEvent', function($q) use ($event_id) {
            $q->where('event_id', $event_id);
        })->where('jemaah_id', auth()->id())->first();
        
        if (!$registration) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar dalam event ini!');
        }
        
        $sesi = $registration->sesiEvent;
        
        // Delete registration
        $registration->delete();
        
        // Decrement attendees count
        $event->decrement('attendees');
        $sesi->decrement('attendees');
        
        return redirect()->back()->with('success', 'Berhasil membatalkan pendaftaran!');
    }
}
