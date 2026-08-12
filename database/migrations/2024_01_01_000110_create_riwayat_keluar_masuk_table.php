<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_keluar_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dispensasi')->constrained('dispensasi_siswa')->cascadeOnDelete();
            $table->unsignedBigInteger('id_siswa');
            $table->datetime('waktu_keluar');
            $table->datetime('waktu_masuk')->nullable();
            $table->foreignId('dikonfirmasi_oleh')->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_keluar_masuk');
    }
};
