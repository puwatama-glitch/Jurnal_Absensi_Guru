<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Waka;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

// 1. Waka Kurikulum
$userWakaKurikulum = User::updateOrCreate(
    ['email' => 'wakakurikulum@smkn1boyolangu.sch.id'],
    [
        'name' => 'Budi Santoso, M.T (Waka Kurikulum)',
        'password' => Hash::make('password'),
        'role' => 'waka_kurikulum',
        'is_active' => true,
    ]
);
Waka::updateOrCreate(
    ['nip' => '197911042006041006'],
    [
        'user_id' => $userWakaKurikulum->id,
        'nama_lengkap' => 'Budi Santoso, M.T',
        'jenis_kelamin' => 'L',
        'no_hp' => '081234567895',
        'bidang' => 'Kurikulum',
        'status_aktif' => true,
    ]
);

// 2. Waka SDM
$userWakaSdm = User::updateOrCreate(
    ['email' => 'wakasdm@smkn1boyolangu.sch.id'],
    [
        'name' => 'Dr. Hendra Wijaya, M.Pd (Waka SDM)',
        'password' => Hash::make('password'),
        'role' => 'waka_sdm',
        'is_active' => true,
    ]
);
Waka::updateOrCreate(
    ['nip' => '198103152008011009'],
    [
        'user_id' => $userWakaSdm->id,
        'nama_lengkap' => 'Dr. Hendra Wijaya, M.Pd',
        'jenis_kelamin' => 'L',
        'no_hp' => '081234567897',
        'bidang' => 'SDM',
        'status_aktif' => true,
    ]
);

// 3. Wali Murid / Siswa
$firstSiswa = Siswa::first();
$userWaliMurid = User::updateOrCreate(
    ['email' => 'walimurid@smkn1boyolangu.sch.id'],
    [
        'name' => 'Wali dari ' . ($firstSiswa ? $firstSiswa->nama_lengkap : 'Siswa 1'),
        'password' => Hash::make('password'),
        'role' => 'wali_murid',
        'is_active' => true,
    ]
);

if ($firstSiswa) {
    $firstSiswa->update(['user_id' => $userWaliMurid->id]);
}

echo "Created / Synced new role users:\n";
echo "- Waka Kurikulum: wakakurikulum@smkn1boyolangu.sch.id (PW: password)\n";
echo "- Waka SDM: wakasdm@smkn1boyolangu.sch.id (PW: password)\n";
echo "- Wali Murid: walimurid@smkn1boyolangu.sch.id (PW: password, linked to Siswa: " . ($firstSiswa ? $firstSiswa->nama_lengkap : 'None') . ")\n";
