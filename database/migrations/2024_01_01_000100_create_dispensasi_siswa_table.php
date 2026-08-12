<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->date('tanggal');
            $table->time('jam_keluar');
            $table->time('jam_kembali')->nullable();
            $table->text('alasan');
            $table->string('bukti_file')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui_KS', 'Disetujui_Waka', 'Ditolak'])->default('Menunggu');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('tanggal_persetujuan')->nullable();
            $table->foreignId('diinput_oleh')->constrained('users');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispensasi_siswa');
    }
};
