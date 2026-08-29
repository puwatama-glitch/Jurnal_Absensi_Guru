<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_piket', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('guru_piket_id')->nullable();
            $table->time('jam_mulai_piket')->nullable();
            $table->time('jam_selesai_piket')->nullable();
            $table->text('catatan_kejadian')->nullable();
            $table->integer('jumlah_guru_hadir')->default(0);
            $table->integer('jumlah_guru_izin')->default(0);
            $table->integer('jumlah_siswa_dispensasi')->default(0);
            $table->integer('jumlah_siswa_alpha')->default(0);
            $table->string('status_piket', 30)->default('Aktif');
            $table->timestamps();

            $table->foreign('guru_piket_id')->references('id')->on('guru_piket')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_piket');
    }
};
