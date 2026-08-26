<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminAbsensiController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\MasterSiswaController;
use App\Http\Controllers\MasterGuruController;
use App\Http\Controllers\MasterJurusanController;
use App\Http\Controllers\MasterKelasController;
use App\Http\Controllers\MasterMapelController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\WaliKelasDashboardController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (auth protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Notifikasi API Endpoints
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('admin.notifikasi');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('admin.notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('admin.notifikasi.read-all');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('admin.notifikasi.destroy');

    // Absensi & Jurnal Mengajar
    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('admin.absensi');
    Route::get('/absensi/export', [AdminAbsensiController::class, 'export'])->name('admin.absensi.export');
    Route::get('/absensi/{id}', [AdminAbsensiController::class, 'show'])->name('admin.absensi.show');

    // Jadwal Pelajaran
    Route::get('/jadwal', [JadwalPelajaranController::class, 'index'])->name('admin.jadwal');
    Route::post('/jadwal', [JadwalPelajaranController::class, 'store'])->name('admin.jadwal.store');
    Route::post('/jadwal/set-tahun-ajaran', [JadwalPelajaranController::class, 'setTahunAjaran'])->name('admin.jadwal.set-ta');
    Route::put('/jadwal/{id}', [JadwalPelajaranController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalPelajaranController::class, 'destroy'])->name('admin.jadwal.destroy');

    // Master Data sub-menu
    Route::prefix('master')->name('admin.master.')->group(function () {
        // User CRUD (Manajemen User di atas Siswa)
        Route::get('/user', [MasterUserController::class, 'index'])->name('user');
        Route::post('/user', [MasterUserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [MasterUserController::class, 'update'])->name('user.update');
        Route::post('/user/{id}/toggle-status', [MasterUserController::class, 'toggleStatus'])->name('user.toggle-status');
        Route::delete('/user/{id}', [MasterUserController::class, 'destroy'])->name('user.destroy');

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

        // Mapel CRUD
        Route::get('/mapel', [MasterMapelController::class, 'index'])->name('mapel');
        Route::post('/mapel', [MasterMapelController::class, 'store'])->name('mapel.store');
        Route::put('/mapel/{id}', [MasterMapelController::class, 'update'])->name('mapel.update');
        Route::delete('/mapel/{id}', [MasterMapelController::class, 'destroy'])->name('mapel.destroy');
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

// Wali Kelas Routes (auth protected)
Route::prefix('wali-kelas')->middleware('auth')->group(function () {
    Route::get('/dashboard', [WaliKelasDashboardController::class, 'index'])->name('wali-kelas.dashboard');
    Route::get('/siswa', [WaliKelasDashboardController::class, 'siswa'])->name('wali-kelas.siswa');
    Route::get('/jurnal', [WaliKelasDashboardController::class, 'jurnal'])->name('wali-kelas.jurnal');
});

Route::resource('jurnal', JurnalMengajarController::class);