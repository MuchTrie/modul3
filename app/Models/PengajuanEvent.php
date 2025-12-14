<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanEvent extends Model
{
    protected $table = 'pengajuan_event';
    protected $primaryKey = 'pengajuan_event_id';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'rule_usulan',
        'tgl_mulai_usulan',
        'tgl_selesai_usulan',
        'status',
        'catatan',
        'jemaah_id',
        'diinput_oleh_jemaah_id',
        'approved_by_jemaah_id',
        'approved_at',
    ];

    protected $casts = [
        'tgl_mulai_usulan' => 'date',
        'tgl_selesai_usulan' => 'date',
        'approved_at' => 'datetime',
    ];

    public function jemaah()
    {
        return $this->belongsTo(User::class, 'jemaah_id');
    }

    public function diinputOleh()
    {
        return $this->belongsTo(User::class, 'diinput_oleh_jemaah_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_jemaah_id');
    }
}
