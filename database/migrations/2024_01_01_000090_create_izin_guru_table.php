<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_guru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_guru');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('jenis_izin', ['Sakit', 'Izin', 'Cuti', 'Dinas_Luar', 'Lainnya']);
            $table->text('alasan');
            $table->string('bukti_file')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('tanggal_persetujuan')->nullable();
            $table->text('catatan_persetujuan')->nullable();
            $table->foreignId('diinput_oleh')->constrained('users');
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_guru');
    }
};
