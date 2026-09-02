<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update role column in users table (using DB raw statement for MySQL / MariaDB enum expansion)
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'wali_kelas', 'guru_mapel', 'guru_piket', 'kepala_sekolah', 'waka', 'waka_sdm', 'waka_kurikulum', 'wali_murid', 'satpam') NOT NULL DEFAULT 'guru_mapel'");

        // 2. Add user_id column to siswa table if not exists
        if (!Schema::hasColumn('siswa', 'user_id')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id_siswa')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('siswa', 'user_id')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'wali_kelas', 'guru_mapel', 'guru_piket', 'kepala_sekolah', 'waka', 'satpam') NOT NULL DEFAULT 'guru_mapel'");
    }
};
