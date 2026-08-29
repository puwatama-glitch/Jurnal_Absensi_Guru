<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->string('nama_tamu');
            $table->string('instansi')->nullable();
            $table->string('no_hp', 25)->nullable();
            $table->text('keperluan');
            $table->string('bertemu_dengan')->nullable();
            $table->string('no_kendaraan', 20)->nullable();
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['Di Dalam', 'Selesai'])->default('Di Dalam');
            $table->text('catatan_satpam')->nullable();
            $table->foreignId('satpam_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};
