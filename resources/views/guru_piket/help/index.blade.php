@extends('layouts.guru_piket')

@section('title', 'Pusat Panduan Guru Piket — SMKN 1 BOYOLANGU')
@section('header_title', 'Pusat Panduan & SOP Guru Piket')
@section('header_subtitle', 'Petunjuk operasional pemantauan KBM, perizinan siswa, dan pelaporan harian piket')

@section('styles')
<style>
    /* Search Bar */
    .help-action-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .help-search-wrap { position: relative; flex: 1; min-width: 280px; max-width: 560px; }
    .help-search-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 14px;
    }
    .help-search-input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 11px 14px 11px 40px;
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
        font-family: inherit;
    }
    .help-search-input:focus {
        border-color: #2b43b9;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(43,67,185,0.1);
    }
    .help-search-input::placeholder { color: #94a3b8; font-weight: 500; }
    .help-search-meta { font-size: 12px; font-weight: 700; color: #64748b; white-space: nowrap; }
    .help-search-meta span { color: #2b43b9; }

    /* KPI Quick Stats */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
    .kpi-card {
        background: #ffffff; border-radius: 18px; padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 16px; transition: all 0.25s ease;
    }
    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.07); border-color: #cbd5e1; }
    .kpi-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .kpi-icon.blue   { background: #eef2ff; color: #2b43b9; }
    .kpi-icon.green  { background: #e6f9f0; color: #10b981; }
    .kpi-icon.orange { background: #fff7ed; color: #f97316; }
    .kpi-icon.red    { background: #fef2f2; color: #ef4444; }
    .kpi-val { font-size: 28px; font-weight: 900; color: #1b2559; line-height: 1.1; }
    .kpi-lbl { font-size: 12px; font-weight: 700; color: #64748b; margin-top: 3px; }
    .kpi-sub { font-size: 11px; font-weight: 600; color: #94a3b8; margin-top: 2px; }

    /* Layout: topics + quick links */
    .help-layout { display: grid; grid-template-columns: 1fr 320px; gap: 24px; margin-bottom: 24px; }
    .help-main-card, .help-side-card {
        background: #ffffff; border-radius: 20px; padding: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;
    }
    .card-hdr {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;
    }
    .card-title { font-size: 17px; font-weight: 800; color: #1b2559; display: flex; align-items: center; gap: 10px; }
    .card-title i { color: #2b43b9; }

    .topics-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .topic-card {
        border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;
        transition: all 0.22s ease; cursor: pointer; background: #ffffff;
        text-decoration: none; color: inherit; display: block;
    }
    .topic-card:hover {
        transform: translateY(-2px);
        border-color: #c7d2fe;
        box-shadow: 0 8px 24px rgba(43,67,185,0.08);
    }
    .topic-top { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 12px; }
    .topic-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
    }
    .topic-icon.blue   { background: #eef2ff; color: #2b43b9; }
    .topic-icon.green  { background: #e6f9f0; color: #10b981; }
    .topic-icon.orange { background: #fff7ed; color: #f97316; }
    .topic-icon.red    { background: #fef2f2; color: #ef4444; }
    .topic-title { font-size: 14.5px; font-weight: 800; color: #1b2559; margin-bottom: 2px; }
    .topic-badge { font-size: 10.5px; font-weight: 800; color: #2b43b9; background: #e0e7ff; padding: 2px 8px; border-radius: 6px; display: inline-block; }
    .topic-desc { font-size: 12.5px; color: #64748b; line-height: 1.5; }

    /* Accordion FAQ */
    .faq-item {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .faq-header {
        padding: 16px 20px;
        background: #f8fafc;
        font-weight: 700;
        font-size: 14px;
        color: #1b2559;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .faq-header:hover { background: #f1f5f9; }
    .faq-body {
        padding: 16px 20px;
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
        display: none;
        background: white;
        border-top: 1px solid #f1f5f9;
    }
    .faq-body.show { display: block; }

    @media (max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .help-layout { grid-template-columns: 1fr; }
        .topics-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- Search Card -->
<div class="help-action-card">
    <div class="help-search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="helpSearchInput" class="help-search-input"
            placeholder="Ketik topik panduan piket (contoh: dispensasi, monitoring, kelas kosong, izin guru)..."
            onkeyup="filterHelpContent()">
    </div>
    <div class="help-search-meta">
        <i class="fa-solid fa-circle-info" style="color: #2b43b9; margin-right: 4px;"></i>
        <span>Panduan Resmi Guru Piket SMKN 1 Boyolangu</span>
    </div>
</div>

<!-- KPI Summary -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
        <div>
            <div class="kpi-val">01</div>
            <div class="kpi-lbl">Monitoring KBM</div>
            <div class="kpi-sub">Pantau pengisian jurnal kelas</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon orange"><i class="fa-solid fa-ticket-simple"></i></div>
        <div>
            <div class="kpi-val">02</div>
            <div class="kpi-lbl">Dispensasi Siswa</div>
            <div class="kpi-sub">Izin keluar gerbang sekolah</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon green"><i class="fa-solid fa-user-clock"></i></div>
        <div>
            <div class="kpi-val">03</div>
            <div class="kpi-lbl">Guru Berhalangan</div>
            <div class="kpi-sub">Tugas mandiri / pengganti</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red"><i class="fa-solid fa-file-signature"></i></div>
        <div>
            <div class="kpi-val">04</div>
            <div class="kpi-lbl">Laporan Harian</div>
            <div class="kpi-sub">Cetak berita acara resmi</div>
        </div>
    </div>
</div>

<!-- Main Layout -->
<div class="help-layout">
    <div class="help-main-card">
        <div class="card-hdr">
            <div class="card-title">
                <i class="fa-solid fa-book-open"></i>
                <span>Modul Panduan Operasional Guru Piket</span>
            </div>
        </div>

        <div class="topics-grid">
            <div class="topic-card">
                <div class="topic-top">
                    <div class="topic-icon blue"><i class="fa-solid fa-desktop"></i></div>
                    <div>
                        <div class="topic-title">Dashboard & Pemantauan Kelas</div>
                        <span class="topic-badge">Matriks KBM</span>
                    </div>
                </div>
                <div class="topic-desc">
                    Memantau secara langsung status setiap ruang kelas (🟢 Sedang Mengajar, 🟡 Belum Ada Jurnal, 🔴 Guru Izin/Kelas Kosong).
                </div>
            </div>

            <div class="topic-card">
                <div class="topic-top">
                    <div class="topic-icon orange"><i class="fa-solid fa-ticket"></i></div>
                    <div>
                        <div class="topic-title">Penerbitan Dispensasi Siswa</div>
                        <span class="topic-badge">Izin Gerbang</span>
                    </div>
                </div>
                <div class="topic-desc">
                    Tata cara mencatat izin keluar siswa, mencetak slip dispensasi resmi ber-kop sekolah untuk ditunjukkan ke Satpam pos gerbang.
                </div>
            </div>

            <div class="topic-card">
                <div class="topic-top">
                    <div class="topic-icon green"><i class="fa-solid fa-user-xmark"></i></div>
                    <div>
                        <div class="topic-title">Monitoring Izin Guru & Kelas Kosong</div>
                        <span class="topic-badge">Penanganan Inval</span>
                    </div>
                </div>
                <div class="topic-desc">
                    Melihat daftar guru yang izin/sakit hari ini beserta jadwal kelas yang ditinggalkan untuk koordinasi pemberian tugas mandiri.
                </div>
            </div>

            <div class="topic-card">
                <div class="topic-top">
                    <div class="topic-icon red"><i class="fa-solid fa-print"></i></div>
                    <div>
                        <div class="topic-title">Pengisian & Cetak Laporan Piket</div>
                        <span class="topic-badge">Laporan Harian</span>
                    </div>
                </div>
                <div class="topic-desc">
                    Merekam catatan kejadian penting selama jam piket serta mencetak Laporan Piket Harian yang ditandatangani Kepala Sekolah.
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div style="margin-top: 30px;">
            <h4 style="font-size: 15px; font-weight: 800; color: #1b2559; margin-bottom: 14px;">
                <i class="fa-solid fa-circle-question" style="color: #2b43b9; margin-right: 6px;"></i>
                Pertanyaan yang Sering Diajukan (FAQ)
            </h4>

            <div class="faq-item">
                <div class="faq-header" onclick="toggleFaq(this)">
                    <span>Bagaimana alur siswa yang ingin izin keluar sekolah (Dispensasi)?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-body">
                    1. Siswa melapor ke meja Guru Piket membawa surat pengantar/alasan izin.<br>
                    2. Guru piket membuka menu <strong>Dispensasi Siswa</strong> & klik tombol <strong>+ Tambah Dispensasi</strong>.<br>
                    3. Isi nama siswa, jam keluar, perkiraan kembali, dan keperluan, lalu klik Simpan.<br>
                    4. Klik tombol <strong>Cetak Slip</strong> dan berikan kepada siswa untuk ditunjukkan ke Satpam di gerbang utama saat keluar dan kembali.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-header" onclick="toggleFaq(this)">
                    <span>Apa yang harus dilakukan jika ada kelas yang statusnya merah (Guru Izin)?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-body">
                    Guru piket dapat mengecek menu <strong>Monitoring Izin Guru</strong> untuk melihat apakah guru yang bersangkutan menitipkan materi/tugas. Petugas piket dapat mendatangi kelas tersebut untuk memastikan siswa mengerjakan tugas dengan tertib atau menginstruksikan guru pengganti (inval).
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-header" onclick="toggleFaq(this)">
                    <span>Kapan Laporan Piket Harian dicetak?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-body">
                    Laporan piket harian dapat dicetak pada akhir jam dinas piket (misal pukul 15.00 WIB) setelah seluruh catatan kejadian, guru izin, dan rekapitulasi presensi siswa terangkum lengkap. Dokumen tersebut kemudian ditandatangani oleh Guru Piket bertugas dan Kepala Sekolah.
                </div>
            </div>
        </div>
    </div>

    <!-- Side Card: Kontak & Bantuan -->
    <div class="help-side-card">
        <div class="card-hdr">
            <div class="card-title">
                <i class="fa-solid fa-headset"></i>
                <span>Bantuan Teknis</span>
            </div>
        </div>

        <div style="font-size: 13px; color: #64748b; line-height: 1.6; margin-bottom: 16px;">
            Jika Anda mengalami kendala teknis pada sistem jurnal atau sinkronisasi data presensi, silakan hubungi tim Administrator IT SMKN 1 Boyolangu.
        </div>

        <div style="background: #f8fafc; border-radius: 12px; padding: 14px; font-size: 12.5px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
            <div><strong>Ruang:</strong> Lab Komputer 1 / Tim IT</div>
            <div><strong>Email:</strong> admin@smkn1boyolangu.sch.id</div>
            <div><strong>Telepon:</strong> (0355) 323354</div>
        </div>

        <a href="{{ route('guru-piket.dashboard') }}" style="display: block; text-align: center; background: #2b43b9; color: white; padding: 10px; border-radius: 10px; font-weight: 700; font-size: 13px; text-decoration: none;">
            <i class="fa-solid fa-gauge-high"></i> Kembali ke Dashboard Piket
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFaq(el) {
        const body = el.nextElementSibling;
        const icon = el.querySelector('i');
        body.classList.toggle('show');
        if (body.classList.contains('show')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    function filterHelpContent() {
        const q = document.getElementById('helpSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.topic-card');
        const faqs = document.querySelectorAll('.faq-item');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(q) ? 'block' : 'none';
        });

        faqs.forEach(faq => {
            const text = faq.innerText.toLowerCase();
            faq.style.display = text.includes(q) ? 'block' : 'none';
        });
    }
</script>
@endsection
