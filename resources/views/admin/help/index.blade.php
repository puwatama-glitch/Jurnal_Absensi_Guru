@extends('layouts.admin')

@section('title', 'Pusat Bantuan & Panduan Resmi — SMKN 1 BOYOLANGU')
@section('header_title', 'Pusat Bantuan & Panduan Resmi')
@section('header_subtitle', 'Petunjuk penggunaan sistem jurnal absensi & rekapitulasi data SMKN 1 BOYOLANGU')

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
    .kpi-icon.indigo  { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .kpi-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
    .kpi-icon.amber   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .kpi-icon.rose    { background: linear-gradient(135deg, #ffe4e6, #fecdd3); color: #9f1239; }
    .kpi-val { font-size: 28px; font-weight: 900; color: #0f172a; line-height: 1.1; }
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
    .card-title { font-size: 17px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .card-title i { color: #2b43b9; }
    .card-sub { font-size: 13px; color: #64748b; font-weight: 500; margin-bottom: 18px; margin-top: -10px; }

    .topics-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .topic-card {
        border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px;
        transition: all 0.22s ease; cursor: pointer; background: #ffffff;
        text-decoration: none; color: inherit; display: block;
    }
    .topic-card:hover, .topic-card.active {
        transform: translateY(-2px);
        border-color: #c7d2fe;
        box-shadow: 0 8px 24px rgba(43,67,185,0.08);
    }
    .topic-card.hidden { display: none; }
    .topic-top { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 12px; }
    .topic-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
    }
    .topic-icon.indigo  { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .topic-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
    .topic-icon.amber   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .topic-icon.rose    { background: linear-gradient(135deg, #ffe4e6, #fecdd3); color: #9f1239; }
    .topic-icon.sky     { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }
    .topic-icon.violet  { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #5b21b6; }
    .topic-h3 { font-size: 14.5px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .topic-desc { font-size: 12.5px; color: #64748b; font-weight: 500; line-height: 1.5; }
    .topic-link {
        font-size: 12px; font-weight: 700; color: #2b43b9;
        display: inline-flex; align-items: center; gap: 6px;
    }

    /* Quick Links Sidebar */
    .quick-link-list { display: flex; flex-direction: column; gap: 8px; }
    .quick-link-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; border-radius: 12px;
        text-decoration: none; color: #334155;
        font-size: 13px; font-weight: 700;
        border: 1px solid #f1f5f9;
        transition: all 0.15s ease;
    }
    .quick-link-item:hover { background: #f8fafc; border-color: #e2e8f0; color: #2b43b9; }
    .quick-link-item i { width: 18px; text-align: center; color: #2b43b9; font-size: 14px; }

    /* FAQ */
    .faq-list { display: flex; flex-direction: column; gap: 10px; }
    .faq-item {
        border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; transition: all 0.2s ease;
    }
    .faq-item.hidden { display: none; }
    .faq-item.open { border-color: #c7d2fe; box-shadow: 0 4px 16px rgba(43,67,185,0.06); }
    .faq-question {
        padding: 15px 18px; background: #ffffff; font-size: 14px; font-weight: 700; color: #0f172a;
        cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        user-select: none; gap: 12px;
    }
    .faq-question:hover { background: #f8fafc; }
    .faq-item.open .faq-question { background: #f8fafc; color: #2b43b9; }
    .faq-question i { font-size: 12px; color: #64748b; transition: transform 0.25s ease; flex-shrink: 0; }
    .faq-item.open .faq-question i { transform: rotate(180deg); color: #2b43b9; }
    .faq-answer {
        display: none; padding: 0 18px 16px; font-size: 13.5px; color: #475569;
        font-weight: 500; line-height: 1.65; border-top: 1px solid #f1f5f9; padding-top: 14px;
    }
    .faq-item.open .faq-answer { display: block; }
    .step-badge {
        display: inline-block; background: #e0e7ff; color: #3730a3;
        font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 6px; margin-right: 4px;
    }

    .search-empty {
        display: none; text-align: center; padding: 40px 20px; color: #94a3b8;
    }
    .search-empty.show { display: block; }
    .search-empty i { font-size: 40px; margin-bottom: 12px; color: #cbd5e1; }
    .search-empty p { font-size: 15px; font-weight: 800; color: #0f172a; }
    .search-empty span { font-size: 13px; color: #64748b; }

    /* Support Banner */
    .support-banner {
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border: 1px solid #c7d2fe; border-radius: 20px; padding: 22px 26px;
        display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
    }
    .support-txt h4 { font-size: 16px; font-weight: 800; color: #1e1b4b; margin-bottom: 4px; }
    .support-txt p { font-size: 13px; color: #3730a3; font-weight: 500; }
    .support-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-support {
        border-radius: 12px; padding: 11px 20px; font-size: 13px; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;
    }
    .btn-support.primary {
        background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%);
        color: #ffffff; box-shadow: 0 4px 14px rgba(43,67,185,0.25);
    }
    .btn-support.primary:hover { transform: translateY(-2px); color: #ffffff; }
    .btn-support.secondary {
        background: #ffffff; color: #2b43b9; border: 1.5px solid #c7d2fe;
    }
    .btn-support.secondary:hover { background: #f8fafc; }

    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .help-layout { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .kpi-grid { grid-template-columns: 1fr; }
        .topics-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

{{-- Search Bar --}}
<div class="help-action-card">
    <div class="help-search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="helpSearchInput" class="help-search-input"
            placeholder="Cari panduan… (laporan, jurusan, kelas, password, soft delete)"
            onkeyup="filterHelpContent()">
    </div>
    <div class="help-search-meta">
        <span id="faqVisibleCount">8</span> topik bantuan tersedia
    </div>
</div>

{{-- Quick Stats --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon indigo"><i class="fa-solid fa-book-open"></i></div>
        <div>
            <div class="kpi-val">6</div>
            <div class="kpi-lbl">Modul Panduan</div>
            <div class="kpi-sub">Fitur utama aplikasi</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon emerald"><i class="fa-solid fa-circle-question"></i></div>
        <div>
            <div class="kpi-val">8</div>
            <div class="kpi-lbl">Pertanyaan FAQ</div>
            <div class="kpi-sub">Solusi instan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon amber"><i class="fa-solid fa-bolt"></i></div>
        <div>
            <div class="kpi-val">24/7</div>
            <div class="kpi-lbl">Akses Panduan</div>
            <div class="kpi-sub">Kapan saja dibutuhkan</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon rose"><i class="fa-solid fa-headset"></i></div>
        <div>
            <div class="kpi-val">IT</div>
            <div class="kpi-lbl">Dukungan Langsung</div>
            <div class="kpi-sub">Tim SMKN 1 Boyolangu</div>
        </div>
    </div>
</div>

<div class="help-layout">
    {{-- Topic Cards --}}
    <div class="help-main-card">
        <div class="card-hdr">
            <div class="card-title"><i class="fa-solid fa-table-cells-large"></i> Modul Panduan Aplikasi</div>
        </div>
        <p class="card-sub">Pilih topik untuk membuka panduan langkah demi langkah di bagian FAQ.</p>

        <div class="topics-grid" id="topicsGrid">
            <a href="#faq-1" class="topic-card" data-keywords="absensi jurnal mengajar presensi siswa hadir" onclick="openFaqItem(1); return false;">
                <div class="topic-top">
                    <div class="topic-icon indigo"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div>
                        <div class="topic-h3">Absensi &amp; Jurnal Mengajar</div>
                        <div class="topic-desc">Isi presensi harian, materi pembelajaran, dan verifikasi sesi kelas.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <a href="#faq-2" class="topic-card" data-keywords="laporan cetak pdf export csv excel rekap" onclick="openFaqItem(2); return false;">
                <div class="topic-top">
                    <div class="topic-icon emerald"><i class="fa-solid fa-file-invoice"></i></div>
                    <div>
                        <div class="topic-h3">Cetak &amp; Ekspor Laporan</div>
                        <div class="topic-desc">Filter periode, cetak PDF kop surat resmi, dan ekspor data ke CSV.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <a href="#faq-3" class="topic-card" data-keywords="master data siswa guru soft delete hapus" onclick="openFaqItem(3); return false;">
                <div class="topic-top">
                    <div class="topic-icon amber"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="topic-h3">Manajemen Siswa &amp; Guru</div>
                        <div class="topic-desc">Tambah, ubah, filter data induk, dan pemahaman soft delete.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <a href="#faq-4" class="topic-card" data-keywords="jurusan kelas rombel tingkat wali kelas" onclick="openFaqItem(4); return false;">
                <div class="topic-top">
                    <div class="topic-icon sky"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <div class="topic-h3">Manajemen Jurusan &amp; Kelas</div>
                        <div class="topic-desc">Kelola program keahlian, rombongan belajar, dan wali kelas.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <a href="#faq-5" class="topic-card" data-keywords="profil password foto akun keamanan role" onclick="openFaqItem(5); return false;">
                <div class="topic-top">
                    <div class="topic-icon rose"><i class="fa-solid fa-user-shield"></i></div>
                    <div>
                        <div class="topic-h3">Akun &amp; Keamanan</div>
                        <div class="topic-desc">Ubah foto profil, ganti password, dan pemahaman peran pengguna.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <a href="#faq-6" class="topic-card" data-keywords="dispensasi izin satpam guru piket wali kelas" onclick="openFaqItem(6); return false;">
                <div class="topic-top">
                    <div class="topic-icon violet"><i class="fa-solid fa-id-card"></i></div>
                    <div>
                        <div class="topic-h3">Dispensasi &amp; Izin Siswa</div>
                        <div class="topic-desc">Alur input dispensasi, persetujuan, dan rekapitulasi laporan.</div>
                    </div>
                </div>
                <span class="topic-link">Buka panduan <i class="fa-solid fa-arrow-right"></i></span>
            </a>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="help-side-card">
        <div class="card-hdr">
            <div class="card-title"><i class="fa-solid fa-link"></i> Akses Cepat Menu</div>
        </div>
        <p class="card-sub">Langsung buka halaman fitur yang ingin dipelajari.</p>
        <div class="quick-link-list">
            <a href="{{ route('admin.dashboard') }}" class="quick-link-item"><i class="fa-solid fa-border-all"></i> Dashboard</a>
            <a href="{{ route('admin.absensi') }}" class="quick-link-item"><i class="fa-solid fa-clipboard-check"></i> Absensi &amp; Jurnal</a>
            <a href="{{ route('admin.master.siswa') }}" class="quick-link-item"><i class="fa-solid fa-users"></i> Manajemen Siswa</a>
            <a href="{{ route('admin.master.guru') }}" class="quick-link-item"><i class="fa-solid fa-chalkboard-user"></i> Manajemen Guru</a>
            <a href="{{ route('admin.master.jurusan') }}" class="quick-link-item"><i class="fa-solid fa-layer-group"></i> Manajemen Jurusan</a>
            <a href="{{ route('admin.master.kelas') }}" class="quick-link-item"><i class="fa-solid fa-door-open"></i> Manajemen Kelas</a>
            <a href="{{ route('admin.laporan') }}" class="quick-link-item"><i class="fa-solid fa-book"></i> Laporan</a>
            <a href="{{ route('admin.profil') }}" class="quick-link-item"><i class="fa-solid fa-user-pen"></i> Profil Saya</a>
        </div>
    </div>
</div>

{{-- FAQ Section --}}
<div class="help-main-card" style="margin-bottom:24px;">
    <div class="card-hdr">
        <div class="card-title"><i class="fa-solid fa-comments"></i> Pertanyaan Sering Diajukan (FAQ)</div>
    </div>
    <p class="card-sub">Temukan solusi instan untuk kendala teknis yang sering ditemui pengguna.</p>

    <div class="search-empty" id="searchEmpty">
        <i class="fa-solid fa-magnifying-glass"></i>
        <p>Tidak ada hasil ditemukan</p>
        <span>Coba kata kunci lain, misalnya: jurusan, laporan, password</span>
    </div>

    <div class="faq-list" id="faqList">
        <div class="faq-item" id="faq-1" data-keywords="absensi jurnal mengajar presensi siswa hadir sakit izin alpha">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>1. Bagaimana cara mengisi Absensi Siswa &amp; Jurnal Mengajar Harian?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Langkah 1</span> Masuk ke menu <strong>Absensi &amp; Jurnal Mengajar</strong> dari sidebar.<br>
                <span class="step-badge">Langkah 2</span> Pilih tanggal dan kelas mengajar yang sesuai jadwal.<br>
                <span class="step-badge">Langkah 3</span> Tentukan status kehadiran siswa (Hadir, Sakit, Izin, Alpha, Dispensasi).<br>
                <span class="step-badge">Langkah 4</span> Isi uraian materi pembelajaran pada kolom Jurnal Mengajar, lalu klik <strong>Simpan Presensi</strong>.
            </div>
        </div>

        <div class="faq-item" id="faq-2" data-keywords="laporan cetak pdf export csv print kop surat">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>2. Bagaimana cara mencetak Laporan Absensi dengan Kop Surat Resmi?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Langkah 1</span> Buka menu <strong>Laporan</strong> dari sidebar.<br>
                <span class="step-badge">Langkah 2</span> Pilih tab laporan yang diinginkan (Absensi Siswa, Jurnal Mengajar, Dispensasi, dll).<br>
                <span class="step-badge">Langkah 3</span> Atur periode tanggal awal &amp; akhir, serta filter kelas jika diperlukan.<br>
                <span class="step-badge">Langkah 4</span> Klik tombol <strong>Cetak / Print PDF</strong> atau <strong>Ekspor CSV</strong> sesuai kebutuhan.
            </div>
        </div>

        <div class="faq-item" id="faq-3" data-keywords="master data siswa guru soft delete hapus filter">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>3. Bagaimana mengelola data Siswa dan Guru di Master Data?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Tambah Data</span> Buka <strong>Master Data → Manajemen Siswa/Guru</strong>, klik tombol tambah, isi formulir, lalu simpan.<br>
                <span class="step-badge">Filter</span> Gunakan kolom pencarian dan filter status/kelas untuk menemukan data dengan cepat.<br>
                <span class="step-badge">Soft Delete</span> Data yang dihapus tidak hilang permanen — sistem menandai dengan <code>deleted_at</code> agar riwayat jurnal &amp; absensi tetap aman.
            </div>
        </div>

        <div class="faq-item" id="faq-4" data-keywords="jurusan kelas rombel tingkat wali kelas master data">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>4. Bagaimana cara mengelola Jurusan dan Kelas (Rombel)?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Jurusan</span> Buka <strong>Master Data → Manajemen Jurusan</strong> untuk menambah/mengubah program keahlian (kode &amp; nama jurusan).<br>
                <span class="step-badge">Kelas</span> Buka <strong>Master Data → Manajemen Kelas</strong> untuk membuat rombel (tingkat X/XI/XII, jurusan, wali kelas).<br>
                <span class="step-badge">Catatan</span> Jurusan yang masih memiliki kelas tidak dapat dihapus. Kelas yang masih memiliki siswa juga tidak dapat dihapus.
            </div>
        </div>

        <div class="faq-item" id="faq-5" data-keywords="profil password foto akun keamanan ganti">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>5. Bagaimana cara mengganti Password dan Foto Profil?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Foto Profil</span> Klik foto/nama di header kanan atas, pilih <strong>Profil Saya</strong>, lalu unggah foto baru (maks. 2MB).<br>
                <span class="step-badge">Password</span> Di halaman profil, masuk ke bagian <strong>Keamanan Akun</strong>, isi password lama &amp; baru (min. 8 karakter), lalu klik <strong>Perbarui Password</strong>.
            </div>
        </div>

        <div class="faq-item" id="faq-6" data-keywords="dispensasi izin satpam guru piket wali kelas kepsek">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>6. Siapa yang dapat menginput dan menyetujui Dispensasi Siswa?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Dispensasi diinput oleh <strong>Guru Piket / Satpam</strong> saat siswa izin keluar sekolah. Persetujuan diproses oleh <strong>Kepala Sekolah / Waka</strong> atau <strong>Wali Kelas</strong>. Riwayat dispensasi terekap otomatis di tab <strong>Laporan Dispensasi Siswa</strong>.
            </div>
        </div>

        <div class="faq-item" id="faq-7" data-keywords="login akun guru admin role hak akses">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>7. Apa perbedaan peran (role) pengguna dalam sistem?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <strong>Admin</strong> — akses penuh dashboard, master data, dan laporan.<br>
                <strong>Guru Mapel / Wali Kelas</strong> — mengisi jurnal &amp; absensi kelas.<br>
                <strong>Guru Piket / Satpam</strong> — input dispensasi &amp; monitoring kehadiran.<br>
                <strong>Kepala Sekolah / Waka</strong> — persetujuan dispensasi &amp; monitoring laporan.
            </div>
        </div>

        <div class="faq-item" id="faq-8" data-keywords="error gagal simpan validasi database kendala teknis">
            <div class="faq-question" onclick="toggleFaq(this)">
                <span>8. Data gagal disimpan — apa yang harus dilakukan?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="step-badge">Langkah 1</span> Periksa notifikasi error merah di pojok kanan atas — biasanya berisi pesan validasi (NIS/NIP duplikat, field wajib kosong, dll).<br>
                <span class="step-badge">Langkah 2</span> Pastikan semua field bertanda <strong>*</strong> sudah diisi dengan benar.<br>
                <span class="step-badge">Langkah 3</span> Jika masalah berlanjut, hubungi Tim IT melalui tombol WhatsApp di bawah halaman ini.
            </div>
        </div>
    </div>
</div>

{{-- Support Banner --}}
<div class="support-banner">
    <div class="support-txt">
        <h4><i class="fa-solid fa-headset"></i> Masih Mengalami Kendala Teknis?</h4>
        <p>Hubungi Tim IT &amp; Administrator Sistem SMKN 1 Boyolangu untuk bantuan langsung.</p>
    </div>
    <div class="support-actions">
        <a href="mailto:puwatama@gmail.com" class="btn-support secondary">
            <i class="fa-solid fa-envelope"></i> Email IT
        </a>
        <a href="https://wa.me/6283827774389?text=Halo%20Tim%20IT%20SMKN%201%20Boyolangu,%20saya%20butuh%20bantuan%20sistem%20Jurnal%20Absensi" target="_blank" rel="noopener" class="btn-support primary">
            <i class="fa-brands fa-whatsapp"></i> Hubungi via WhatsApp
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleFaq(el) {
    el.parentElement.classList.toggle('open');
}

function openFaqItem(num) {
    document.querySelectorAll('.topic-card').forEach(c => c.classList.remove('active'));
    const topic = document.querySelector(`.topic-card[href="#faq-${num}"]`);
    if (topic) topic.classList.add('active');

    const item = document.getElementById('faq-' + num);
    if (item) {
        item.classList.remove('hidden');
        item.classList.add('open');
        item.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function filterHelpContent() {
    const query = document.getElementById('helpSearchInput').value.toLowerCase().trim();
    let faqVisible = 0;

    document.querySelectorAll('.faq-item').forEach(item => {
        const text = (item.textContent + ' ' + (item.dataset.keywords || '')).toLowerCase();
        const match = query === '' || text.includes(query);
        item.classList.toggle('hidden', !match);
        if (match) {
            faqVisible++;
            if (query !== '') item.classList.add('open');
        }
    });

    document.querySelectorAll('.topic-card').forEach(card => {
        const text = (card.textContent + ' ' + (card.dataset.keywords || '')).toLowerCase();
        card.classList.toggle('hidden', query !== '' && !text.includes(query));
    });

    document.getElementById('faqVisibleCount').textContent = faqVisible;
    document.getElementById('searchEmpty').classList.toggle('show', query !== '' && faqVisible === 0);
}
</script>
@endsection
