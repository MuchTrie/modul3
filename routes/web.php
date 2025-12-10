<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

// Homepage - Jadwal Kegiatan & Event
Route::get('/', [EventController::class, 'index'])->name('events.index');

// Form Tambah Event
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

// Form Tambah Acara Rutinan
Route::get('/events/create-routine', [EventController::class, 'createRoutine'])->name('events.create-routine');

// Detail Event
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Kehadiran Jamaah
Route::get('/events/{id}/attendance', [EventController::class, 'attendance'])->name('events.attendance');

Route::get('/jadwal-sholat', function () {
    return view('events.jadwal-solat');
})->name('events.jadwal-solat');

Route::get('/pengajuan-event', function () {
    return view('events.pengajuan-event');
})->name('events.pengajuan-event');




