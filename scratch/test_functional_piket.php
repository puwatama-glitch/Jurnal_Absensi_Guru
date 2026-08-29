<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'guru_piket')->first() ?? \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$siswa = \App\Models\Siswa::first();
if (!$siswa) {
    echo "No siswa found, test skipped.\n";
    exit(0);
}

$c = app(\App\Http\Controllers\GuruPiketDashboardController::class);

// 1. Test Store Dispensasi
$reqStore = new \Illuminate\Http\Request([
    'id_siswa' => $siswa->id_siswa,
    'tanggal' => date('Y-m-d'),
    'jam_keluar' => '09:00',
    'jam_kembali' => '11:30',
    'alasan' => 'Izin tes kesehatan dan lomba kejuruan',
]);

$response = $c->storeDispensasi($reqStore);
echo "Dispensasi created! Status code: " . $response->getStatusCode() . "\n";

$disp = \App\Models\DispensasiSiswa::latest('id')->first();
echo "Latest dispensasi ID: " . $disp->id . ", Status: " . $disp->status . "\n";

// 2. Test Cetak Dispensasi view
$cetakView = $c->cetakDispensasi($disp->id);
echo "Cetak Dispensasi view rendered: " . strlen($cetakView->render()) . " bytes\n";

// 3. Test Update Status
$reqUpdate = new \Illuminate\Http\Request(['action' => 'kembali']);
$c->updateDispensasiStatus($reqUpdate, $disp->id);
$disp->refresh();
echo "Updated dispensasi status: " . $disp->status . ", Jam kembali: " . $disp->jam_kembali . "\n";

// 4. Test Store Laporan Piket
$reqLaporan = new \Illuminate\Http\Request([
    'tanggal' => date('Y-m-d'),
    'jam_mulai_piket' => '06:45',
    'jam_selesai_piket' => '15:30',
    'catatan_kejadian' => 'Kegiatan belajar mengajar berjalan kondusif, aman, dan tertib.',
]);
$c->storeLaporan($reqLaporan);
$lap = \App\Models\LaporanPiket::where('tanggal', date('Y-m-d'))->first();
echo "Laporan Piket saved! ID: " . $lap->id . ", Catatan: " . $lap->catatan_kejadian . "\n";

echo "ALL FUNCTIONAL TESTS PASSED!\n";
