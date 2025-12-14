<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';
    
    protected $fillable = [
        'created_by',
        'nama_kegiatan',
        'jenis_kegiatan',
        'lokasi',
        'start_at',
        'end_at',
        'kuota',
        'start_time',
        'end_time',
        'status',
        'rule',
        'poster',
        'description',
        'attendees',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'attendees' => 'integer',
        'kuota' => 'integer',
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    public function sesiEvent()
    {
        return $this->hasMany(SesiEvent::class, 'event_id', 'event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get count of participants for this event
    public function getPesertaAttribute()
    {
        // Count unique participants across all sessions of this event
        return \DB::table('peserta_event')
            ->join('sesi_event', 'peserta_event.sesi_event_id', '=', 'sesi_event.sesi_event_id')
            ->where('sesi_event.event_id', $this->event_id)
            ->distinct('peserta_event.jemaah_id')
            ->select('peserta_event.jemaah_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
