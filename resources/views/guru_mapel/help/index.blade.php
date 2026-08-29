@extends('layouts.guru_mapel')

@section('title', 'Pusat Panduan Guru - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Pusat Panduan & SOP Guru Mapel')
@section('header_subtitle', 'Panduan tata cara operasional pengisian jurnal mengajar, presensi siswa, dan jadwal KBM')

@section('styles')
<style>
    .help-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .topic-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .topic-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .topic-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .topic-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .topic-icon.blue   { background: #eef2ff; color: #2b43b9; }
    .topic-icon.green  { background: #e6f9f0; color: #10b981; }
    .topic-icon.orange { background: #fff7ed; color: #f97316; }
    .topic-icon.red    { background: #fef2f2; color: #ef4444; }

    .topic-title {
        font-size: 15px;
        font-weight: 800;
        color: #1b2559;
    }

    .topic-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
    }

    .faq-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    }

    .faq-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 0;
    }
    .faq-item:last-child { border-bottom: none; }

    .faq-header {
        font-size: 14px;
        font-weight: 700;
        color: #1b2559;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-body {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        margin-top: 8px;
    }
</style>
@endsection

@section('content')
<!-- SOP Cards -->
<div class="help-grid">
    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon blue"><i class="fa-solid fa-pen-to-square"></i></div>
            <div>
                <div class="topic-title">Pengisian Jurnal KBM</div>
            </div>
        </div>
        <div class="topic-desc">
            Setiap selesai jam mengajar, buka Dashboard atau menu Isi Jurnal, pilih sesi jadwal aktif, lalu tuliskan ringkasan materi dan CP/TP yang diajarkan.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon green"><i class="fa-solid fa-users-viewfinder"></i></div>
            <div>
                <div class="topic-title">Presensi Siswa</div>
            </div>
        </div>
        <div class="topic-desc">
            Daftar siswa akan muncul otomatis per rombel. Gunakan tombol 'Semua Hadir' untuk presensi cepat, lalu ubah status siswa yang Sakit, Izin, atau Alpha.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon orange"><i class="fa-solid fa-calendar-week"></i></div>
            <div>
                <div class="topic-title">Jadwal & Ruang Kelas</div>
            </div>
        </div>
        <div class="topic-desc">
            Cek jadwal mengajar mingguan di menu Jadwal Mengajar untuk memastikan jam dan rombel yang harus Anda masuki setiap harinya.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon red"><i class="fa-solid fa-user-clock"></i></div>
            <div>
                <div class="topic-title">Izin & Penugasan Mandiri</div>
            </div>
        </div>
        <div class="topic-desc">
            Jika berhalangan hadir, ajukan izin melalui menu Pengajuan Izin dan sertakan instruksi tugas mandiri agar Guru Piket dapat mengondisikan kelas.
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-card">
    <h3 style="font-size: 16px; font-weight: 800; color: #1b2559; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-question" style="color: #2b43b9;"></i>
        Pertanyaan Umum Seputar Guru Mapel (FAQ)
    </h3>

    <div class="faq-item">
        <div class="faq-header">
            <span>Bagaimana jika saya mengajar di luar jadwal rutin (jam tambahan / inval)?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Anda dapat langsung masuk ke menu <strong>Isi Jurnal & Presensi</strong>, lalu pilih kelas dan mata pelajaran yang diajar secara manual tanpa harus memilih dari jadwal rutin.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-header">
            <span>Apakah saya bisa melihat kembali materi yang diajarkan pada pertemuan sebelumnya?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Bisa. Seluruh arsip pembelajaran tersimpan di menu <strong>Riwayat Jurnal</strong>. Anda dapat memfilter berdasarkan kelas atau mata pelajaran untuk melihat materi dan absensi pada tanggal tersebut.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-header">
            <span>Kapan data presensi siswa masuk ke laporan sekolah?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Saat Anda menekan tombol <strong>Simpan Jurnal & Presensi Siswa</strong>, data kehadiran siswa otomatis tersinkronisasi secara real-time ke Dashboard Wali Kelas, Guru Piket, dan Admin.
        </div>
    </div>
</div>
@endsection
