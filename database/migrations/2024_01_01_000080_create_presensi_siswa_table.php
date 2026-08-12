<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jurnal');
            $table->unsignedBigInteger('id_siswa');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha', 'Dispensasi'])->default('Hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_jurnal')->references('id_jurnal')->on('jurnal_mengajar')->cascadeOnDelete();
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->cascadeOnDelete();

            $table->unique(['id_jurnal', 'id_siswa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_siswa');
    }
};
