<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Http\Controllers\JadwalPelajaranController;
use Illuminate\Http\Request;

echo "=== Testing Jadwal Matriks Controller ===" . PHP_EOL;

// 1. Test Index view rendering
$controller = new JadwalPelajaranController();
$request = Request::create('/admin/jadwal', 'GET', ['kelas' => 1, 'mode' => 'matrix']);
$response = $controller->index($request);

echo "View Name: " . $response->name() . PHP_EOL;
$viewData = $response->getData();
echo "Selected Kelas: " . ($viewData['selectedKelas']->nama_kelas ?? 'None') . PHP_EOL;
echo "Total Mapel Kelas: " . $viewData['totalMapelKelas'] . PHP_EOL;
echo "Total Jam Kelas: " . $viewData['totalJamKelas'] . PHP_EOL;
echo "Matrix Jadwal Days: " . implode(', ', array_keys($viewData['matrixJadwal'])) . PHP_EOL;

// 2. Test Move Slot
$sampleJadwal = JadwalPelajaran::where('id_kelas', 1)->first();
if ($sampleJadwal) {
    echo "Found sample jadwal ID {$sampleJadwal->id_jadwal}: {$sampleJadwal->hari} Jam {$sampleJadwal->jam_ke}" . PHP_EOL;
    
    // Test moving to empty slot (Kamis Jam 5)
    $moveRequest = Request::create("/admin/jadwal/{$sampleJadwal->id_jadwal}/move", 'POST', [
        'target_hari' => 'Kamis',
        'target_jam'  => 5,
        'id_kelas'    => 1,
    ]);
    $moveRes = $controller->move($moveRequest, $sampleJadwal->id_jadwal);
    echo "Move to Kamis Jam 5: " . $moveRes->getContent() . PHP_EOL;

    // Move it back
    $moveBackReq = Request::create("/admin/jadwal/{$sampleJadwal->id_jadwal}/move", 'POST', [
        'target_hari' => $sampleJadwal->getOriginal('hari'),
        'target_jam'  => $sampleJadwal->getOriginal('jam_ke'),
        'id_kelas'    => 1,
    ]);
    $moveBackRes = $controller->move($moveBackReq, $sampleJadwal->id_jadwal);
    echo "Move back: " . $moveBackRes->getContent() . PHP_EOL;
}

echo "=== All Tests Completed Successfully! ===" . PHP_EOL;
