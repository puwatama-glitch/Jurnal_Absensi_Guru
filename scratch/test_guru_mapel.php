<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'guru_mapel')->first() ?? \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

echo "Logged in as: " . $user->name . " (" . $user->role . ")\n";

$c = app(\App\Http\Controllers\GuruMapelDashboardController::class);
$req = request();

echo "1. Dashboard: " . strlen($c->index($req)->render()) . " bytes\n";
echo "2. Jadwal: " . strlen($c->jadwal($req)->render()) . " bytes\n";
echo "3. Create Jurnal: " . strlen($c->createJurnal($req)->render()) . " bytes\n";
echo "4. Riwayat Jurnal: " . strlen($c->riwayatJurnal($req)->render()) . " bytes\n";

$firstJurnal = \App\Models\JurnalMengajar::first();
if ($firstJurnal) {
    echo "5. Show Jurnal: " . strlen($c->showJurnal($firstJurnal->id_jurnal)->render()) . " bytes\n";
} else {
    echo "5. Show Jurnal: [Skipped, no records yet]\n";
}

echo "6. Rekap Presensi: " . strlen($c->rekapPresensi($req)->render()) . " bytes\n";
echo "7. Izin: " . strlen($c->izin($req)->render()) . " bytes\n";
echo "8. Help: " . strlen($c->help()->render()) . " bytes\n";
echo "\nALL GURU MAPEL VIEWS COMPILED AND RENDERED PERFECTLY!\n";
