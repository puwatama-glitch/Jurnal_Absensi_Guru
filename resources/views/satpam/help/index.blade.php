@extends('layouts.satpam')

@section('title', 'Pusat Panduan Satpam - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Pusat Panduan & SOP Pos Keamanan Gate')
@section('header_subtitle', 'Prosedur operasional standar verifikasi gerbang, buku tamu, dan kontak darurat')

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
            <div class="topic-icon orange"><i class="fa-solid fa-ticket-simple"></i></div>
            <div>
                <div class="topic-title">Pemeriksaan Dispensasi Siswa</div>
            </div>
        </div>
        <div class="topic-desc">
            Siswa yang diizinkan keluar gerbang wajib membawa slip dispensasi fisik atau terdaftar di sistem. Satpam menekan tombol <strong>'Konfirmasi Keluar'</strong> saat siswa melintasi gerbang.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon green"><i class="fa-solid fa-rotate-left"></i></div>
            <div>
                <div class="topic-title">Siswa Kembali ke Sekolah</div>
            </div>
        </div>
        <div class="topic-desc">
            Saat siswa kembali masuk pos gerbang, cari data siswa pada Dashboard atau menu Live Monitoring, lalu klik <strong>'Konfirmasi Siswa Kembali Masuk'</strong>.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon blue"><i class="fa-solid fa-address-book"></i></div>
            <div>
                <div class="topic-title">Pencatatan Buku Tamu</div>
            </div>
        </div>
        <div class="topic-desc">
            Tamu kedinasan atau wali murid dicatat melalui tombol <strong>'+ Catat Tamu Baru'</strong> dengan mengisi nama, instansi, keperluan, bertemu siapa, dan nomor polisi kendaraan.
        </div>
    </div>

    <div class="topic-card">
        <div class="topic-top">
            <div class="topic-icon red"><i class="fa-solid fa-phone-volume"></i></div>
            <div>
                <div class="topic-title">Penanganan Darurat</div>
            </div>
        </div>
        <div class="topic-desc">
            Bila ada siswa yang memaksa keluar tanpa slip atau tamu yang mencurigakan, segera hubungi Guru Piket atau Waka Kesiswaan melalui kontak darurat sekolah.
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-card">
    <h3 style="font-size: 16px; font-weight: 800; color: #1b2559; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-question" style="color: #2b43b9;"></i>
        Pertanyaan Umum Seputar Pos Keamanan Gate (FAQ)
    </h3>

    <div class="faq-item">
        <div class="faq-header">
            <span>Bagaimana jika siswa lupa membawa slip dispensasi fisik?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Satpam dapat langsung memeriksa menu <strong>Verifikasi Dispensasi</strong> lalu mengetikkan NISN atau Nama Siswa. Jika statusnya sudah disetujui Guru Piket, siswa dapat diizinkan keluar.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-header">
            <span>Bagaimana jika tamu keluar sekolah saat jam pergantian shift?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Buka menu <strong>Buku Tamu Digital</strong>, cari nama tamu yang bersangkutan, lalu klik tombol <strong>'Checkout'</strong> agar statusnya berubah menjadi selesai.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-header">
            <span>Bagaimana cara mencetak laporan harian keamanan pos jaga?</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8;"></i>
        </div>
        <div class="faq-body">
            Masuk ke menu <strong>Riwayat Log Gerbang</strong>, pilih tanggal yang diinginkan, lalu klik tombol <strong>'Cetak Laporan Pos Jaga'</strong> di pojok kanan atas.
        </div>
    </div>
</div>
@endsection
