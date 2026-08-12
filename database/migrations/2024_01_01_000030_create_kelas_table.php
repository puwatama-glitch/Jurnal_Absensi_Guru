<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 30);
            $table->enum('tingkat', ['X', 'XI', 'XII']);
            $table->string('jurusan', 50);
            $table->foreignId('wali_kelas_id')->nullable()->constrained('wali_kelas')->nullOnDelete();
            $table->integer('jumlah_siswa')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
