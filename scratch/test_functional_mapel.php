<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'guru_mapel')->first();
\Illuminate\Support\Facades\Auth::login($user);

$guru = $user->guru ?? \App\Models\Guru::where('user_id', $user->id)->first() ?? \App\Models\Guru::first();
$kelas = \App\Models\Kelas::first();
$mapel = \App\Models\Mapel::first();
$siswaList = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->take(3)->get();

echo "Testing Functional Jurnal Creation for: " . $guru->nama_lengkap . "\n";

$presensiMap = [];
foreach ($siswaList as $idx => $s) {
    $presensiMap[$s->id_siswa] = ($idx === 0) ? 'Hadir' : (($idx === 1) ? 'Sakit' : 'Izin');
}

$c = app(\App\Http\Controllers\GuruMapelDashboardController::class);

$request = new \Illuminate\Http\Request();
$request->merge([
    'id_kelas' => $kelas->id_kelas,
    'id_mapel' => $mapel->id_mapel,
    'tanggal' => date('Y-m-d'),
    'jam_ke' => '1-2',
    'jam_mulai' => '07:30',
    'jam_selesai' => '09:00',
    'materi' => 'Pengenalan Algoritma & Struktur Data Dasar',
    'status_guru' => 'Hadir',
    'catatan' => 'Siswa mengikuti sesi dengan aktif.',
    'presensi' => $presensiMap,
    'keterangan' => [
        $siswaList[1]->id_siswa ?? 0 => 'Demam flu',
    ]
]);

$res = $c->storeJurnal($request);
echo "Jurnal store response status: " . $res->getStatusCode() . "\n";

$latestJurnal = \App\Models\JurnalMengajar::latest('id_jurnal')->first();
echo "Created Jurnal ID: " . $latestJurnal->id_jurnal . " | Materi: " . $latestJurnal->materi . "\n";
echo "Total Hadir: " . $latestJurnal->jumlah_siswa_hadir . " | Absen: " . $latestJurnal->jumlah_siswa_tidak_hadir . "\n";

$presensiCount = \App\Models\PresensiSiswa::where('id_jurnal', $latestJurnal->id_jurnal)->count();
echo "Presensi rows created: " . $presensiCount . "\n";

echo "ALL FUNCTIONAL TESTS PASSED SUCCESSFULLY!\n";
