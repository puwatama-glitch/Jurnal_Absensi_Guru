<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$userKepsek = \App\Models\User::where('role', 'kepala_sekolah')->first();
if ($userKepsek) {
    $cleanName = preg_replace('/\s*\(Kepala Sekolah\)/i', '', $userKepsek->name);
    $cleanName = trim($cleanName);

    $kepsek = \App\Models\KepalaSekolah::where('user_id', $userKepsek->id)->first();
    if ($kepsek) {
        $kepsek->update(['nama_lengkap' => $cleanName]);
        echo "Updated existing KepalaSekolah ID {$kepsek->id} with name: {$cleanName}\n";
    } else {
        \App\Models\KepalaSekolah::create([
            'user_id' => $userKepsek->id,
            'nip' => '196808101994031005',
            'nama_lengkap' => $cleanName,
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);
        echo "Created new KepalaSekolah with name: {$cleanName}\n";
    }
}

echo "Current KepalaSekolah:\n";
print_r(\App\Models\KepalaSekolah::all()->toArray());
