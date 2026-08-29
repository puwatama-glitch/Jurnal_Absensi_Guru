<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'satpam')->first() ?? \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

echo "Logged in as: " . $user->name . " (" . $user->role . ")\n";

$c = app(\App\Http\Controllers\SatpamDashboardController::class);
$req = request();

echo "1. Dashboard: " . strlen($c->index($req)->render()) . " bytes\n";
echo "2. Monitoring: " . strlen($c->monitoring($req)->render()) . " bytes\n";
echo "3. Dispensasi: " . strlen($c->dispensasi($req)->render()) . " bytes\n";
echo "4. Buku Tamu: " . strlen($c->bukuTamu($req)->render()) . " bytes\n";
echo "5. Riwayat: " . strlen($c->riwayat($req)->render()) . " bytes\n";
echo "6. Cetak Laporan: " . strlen($c->cetakLaporan($req)->render()) . " bytes\n";
echo "7. Help: " . strlen($c->help()->render()) . " bytes\n";

echo "\nALL SATPAM VIEWS COMPILED AND RENDERED PERFECTLY!\n";
