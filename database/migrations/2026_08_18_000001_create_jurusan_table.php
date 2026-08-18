<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id('id_jurusan');
            $table->string('kode_jurusan', 20)->unique();
            $table->string('nama_jurusan', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('id_jurusan')->nullable()->after('tingkat')->constrained('jurusan', 'id_jurusan')->nullOnDelete();
        });

        $defaults = [
            ['kode_jurusan' => 'RPL',  'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['kode_jurusan' => 'TKJ',  'nama_jurusan' => 'Teknik Komputer dan Jaringan'],
            ['kode_jurusan' => 'AKL',  'nama_jurusan' => 'Akuntansi dan Keuangan Lembaga'],
            ['kode_jurusan' => 'OTKP', 'nama_jurusan' => 'Otomatisasi dan Tata Kelola Perkantoran'],
            ['kode_jurusan' => 'TBM',  'nama_jurusan' => 'Teknik Bisnis Sepeda Motor'],
            ['kode_jurusan' => 'DMM',  'nama_jurusan' => 'Desain Multimedia'],
        ];

        $now = now();
        foreach ($defaults as $j) {
            DB::table('jurusan')->insert(array_merge($j, [
                'status_aktif' => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]));
        }

        if (Schema::hasColumn('kelas', 'jurusan')) {
            $jurusanMap = DB::table('jurusan')->pluck('id_jurusan', 'kode_jurusan');
            foreach (DB::table('kelas')->whereNull('id_jurusan')->get() as $kelas) {
                $idJurusan = $jurusanMap[$kelas->jurusan] ?? null;
                if ($idJurusan) {
                    DB::table('kelas')->where('id_kelas', $kelas->id_kelas)->update(['id_jurusan' => $idJurusan]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_jurusan']);
            $table->dropColumn('id_jurusan');
        });

        Schema::dropIfExists('jurusan');
    }
};
