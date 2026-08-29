<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispensasi_siswa', function (Blueprint $table) {
            $table->string('status', 50)->default('Menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('dispensasi_siswa', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'Disetujui_KS', 'Disetujui_Waka', 'Ditolak'])->default('Menunggu')->change();
        });
    }
};
