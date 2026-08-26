<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('riwayat_keluar_masuk');
        Schema::dropIfExists('pengaturan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('riwayat_keluar_masuk')) {
            Schema::create('riwayat_keluar_masuk', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('id_siswa')->nullable()->constrained('siswa', 'id_siswa')->nullOnDelete();
                $table->enum('tipe', ['masuk', 'keluar']);
                $table->dateTime('waktu');
                $table->string('keperluan')->nullable();
                $table->foreignId('satpam_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pengaturan')) {
            Schema::create('pengaturan', function (Blueprint $table) {
                $table->id();
                $table->string('kunci', 50)->unique();
                $table->text('nilai')->nullable();
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }
};
