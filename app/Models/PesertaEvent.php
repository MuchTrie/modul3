<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaEvent extends Model
{
    protected $table = 'peserta_event';
    protected $primaryKey = 'peserta_event_id';
    
    protected $fillable = [
        'status_daftar',
        'registered_at',
        'status_hadir',
        'checkin_at',
        'checkout_at',
        'marked_at',
        'catatan',
        'jemaah_id',
        'sesi_event_id',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
        'marked_at' => 'datetime',
    ];

    public function jemaah()
    {
        return $this->belongsTo(User::class, 'jemaah_id');
    }

    public function sesiEvent()
    {
        return $this->belongsTo(SesiEvent::class, 'sesi_event_id', 'sesi_event_id');
    }
}
