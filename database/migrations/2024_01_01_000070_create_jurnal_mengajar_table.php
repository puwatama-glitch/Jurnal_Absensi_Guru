<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_mengajar', function (Blueprint $table) {
            $table->id('id_jurnal');
            $table->unsignedBigInteger('id_jadwal')->nullable();
            $table->unsignedBigInteger('id_guru')->index();
            $table->unsignedBigInteger('id_kelas')->index();
            $table->unsignedBigInteger('id_mapel')->index();
            $table->date('tanggal')->index();
            $table->string('jam_ke', 10);
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->text('materi');
            $table->integer('jumlah_siswa_hadir')->default(0);
            $table->integer('jumlah_siswa_tidak_hadir')->default(0);
            $table->enum('status_guru', ['Hadir', 'Izin', 'Sakit'])->default('Hadir');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_pelajaran')->nullOnDelete();
            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->cascadeOnDelete();
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajar');
    }
};
