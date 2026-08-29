<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'guru_piket')->first() ?? \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$c = app(\App\Http\Controllers\GuruPiketDashboardController::class);
$req = request();

echo "1. Dashboard: " . strlen($c->index($req)->render()) . " bytes\n";
echo "2. Monitoring: " . strlen($c->monitoringKelas($req)->render()) . " bytes\n";
echo "3. Dispensasi: " . strlen($c->dispensasi($req)->render()) . " bytes\n";
echo "4. Izin Guru: " . strlen($c->izinGuru($req)->render()) . " bytes\n";
echo "5. Rekap Presensi: " . strlen($c->rekapPresensi($req)->render()) . " bytes\n";
echo "6. Laporan: " . strlen($c->laporan($req)->render()) . " bytes\n";
echo "7. Cetak Laporan: " . strlen($c->cetakLaporan($req)->render()) . " bytes\n";
echo "8. Help: " . strlen($c->help()->render()) . " bytes\n";
echo "ALL VIEWS COMPILED AND RENDERED PERFECTLY!\n";
