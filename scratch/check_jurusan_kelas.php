<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== TABEL DATABASE ===\n";
echo "jurusan exists: " . (Schema::hasTable('jurusan') ? 'YES' : 'NO') . "\n";
echo "kelas exists: " . (Schema::hasTable('kelas') ? 'YES' : 'NO') . "\n";
echo "kelas.id_jurusan column: " . (Schema::hasColumn('kelas', 'id_jurusan') ? 'YES' : 'NO') . "\n\n";

echo "=== JUMLAH DATA ===\n";
echo "Total jurusan: " . Jurusan::count() . "\n";
echo "Total kelas: " . Kelas::count() . "\n";
echo "Kelas terhubung id_jurusan: " . Kelas::whereNotNull('id_jurusan')->count() . "\n";
echo "Kelas tanpa id_jurusan: " . Kelas::whereNull('id_jurusan')->count() . "\n\n";

echo "=== JURUSAN & JUMLAH KELAS ===\n";
foreach (Jurusan::withCount('kelas')->orderBy('kode_jurusan')->get() as $j) {
    echo "  {$j->kode_jurusan} ({$j->nama_jurusan}): {$j->kelas_count} kelas\n";
}

echo "\n=== SAMPLE KELAS ===\n";
$sample = Kelas::with('jurusanRelation', 'waliKelas')->limit(3)->get();
foreach ($sample as $k) {
    $jur = $k->jurusanRelation ? $k->jurusanRelation->kode_jurusan : 'NULL';
    $wali = $k->waliKelas ? $k->waliKelas->nama_lengkap : 'NULL';
    echo "  {$k->nama_kelas} | id_jurusan={$k->id_jurusan} | jurusan={$jur} | wali={$wali}\n";
}

echo "\n=== FOREIGN KEY ===\n";
$fks = DB::select("
    SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'kelas'
      AND REFERENCED_TABLE_NAME IS NOT NULL
");
foreach ($fks as $fk) {
    echo "  {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
}
