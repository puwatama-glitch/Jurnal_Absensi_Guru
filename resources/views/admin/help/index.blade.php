@extends('layouts.admin')

@section('title', 'Pusat Bantuan & Panduan — SMKN 1 BOYOLANGU')
@section('header_title', 'Pusat Bantuan & Panduan Resmi')
@section('header_subtitle', 'Petunjuk penggunaan sistem jurnal absensi & rekapitulasi data SMKN 1 BOYOLANGU')

@section('styles')
<style>
    /* ── Search Hero ── */
    .help-search-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 32px;
        color: #ffffff;
        margin-bottom: 28px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.12);
        position: relative;
        overflow: hidden;
    }
    .help-search-card::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .search-title { font-size: 22px; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.3px; }
    .search-sub { font-size: 13.5px; color: #94a3b8; font-weight: 500; margin-bottom: 20px; }

    .help-search-box {
        position: relative;
        max-width: 600px;
    }
    .help-search-box i {
        position: absolute;
        left: 18px; top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 16px;
    }
    .help-search-input {
        width: 100%;
        background: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 14px 18px 14px 48px;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        font-family: inherit;
    }
    .help-search-input::placeholder { color: #94a3b8; font-weight: 500; }

    /* ── Topic Cards Grid ── */
    .topics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .topic-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .topic-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }

    .topic-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; margin-bottom: 16px;
    }
    .topic-icon.indigo  { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .topic-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
    .topic-icon.amber   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .topic-icon.rose    { background: linear-gradient(135deg, #ffe4e6, #fecdd3); color: #9f1239; }

    .topic-h3 { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .topic-desc { font-size: 12.5px; color: #64748b; font-weight: 500; line-height: 1.5; margin-bottom: 16px; }

    .topic-link {
        font-size: 13px;
        font-weight: 700;
        color: #2b43b9;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s ease;
    }
    .topic-card:hover .topic-link { gap: 10px; }

    /* ── FAQ Section ── */
    .faq-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 28px;
    }

    .sec-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sec-title i { color: #2b43b9; }
    .sec-sub { font-size: 13px; color: #64748b; font-weight: 500; margin-bottom: 22px; }

    /* Accordion */
    .faq-list { display: flex; flex-direction: column; gap: 12px; }
    
    .faq-item {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .faq-item.open {
        border-color: #c7d2fe;
        box-shadow: 0 4px 16px rgba(43,67,185,0.06);
    }

    .faq-question {
        padding: 16px 20px;
        background: #ffffff;
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
        transition: background 0.15s ease;
    }
    .faq-question:hover { background: #f8fafc; }
    .faq-item.open .faq-question { background: #f8fafc; color: #2b43b9; }

    .faq-question i {
        font-size: 13px;
        color: #64748b;
        transition: transform 0.25s ease;
    }
    .faq-item.open .faq-question i {
        transform: rotate(180deg);
        color: #2b43b9;
    }

    .faq-answer {
        padding: 0 20px 18px;
        font-size: 13.5px;
        color: #475569;
        font-weight: 500;
        line-height: 1.6;
        display: none;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        margin-top: 4px;
        padding-top: 14px;
    }
    .faq-item.open .faq-answer { display: block; }

    /* Step Pill */
    .step-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #3730a3;
        font-weight: 800;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 6px;
        margin-right: 6px;
    }

    /* ── Contact Support Box ── */
    .support-banner {
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border: 1px solid #c7d2fe;
        border-radius: 20px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .support-txt h4 { font-size: 16px; font-weight: 800; color: #1e1b4b; margin-bottom: 4px; }
    .support-txt p { font-size: 13px; color: #3730a3; font-weight: 500; }

    .btn-contact-support {
        background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px 22px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(43,67,185,0.25);
        white-space: nowrap;
    }
    .btn-contact-support:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(43,67,185,0.35); color: #ffffff; }
</style>
@endsection

@section('content')

{{-- Hero & Search --}}
<div class="help-search-card">
    <div class="search-title"><i class="fa-solid fa-circle-question"></i> Pusat Bantuan & Panduan Penggunaan</div>
    <div class="search-sub">Cari jawaban cepat atau pelajari panduan penggunaan fitur aplikasi Jurnal Absensi SMKN 1 Boyolangu</div>

    <div class="help-search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="helpSearchInput" class="help-search-input" placeholder="Ketik kata kunci (contoh: cetak laporan, soft delete, dispensasi, password)..." onkeyup="filterHelpTopics()">
    </div>
</div>

{{-- 4 Modul Utama --}}
<div class="topics-grid">
    <div class="topic-card">
        <div>
            <div class="topic-icon indigo"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="topic-h3">Absensi & Jurnal</div>
            <div class="topic-desc">Cara mengisi presensi siswa harian, catat materi mengajar, dan verifikasi sesi kelas.</div>
        </div>
        <a href="#faq-1" class="topic-link" onclick="openFaqItem(1)">Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="topic-card">
        <div>
            <div class="topic-icon emerald"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="topic-h3">Cetak Laporan</div>
            <div class="topic-desc">Panduan memfilter periode, cetak PDF Kop Surat resmi sekolah, & ekspor ke CSV/Excel.</div>
        </div>
        <a href="#faq-2" class="topic-link" onclick="openFaqItem(2)">Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="topic-card">
        <div>
            <div class="topic-icon amber"><i class="fa-solid fa-database"></i></div>
            <div class="topic-h3">Master Data</div>
            <div class="topic-desc">Pengelolaan data siswa & guru, filter rombel, serta pemulihan data terhapus (Soft Delete).</div>
        </div>
        <a href="#faq-3" class="topic-link" onclick="openFaqItem(3)">Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="topic-card">
        <div>
            <div class="topic-icon rose"><i class="fa-solid fa-user-shield"></i></div>
            <div class="topic-h3">Akun & Keamanan</div>
            <div class="topic-desc">Ubah foto profil, ganti password akun, dan penjelas peran/hak akses (Role Management).</div>
        </div>
        <a href="#faq-4" class="topic-link" onclick="openFaqItem(4)">Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>

{{-- Interactive FAQ Section --}}
<div class="faq-card">
    <div class="sec-title">
        <i class="fa-solid fa-comments"></i>
        <span>Pertanyaan Sering Diajukan (FAQ)</span>
    </div>
    <div class="sec-sub">Temukan solusi instan untuk kendala teknis yang sering ditemui.</div>

    <div class="faq-list" id="faqList">

        <div class="faq-item" id="faq-1">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>1. Bagaimana cara mengisi Absensi Siswa &amp; Jurnal Mengajar Harian?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Langkah 1</span> Masuk ke menu <strong>Absensi &amp; Jurnal Mengajar</strong> dari sidebar.<br>
                <span class="step-badge">Langkah 2</span> Pilih tanggal dan kelas mengajar yang sesuai.<br>
                <span class="step-badge">Langkah 3</span> Tentukan status kehadiran siswa (Hadir, Sakit, Izin, Alpha, Dispensasi).<br>
                <span class="step-badge">Langkah 4</span> Isi uraian materi pembelajaran pada kolom Jurnal Mengajar, lalu klik <strong>Simpan Presensi</strong>.
            </div>
        </div>

        <div class="faq-item" id="faq-2">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>2. Bagaimana cara mencetak Laporan Absensi dengan Kop Surat Resmi SMKN 1 Boyolangu?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Langkah 1</span> Buka menu <strong>Laporan</strong> dari sidebar.<br>
                <span class="step-badge">Langkah 2</span> Pilih tab laporan yang diinginkan (Absensi Siswa, Jurnal Mengajar, Dispensasi, dll).<br>
                <span class="step-badge">Langkah 3</span> Atur periode tanggal awal &amp; akhir, serta filter kelas jika diperlukan.<br>
                <span class="step-badge">Langkah 4</span> Klik tombol <strong>Cetak / Print PDF</strong> di pojok kanan atas filter bar. Halaman cetak lengkap dengan Kop Surat &amp; tanda tangan pengesahan akan terbuka otomatis.
            </div>
        </div>

        <div class="faq-item" id="faq-3">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>3. Bagaimana sistem Soft Delete melindungi data siswa dan guru dari terhapus tidak sengaja?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Aplikasi ini dilengkapi standar <strong>Soft Delete</strong> profesional. Ketika Anda menghapus data siswa atau guru dari menu Master Data, data tidak benar-benar hilang permanen dari database melainkan diberi penanda `deleted_at`. Hal ini menjaga integritas riwayat jurnal dan absensi terdahulu agar tidak merusak laporan historis sekolah.
            </div>
        </div>

        <div class="faq-item" id="faq-4">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>4. Bagaimana cara mengganti Password dan Foto Profil saya?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Foto Profil</span> Klik foto/nama di pojok kanan header banner, pilih <strong>Profil Saya</strong>. Klik ikon kamera pada lingkaran foto untuk mengunggah foto baru (maksimal 2MB).<br>
                <span class="step-badge">Ganti Password</span> Pada halaman profil, gulir ke section <strong>Keamanan Akun</strong>, masukkan password lama dan password baru (minimal 8 karakter), lalu klik <strong>Perbarui Password</strong>.
            </div>
        </div>

        <div class="faq-item" id="faq-5">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>5. Siapa saja yang dapat mengakses dan memunculkan dispensasi siswa?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Dispensasi siswa diinput oleh <strong>Guru Piket / Satpam</strong> ketika siswa izin keluar lingkungan sekolah. Persetujuan dispensasi diproses oleh **Kepala Sekolah / Waka** atau **Wali Kelas**, dan riwayatnya terekap secara otomatis pada tab <strong>Laporan Dispensasi Siswa</strong>.
            </div>
        </div>

    </div>
</div>

{{-- Contact Support Box --}}
<div class="support-banner">
    <div class="support-txt">
        <h4><i class="fa-solid fa-headset"></i> Masih Mengalami Kendala Teknis?</h4>
        <p>Hubungi Tim IT &amp; Administrator Sistem SMKN 1 Boyolangu untuk bantuan langsung.</p>
    </div>
    <a href="https://wa.me/6281234567890?text=Halo%20Tim%20IT%20SMKN%201%20Boyolangu,%20saya%20butuh%20bantuan%20sistem%20Jurnal%20Absensi" target="_blank" class="btn-contact-support">
        <i class="fa-brands fa-whatsapp"></i> Hubungi Tim IT
    </a>
</div>

@endsection

@section('scripts')
<script>
function toggleFaq(el) {
    const item = el.parentElement;
    item.classList.toggle('open');
}

function openFaqItem(num) {
    const item = document.getElementById('faq-' + num);
    if (item) {
        item.classList.add('open');
        item.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function filterHelpTopics() {
    const input = document.getElementById('helpSearchInput').value.toLowerCase();
    const items = document.querySelectorAll('.faq-item');

    items.forEach(item => {
        const txt = item.textContent.toLowerCase();
        if (txt.includes(input)) {
            item.style.display = 'block';
            if (input.trim() !== '') item.classList.add('open');
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
