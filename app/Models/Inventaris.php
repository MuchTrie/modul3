<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';
    protected $primaryKey = 'id_asset';
    
    protected $fillable = [
        'nama_asset',
        'jenis_asset',
        'kondisi',
        'tahun_peroleh',
        'status',
    ];

    public function perawatan()
    {
        return $this->hasMany(Perawatan::class, 'id_asset', 'id_asset');
    }

    public function laporanPerawatan()
    {
        return $this->hasMany(LaporanPerawatan::class, 'id_asset', 'id_asset');
    }
}
