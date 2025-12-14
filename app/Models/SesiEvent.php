<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiEvent extends Model
{
    protected $table = 'sesi_event';
    protected $primaryKey = 'sesi_event_id';
    
    protected $fillable = [
        'start_at',
        'end_at',
        'title_override',
        'location_override',
        'published',
        'meta',
        'event_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'published' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaEvent::class, 'sesi_event_id', 'sesi_event_id');
    }

    public function pesertaEvent()
    {
        return $this->hasMany(PesertaEvent::class, 'sesi_event_id', 'sesi_event_id');
    }
}
