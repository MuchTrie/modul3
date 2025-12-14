<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class ApprovalController extends Controller
{
    /**
     * Display pending events for approval
     */
    public function index()
    {
        $pendingEvents = Event::where('status', 'draft')
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('pengurus.approvals', compact('pendingEvents'));
    }

    /**
     * Approve an event
     */
    public function approve($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Only approve if status is draft
        if ($event->status === 'draft') {
            $event->status = 'published';
            $event->save();
            
            return redirect()->back()->with('success', 'Event berhasil disetujui!');
        }
        
        return redirect()->back()->with('error', 'Event tidak dapat disetujui!');
    }

    /**
     * Reject an event
     */
    public function reject($event_id)
    {
        $event = Event::where('event_id', $event_id)->firstOrFail();
        
        // Only reject if status is draft
        if ($event->status === 'draft') {
            $event->status = 'cancelled';
            $event->save();
            
            return redirect()->back()->with('success', 'Event berhasil ditolak!');
        }
        
        return redirect()->back()->with('error', 'Event tidak dapat ditolak!');
    }
}
