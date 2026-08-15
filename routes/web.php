<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Stub routes for sidebar links
    Route::get('/absensi', [AdminDashboardController::class, 'index'])->name('admin.absensi');
    Route::get('/siswa', [AdminDashboardController::class, 'index'])->name('admin.siswa');
    Route::get('/guru', [AdminDashboardController::class, 'index'])->name('admin.guru');
    Route::get('/master', [AdminDashboardController::class, 'index'])->name('admin.master');
    Route::get('/laporan', [AdminDashboardController::class, 'index'])->name('admin.laporan');
    Route::get('/help', [AdminDashboardController::class, 'index'])->name('admin.help');
});

Route::resource('jurnal', JurnalMengajarController::class);