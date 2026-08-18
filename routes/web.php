<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminAbsensiController;
use App\Http\Controllers\MasterSiswaController;
use App\Http\Controllers\MasterGuruController;
use App\Http\Controllers\MasterJurusanController;
use App\Http\Controllers\MasterKelasController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (auth protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Absensi & Jurnal Mengajar
    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('admin.absensi');
    Route::get('/absensi/export', [AdminAbsensiController::class, 'export'])->name('admin.absensi.export');
    Route::get('/absensi/{id}', [AdminAbsensiController::class, 'show'])->name('admin.absensi.show');

    // Master Data sub-menu
    Route::prefix('master')->name('admin.master.')->group(function () {
        // Siswa CRUD
        Route::get('/siswa', [MasterSiswaController::class, 'index'])->name('siswa');
        Route::post('/siswa', [MasterSiswaController::class, 'store'])->name('siswa.store');
        Route::put('/siswa/{id}', [MasterSiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{id}', [MasterSiswaController::class, 'destroy'])->name('siswa.destroy');

        // Guru CRUD
        Route::get('/guru', [MasterGuruController::class, 'index'])->name('guru');
        Route::post('/guru', [MasterGuruController::class, 'store'])->name('guru.store');
        Route::put('/guru/{id}', [MasterGuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{id}', [MasterGuruController::class, 'destroy'])->name('guru.destroy');

        // Jurusan CRUD
        Route::get('/jurusan', [MasterJurusanController::class, 'index'])->name('jurusan');
        Route::post('/jurusan', [MasterJurusanController::class, 'store'])->name('jurusan.store');
        Route::put('/jurusan/{id}', [MasterJurusanController::class, 'update'])->name('jurusan.update');
        Route::delete('/jurusan/{id}', [MasterJurusanController::class, 'destroy'])->name('jurusan.destroy');

        // Kelas CRUD
        Route::get('/kelas', [MasterKelasController::class, 'index'])->name('kelas');
        Route::post('/kelas', [MasterKelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{id}', [MasterKelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [MasterKelasController::class, 'destroy'])->name('kelas.destroy');
    });

    // Laporan (Pusat Rekap)
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/export', [AdminLaporanController::class, 'exportCsv'])->name('admin.laporan.export');
    Route::get('/laporan/print', [AdminLaporanController::class, 'printView'])->name('admin.laporan.print');

    Route::get('/help', [AdminDashboardController::class, 'help'])->name('admin.help');

    // Profil Pengguna
    Route::get('/profil', [ProfileController::class, 'index'])->name('admin.profil');
    Route::put('/profil/info', [ProfileController::class, 'updateInfo'])->name('admin.profil.info');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('admin.profil.password');
    Route::post('/profil/photo', [ProfileController::class, 'updatePhoto'])->name('admin.profil.photo');
});

Route::resource('jurnal', JurnalMengajarController::class);