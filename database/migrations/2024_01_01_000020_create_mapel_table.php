<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapel', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('kode_mapel', 20)->unique();
            $table->string('nama_mapel');
            $table->enum('kelompok', ['Normatif', 'Adaptif', 'Produktif', 'Muatan_Lokal'])->default('Normatif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};
