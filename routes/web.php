<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [EventController::class, 'index'])->name('home');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/jadwal-sholat', [EventController::class, 'jadwalSolat'])->name('jadwal-sholat');

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

    Route::get('/pengurus/dashboard', function () {
        return view('dashboard.pengurus');
    })->middleware('role:pengurus')->name('pengurus.dashboard');

    Route::get('/panitia/dashboard', function () {
        return view('dashboard.panitia');
    })->middleware('role:panitia')->name('panitia.dashboard');

    Route::get('/jemaah/dashboard', function () {
        return view('dashboard.jemaah');
    })->middleware('role:jemaah')->name('jemaah.dashboard');

    // Legacy dashboard route
    Route::get('/dashboard', function () {
        return redirect()->route(Auth::user()->role . '.dashboard');
    })->name('dashboard');
});

// Panitia Routes - Can create and submit events
Route::middleware(['auth', 'role:panitia'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/create-routine', [EventController::class, 'createRoutine'])->name('events.create-routine');
    Route::post('/events/routine', [EventController::class, 'storeRoutine'])->name('events.store-routine');
});

// Admin & Pengurus Routes - Can manage attendance
Route::middleware(['auth', 'role:admin,pengurus'])->group(function () {
    Route::get('/attendance', [EventController::class, 'attendanceList'])->name('attendance.list');
    Route::get('/attendance/{event}', [EventController::class, 'attendance'])->name('attendance.show');
});

// Admin & Pengurus Routes - Approval system
Route::middleware(['auth', 'role:pengurus,admin'])->group(function () {
    Route::get('/pengurus/approvals', [ApprovalController::class, 'index'])->name('pengurus.approvals');
    Route::post('/pengurus/approve/{event}', [ApprovalController::class, 'approve'])->name('pengurus.approve');
    Route::post('/pengurus/reject/{event}', [ApprovalController::class, 'reject'])->name('pengurus.reject');
});

// Jemaah Routes - Event Registration
Route::middleware(['auth', 'role:jemaah'])->group(function () {
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/unregister', [RegistrationController::class, 'unregister'])->name('events.unregister');
});

// Admin Only Routes - User Management
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

require __DIR__.'/auth.php';
