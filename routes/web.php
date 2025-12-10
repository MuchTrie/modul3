<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventAttendanceController;
// Homepage - Jadwal Kegiatan & Event
Route::get('/', [EventController::class, 'index'])->name('events.index');

// -----------------------
// Event Management Routes
// -----------------------

// Form Tambah Event
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

// Simpan Event Baru
Route::post('/events', [EventController::class, 'store'])->name('events.store');

// Form Tambah Acara Rutin
Route::get('/events/create-routine', [EventController::class, 'createRoutine'])->name('events.create-routine');

// Simpan Acara Rutin
Route::post('/events/routine', [EventController::class, 'storeRoutine'])->name('events.store-routine');

// Detail Event
Route::get('/events/{event_id}', [EventController::class, 'show'])->name('events.show');

// Edit Event
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');

// Update Event
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');

// Hapus Event
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

// Kehadiran Jamaah

// List event (tanpa parameter)
Route::get('/attendance', [EventController::class, 'attendanceList'])
    ->name('attendance.list');

// Halaman absensi per event
Route::get('/attendance/{event}', [EventController::class, 'attendance'])
    ->name('attendance.show');

Route::get('/jadwal-sholat', function () {
    return view('events.jadwal-solat');
})->name('events.jadwal-solat');

Route::get('/pengajuan-event', [EventController::class, 'pengajuan'])
    ->name('events.pengajuan-event');
