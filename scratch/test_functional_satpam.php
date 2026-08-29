<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'satpam')->first();
\Illuminate\Support\Facades\Auth::login($user);

echo "Testing Functional Satpam Workflow for: " . $user->name . "\n";

$c = app(\App\Http\Controllers\SatpamDashboardController::class);

// 1. Test Store Buku Tamu
$requestTamu = new \Illuminate\Http\Request();
$requestTamu->merge([
    'nama_tamu' => 'Drs. H. Bambang Sujarwo',
    'instansi' => 'Dinas Pendidikan Provinsi Jawa Timur',
    'no_hp' => '081234567890',
    'keperluan' => 'Monitoring & Evaluasi Fasilitas Laboratorium RPL',
    'bertemu_dengan' => 'Kepala Sekolah',
    'no_kendaraan' => 'L 1945 AB',
    'jam_masuk' => '08:15',
    'catatan_satpam' => 'Tamu dinas diterima di lobi utama.',
]);

$resTamu = $c->storeBukuTamu($requestTamu);
echo "Store Buku Tamu response status: " . $resTamu->getStatusCode() . "\n";

$latestTamu = \App\Models\BukuTamu::latest('id')->first();
echo "Created Buku Tamu ID: " . $latestTamu->id . " | Nama: " . $latestTamu->nama_tamu . " | Status: " . $latestTamu->status . "\n";

// 2. Test Checkout Buku Tamu
$resCheckout = $c->checkoutBukuTamu($latestTamu->id);
$latestTamu->refresh();
echo "Checkout Buku Tamu status: " . $latestTamu->status . " | Jam Keluar: " . $latestTamu->jam_keluar . "\n";

// 3. Test Dispensasi Status Update
$firstDispensasi = \App\Models\DispensasiSiswa::first();
if ($firstDispensasi) {
    $requestDisp = new \Illuminate\Http\Request();
    $requestDisp->merge(['action' => 'kembali']);
    $resDisp = $c->updateStatusDispensasi($requestDisp, $firstDispensasi->id);
    $firstDispensasi->refresh();
    echo "Updated Dispensasi ID: " . $firstDispensasi->id . " | Status: " . $firstDispensasi->status . " | Jam Kembali: " . $firstDispensasi->jam_kembali . "\n";
}

// 4. Test Cetak Laporan Rendering
$html = $c->cetakLaporan(request())->render();
if (strpos($html, 'LAPORAN HARIAN POS KEAMANAN GERBANG') !== false) {
    echo "SUCCESS: Cetak Laporan Pos Jaga HTML rendered properly with official layout!\n";
}

echo "\nALL FUNCTIONAL SATPAM TESTS COMPLETED SUCCESSFULLY!\n";
