<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events'; // nama tabel

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'image'
    ];
}
