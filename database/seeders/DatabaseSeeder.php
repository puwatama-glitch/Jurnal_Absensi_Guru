<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\WaliKelas;
use App\Models\GuruPiket;
use App\Models\GuruMapel;
use App\Models\Satpam;
use App\Models\KepalaSekolah;
use App\Models\Waka;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tahun Ajaran Aktif
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'semester' => 'Ganjil',
            'tanggal_mulai' => '2024-07-15',
            'tanggal_selesai' => '2024-12-20',
            'is_aktif' => true,
        ]);

        // 2. Sample Mapel
        $mapelRpl = Mapel::create(['kode_mapel' => 'RPL01', 'nama_mapel' => 'Pemrograman Web & Perangkat Bergerak', 'kelompok' => 'Produktif']);
        $mapelTkj = Mapel::create(['kode_mapel' => 'TKJ01', 'nama_mapel' => 'Administrasi Infrastruktur Jaringan', 'kelompok' => 'Produktif']);
        $mapelMtk = Mapel::create(['kode_mapel' => 'MTK01', 'nama_mapel' => 'Matematika Terapan', 'kelompok' => 'Adaptif']);

        // 3. User & Role Records across separate tables
        // Admin
        $userAdmin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        Admin::create([
            'user_id' => $userAdmin->id,
            'nip' => '198001012005011001',
            'nama_lengkap' => 'Administrator Utama',
            'no_hp' => '081234567890',
        ]);

        // Wali Kelas
        $userWali = User::create([
            'name' => 'Drs. Ahmad Fauzi, M.Pd (Wali Kelas)',
            'email' => 'walikelas@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
            'is_active' => true,
        ]);
        $waliKelasRecord = WaliKelas::create([
            'user_id' => $userWali->id,
            'nip' => '197502122003121002',
            'nama_lengkap' => 'Drs. Ahmad Fauzi, M.Pd',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567891',
        ]);

        // Guru Piket
        $userPiket = User::create([
            'name' => 'Bambang Setyono, S.Pd (Guru Piket)',
            'email' => 'gurupiket@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_piket',
            'is_active' => true,
        ]);
        GuruPiket::create([
            'user_id' => $userPiket->id,
            'nip' => '198203152008011003',
            'nama_lengkap' => 'Bambang Setyono, S.Pd',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567892',
            'hari_piket' => 'Senin, Rabu',
        ]);

        // Guru Mapel
        $userMapel = User::create([
            'name' => 'Siti Rahayu, S.Kom (Guru Mapel)',
            'email' => 'gurumapel@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_mapel',
            'is_active' => true,
        ]);
        GuruMapel::create([
            'user_id' => $userMapel->id,
            'nip' => '198805202012022004',
            'nama_lengkap' => 'Siti Rahayu, S.Kom',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567893',
        ]);

        // Kepala Sekolah
        $userKepsek = User::create([
            'name' => 'Dr. H. Supriyanto, M.Pd (Kepala Sekolah)',
            'email' => 'kepsek@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'is_active' => true,
        ]);
        KepalaSekolah::create([
            'user_id' => $userKepsek->id,
            'nip' => '196808101994031005',
            'nama_lengkap' => 'Dr. H. Supriyanto, M.Pd',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567894',
            'periode_jabatan' => '2022-2026',
        ]);

        // Waka
        $userWaka = User::create([
            'name' => 'Budi Santoso, M.T (Waka Kurikulum)',
            'email' => 'waka@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'waka',
            'is_active' => true,
        ]);
        Waka::create([
            'user_id' => $userWaka->id,
            'nip' => '197911042006041006',
            'nama_lengkap' => 'Budi Santoso, M.T',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567895',
            'bidang' => 'Kurikulum',
        ]);

        // Satpam
        $userSatpam = User::create([
            'name' => 'Agus Setiawan (Satpam Gate)',
            'email' => 'satpam@smkn1boyolangu.sch.id',
            'password' => Hash::make('password'),
            'role' => 'satpam',
            'is_active' => true,
        ]);
        Satpam::create([
            'user_id' => $userSatpam->id,
            'nip' => '9900112233',
            'nama_lengkap' => 'Agus Setiawan',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567896',
            'pos_jaga' => 'Gerbang Utama',
        ]);

        // 4. Sample Kelas & Guru Legacy entries
        Guru::create([
            'user_id' => $userWali->id,
            'nip' => '197502122003121002',
            'nama_lengkap' => 'Drs. Ahmad Fauzi, M.Pd',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567891',
        ]);

        Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'tingkat' => 'XII',
            'jurusan' => 'RPL',
            'wali_kelas_id' => $waliKelasRecord->id,
            'jumlah_siswa' => 36,
        ]);

        Kelas::create([
            'nama_kelas' => 'XI TKJ 2',
            'tingkat' => 'XI',
            'jurusan' => 'TKJ',
            'jumlah_siswa' => 34,
        ]);
    }
}
