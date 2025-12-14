<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/jadwal-sholat', [EventController::class, 'jadwalSolat'])->name('jadwal-sholat');

// Panitia Routes - MUST BE BEFORE wildcard {event} route
Route::middleware(['auth', 'role:panitia'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/create-routine', [EventController::class, 'createRoutine'])->name('events.create-routine');
    Route::post('/events/routine', [EventController::class, 'storeRoutine'])->name('events.store-routine');
    Route::get('/events/mine', [EventController::class, 'mine'])->name('events.mine');
    Route::post('/events/{event_id}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::get('/events/{event_id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event_id}', [EventController::class, 'update'])->name('events.update');
    // Participants management
    Route::get('/events/manage-participants', [EventController::class, 'manageParticipants'])->name('events.manage-participants');
    Route::get('/events/{event_id}/participants', [EventController::class, 'eventParticipants'])->name('events.event-participants');
    Route::post('/events/{event_id}/register-participant', [EventController::class, 'registerParticipant'])->name('events.register-participant');
    Route::delete('/events/{event_id}/participant/{peserta_event_id}', [EventController::class, 'removeParticipant'])->name('events.remove-participant');
    // Attendance management
    Route::get('/events/{event_id}/attendance', [EventController::class, 'attendanceView'])->name('events.attendance');
    Route::post('/events/{event_id}/attendance/mark', [EventController::class, 'markAttendance'])->name('events.mark-attendance');
});

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Authentication Required Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role-based Dashboards
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::get('/dkm/dashboard', function () {
        return view('dashboard.dkm');
    })->middleware('role:dkm')->name('dkm.dashboard');

    Route::get('/panitia/dashboard', function () {
        $user = \Auth::user();
        $events = \App\Models\Event::where('created_by', $user->id)->get();
        return view('dashboard.panitia', compact('events', 'user'));
    })->middleware('role:panitia')->name('panitia.dashboard');

    Route::get('/jemaah/dashboard', function () {
        return view('dashboard.jemaah');
    })->middleware('role:jemaah')->name('jemaah.dashboard');

    // Legacy dashboard route - redirect based on user role
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        
        // Redirect to appropriate dashboard based on role
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dkm' => redirect()->route('dkm.dashboard'),
            'panitia' => redirect()->route('panitia.dashboard'),
            'jemaah' => redirect()->route('jemaah.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');
});


// Admin & Pengurus Routes - Can manage attendance
Route::middleware(['auth', 'role:admin,pengurus'])->group(function () {
    Route::get('/attendance', [EventController::class, 'attendanceList'])->name('attendance.list');
    Route::get('/attendance/{event}', [EventController::class, 'attendance'])->name('attendance.show');
});

// DKM Routes - Approval system (approve events created by Panitia)
Route::middleware(['auth', 'role:dkm,admin'])->group(function () {
    Route::get('/dkm/approvals', [ApprovalController::class, 'index'])->name('dkm.approvals');
    Route::post('/dkm/approve/{event}', [ApprovalController::class, 'approve'])->name('dkm.approve');
    Route::post('/dkm/reject/{event}', [ApprovalController::class, 'reject'])->name('dkm.reject');
});

// Jemaah Routes - Event Registration
Route::middleware(['auth', 'role:jemaah'])->group(function () {
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/unregister', [RegistrationController::class, 'unregister'])->name('events.unregister');
});

// Admin Only Routes - User Management & Events
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

// Admin only - Event Management & Deletion
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/events/{event_id}', [EventController::class, 'destroy'])->name('admin.events.destroy');
});

// Admin only - View all events in table
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('events', [EventController::class, 'adminIndex'])->name('events.index');
});

require __DIR__.'/auth.php';
