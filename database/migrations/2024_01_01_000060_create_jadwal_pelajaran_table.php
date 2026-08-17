<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->unsignedTinyInteger('jam_ke');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_kelas');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->cascadeOnDelete();
            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->cascadeOnDelete();

            // Prevent duplicate schedules
            $table->unique(['id_tahun_ajaran', 'hari', 'jam_ke', 'id_kelas'], 'jadwal_unique_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
};
