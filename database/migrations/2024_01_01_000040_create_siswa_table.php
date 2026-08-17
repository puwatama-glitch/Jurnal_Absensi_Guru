<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->unsignedBigInteger('id_kelas')->index();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status_aktif')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
