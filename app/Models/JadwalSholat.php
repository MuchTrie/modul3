<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSholat extends Model
{
    protected $table = 'jadwal_sholat';
    
    protected $fillable = [
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
