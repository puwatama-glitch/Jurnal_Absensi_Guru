@extends('layouts.satpam')

@section('title', 'Dashboard Pos Jaga - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Dashboard Pos Keamanan Gate')
@section('header_subtitle', 'Pantau arus keluar-masuk siswa berizin dan pencatatan buku tamu sekolah secara real-time')

@section('header_extra')
    <div style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 14px; padding: 8px 16px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-shield-halved" style="font-size: 20px; color: #a5b4fc;"></i>
        <div>
            <div style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.85; font-weight: 700;">Petugas Jaga Gerbang</div>
            <div style="font-size: 13.5px; font-weight: 800; color: #ffffff;">{{ $satpam->nama_lengkap ?? (Auth::user()->name ?? 'Petugas Satpam') }}</div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Section Greeting */
    .greeting-section {
        margin-bottom: 24px;
    }

    .greeting-title {
        font-size: 22px;
        font-weight: 700;
        color: #1b2559;
        margin-bottom: 4px;
    }

    .greeting-subtitle {
        font-size: 14px;
        color: #6b7a99;
        font-weight: 500;
    }

    /* Top 5 Stat Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #707e94;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .stat-icon.blue   { background-color: #eef2ff; color: #2b43b9; }
    .stat-icon.green  { background-color: #e6f9f0; color: #10b981; }
    .stat-icon.orange { background-color: #fff7ed; color: #f97316; }
    .stat-icon.red    { background-color: #fef2f2; color: #ef4444; }
    .stat-icon.dark   { background-color: #f1f5f9; color: #334155; }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #1b2559;
        line-height: 1.1;
        margin-bottom: 8px;
    }

    .stat-footer {
        font-size: 12px;
        color: #707e94;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-badge-green { color: #10b981; font-weight: 700; }
    .stat-badge-red   { color: #ef4444; font-weight: 700; }

    /* Action Buttons */
    .btn-action-primary {
        background: #2b43b9;
        color: white;
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(43, 67, 185, 0.2);
    }
    .btn-action-primary:hover {
        background: #1e35a0;
        transform: translateY(-2px);
    }

    .btn-action-secondary {
        background: white;
        color: #1b2559;
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-action-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Section Cards */
    .section-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
        margin-bottom: 24px;
    }

    .section-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b2559;
    }

    /* Live Feed Grid */
    .live-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .student-live-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease;
    }

    .student-live-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }

    .status-pill {
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-pill.warning { background: #fff7ed; color: #f97316; }
    .status-pill.success { background: #e6f9f0; color: #10b981; }
    .status-pill.danger  { background: #fef2f2; color: #ef4444; }
    .status-pill.info    { background: #e0f2fe; color: #0369a1; }

    /* Custom Table Styling */
    .table-responsive { overflow-x: auto; }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }
    .custom-table th {
        background-color: #f8fafc;
        padding: 12px 14px;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        color: #707e94;
        letter-spacing: 0.5px;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .custom-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f4f7fe;
        color: #2b3674;
        vertical-align: middle;
    }
    .custom-table tr:hover td { background-color: #f8fafc; }

    /* Modal Form */
    .custom-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
    }
    .custom-modal-backdrop.show { display: flex; }
    .custom-modal-card {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    .modal-head {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }
    .modal-head h3 { font-size: 16px; font-weight: 800; color: #1b2559; display: flex; align-items: center; gap: 8px; }
    .modal-close-btn { background: none; border: none; font-size: 18px; color: #94a3b8; cursor: pointer; }
    .modal-body { padding: 22px 24px; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 700; color: #2b3674; margin-bottom: 6px; }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        font-size: 13.5px;
        font-family: inherit;
        color: #1b2559;
        outline: none;
    }
    .form-control:focus { border-color: #2b43b9; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- 1. Greeting Section & Quick Controls -->
<div class="greeting-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 class="greeting-title">Halo, {{ $satpam->nama_lengkap ?? (Auth::user()->name ?? 'Petugas Satpam') }} 👋</h2>
        <p class="greeting-subtitle">Berikut adalah ringkasan lalu lintas gerbang, status siswa keluar, dan buku tamu hari ini ({{ $todayFormatted }}).</p>
    </div>

    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <button type="button" class="btn-action-primary" onclick="openTamuModal()">
            <i class="fa-solid fa-user-plus"></i> + Catat Tamu Baru
        </button>
        <a href="{{ route('satpam.dispensasi') }}" class="btn-action-secondary">
            <i class="fa-solid fa-ticket-simple"></i> Verifikasi Dispensasi
        </a>
        <a href="{{ route('satpam.monitoring') }}" class="btn-action-secondary">
            <i class="fa-solid fa-tower-observation"></i> Live Monitoring
        </a>
    </div>
</div>

<!-- 2. Top 5 Stat Cards Grid -->
<div class="stats-grid">
    <!-- Card 1: Siswa Sedang di Luar -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Siswa di Luar</span>
            <div class="stat-icon orange"><i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i></div>
        </div>
        <div>
            <div class="stat-value">{{ $metrics['siswa_di_luar'] }} <span style="font-size: 14px; color: #6b7a99; font-weight: 600;">Siswa</span></div>
            <div class="stat-footer">
                @if($metrics['siswa_di_luar'] > 0)
                    <span class="stat-badge-red">Sedang di Luar</span> gerbang
                @else
                    <span class="stat-badge-green">Semua Berada</span> di dalam
                @endif
            </div>
        </div>
    </div>

    <!-- Card 2: Siswa Selesai Kembali -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Siswa Kembali</span>
            <div class="stat-icon green"><i class="fa-solid fa-rotate-left"></i></div>
        </div>
        <div>
            <div class="stat-value">{{ $metrics['siswa_kembali'] }} <span style="font-size: 14px; color: #6b7a99; font-weight: 600;">Siswa</span></div>
            <div class="stat-footer">
                <span class="stat-badge-green">Tertib Masuk</span> kembali
            </div>
        </div>
    </div>

    <!-- Card 3: Total Dispensasi Hari Ini -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Izin Dispensasi</span>
            <div class="stat-icon blue"><i class="fa-solid fa-ticket-simple"></i></div>
        </div>
        <div>
            <div class="stat-value">{{ $metrics['total_dispensasi'] }} <span style="font-size: 14px; color: #6b7a99; font-weight: 600;">Izin</span></div>
            <div class="stat-footer">
                Diterbitkan Guru Piket
            </div>
        </div>
    </div>

    <!-- Card 4: Tamu Aktif di Sekolah -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Tamu di Dalam</span>
            <div class="stat-icon red"><i class="fa-solid fa-id-badge"></i></div>
        </div>
        <div>
            <div class="stat-value">{{ $metrics['tamu_aktif'] }} <span style="font-size: 14px; color: #6b7a99; font-weight: 600;">Tamu</span></div>
            <div class="stat-footer">
                @if($metrics['tamu_aktif'] > 0)
                    <span class="stat-badge-red">Masih Berada</span> di area sekolah
                @else
                    <span class="stat-badge-green">Nihil</span> tamu aktif
                @endif
            </div>
        </div>
    </div>

    <!-- Card 5: Total Kunjungan Tamu -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Tamu Hari Ini</span>
            <div class="stat-icon dark"><i class="fa-solid fa-address-book"></i></div>
        </div>
        <div>
            <div class="stat-value">{{ $metrics['total_tamu'] }} <span style="font-size: 14px; color: #6b7a99; font-weight: 600;">Orang</span></div>
            <div class="stat-footer">
                Tercatat di pos jaga
            </div>
        </div>
    </div>
</div>

<!-- 3. Live Feed: Siswa Sedang Berada di Luar Lingkungan Sekolah -->
<div class="section-card">
    <div class="section-card-header">
        <div>
            <h3 class="section-title">
                <i class="fa-solid fa-tower-observation" style="color: #f97316; margin-right: 6px;"></i>
                Siswa Sedang Berada di Luar Gerbang ({{ $siswaDiLuar->count() }})
            </h3>
            <span style="font-size: 12px; color: #6b7a99;">Konfirmasi kembali saat siswa melintasi pos gerbang sekolah</span>
        </div>
        <a href="{{ route('satpam.monitoring') }}" style="font-size: 13px; font-weight: 700; color: #2b43b9; text-decoration: none;">
            Monitoring Lengkap <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="live-cards-grid">
        @forelse($siswaDiLuar as $sd)
        <div class="student-live-card">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <strong style="color: #1b2559; font-size: 15px;">{{ $sd->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                        <div style="font-size: 12px; color: #6b7a99; font-weight: 600;">
                            {{ $sd->siswa->kelas->nama_kelas ?? '-' }} &bull; NISN: {{ $sd->siswa->nisn ?? '-' }}
                        </div>
                    </div>
                    <span class="status-pill warning">Di Luar</span>
                </div>

                <div style="font-size: 12.5px; color: #2b3674; display: flex; flex-direction: column; gap: 4px; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
                    <div>
                        <i class="fa-solid fa-arrow-right-from-bracket" style="color: #ef4444; width: 16px;"></i>
                        Jam Keluar: <strong>{{ Carbon\Carbon::parse($sd->jam_keluar)->format('H:i') }} WIB</strong>
                    </div>
                    <div>
                        <i class="fa-solid fa-clock" style="color: #6b7a99; width: 16px;"></i>
                        Keperluan: {{ $sd->alasan }}
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 10px;">
                <form action="{{ route('satpam.dispensasi.status', $sd->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="kembali">
                    <button type="submit" class="btn-action-primary" style="width: 100%; background: #10b981; justify-content: center; font-size: 12.5px;">
                        <i class="fa-solid fa-check"></i> Konfirmasi Siswa Kembali Masuk
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; padding: 36px; text-align: center; color: #6b7a99; background: #f8fafc; border-radius: 14px;">
            <i class="fa-solid fa-circle-check" style="font-size: 32px; color: #10b981; margin-bottom: 8px;"></i>
            <div style="font-weight: 700; color: #1b2559; font-size: 14px;">Tidak Ada Siswa yang Sedang di Luar Gerbang</div>
            <div style="font-size: 12.5px; color: #6b7a99; margin-top: 2px;">Seluruh siswa yang memiliki izin dispensasi telah kembali ke sekolah.</div>
        </div>
        @endforelse
    </div>
</div>

<!-- 4. Log Buku Tamu Terkini -->
<div class="section-card">
    <div class="section-card-header">
        <div>
            <h3 class="section-title">
                <i class="fa-solid fa-address-book" style="color: #2b43b9; margin-right: 6px;"></i>
                Log Kunjungan Tamu Hari Ini
            </h3>
            <span style="font-size: 12px; color: #6b7a99;">Daftar tamu kedinasan & wali murid yang berkunjung ke sekolah</span>
        </div>
        <a href="{{ route('satpam.buku-tamu') }}" style="font-size: 13px; font-weight: 700; color: #2b43b9; text-decoration: none;">
            Semua Buku Tamu <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Waktu Masuk</th>
                    <th>Nama Tamu & Instansi</th>
                    <th>Keperluan / Bertemu</th>
                    <th>No. Kendaraan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allTamuToday as $tamu)
                <tr>
                    <td>
                        <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($tamu->jam_masuk)->format('H:i') }} WIB</strong>
                        @if($tamu->jam_keluar)
                            <div style="font-size: 11px; color: #10b981;">Keluar: {{ Carbon\Carbon::parse($tamu->jam_keluar)->format('H:i') }}</div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #1b2559;">{{ $tamu->nama_tamu }}</strong>
                        <div style="font-size: 11.5px; color: #6b7a99;">{{ $tamu->instansi ?? 'Pribadi / Wali Murid' }}</div>
                    </td>
                    <td>
                        <div><strong>Bertemu:</strong> {{ $tamu->bertemu_dengan }}</div>
                        <div style="font-size: 11.5px; color: #6b7a99;">{{ $tamu->keperluan }}</div>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #334155;">{{ $tamu->no_kendaraan ?? '-' }}</span>
                    </td>
                    <td>
                        @if($tamu->status === 'Di Dalam')
                            <span class="status-pill danger">Masih di Sekolah</span>
                        @else
                            <span class="status-pill success">Sudah Keluar</span>
                        @endif
                    </td>
                    <td>
                        @if($tamu->status === 'Di Dalam')
                            <form action="{{ route('satpam.buku-tamu.checkout', $tamu->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action-secondary" style="padding: 4px 10px; font-size: 11.5px; color: #ef4444; border-color: #fca5a5;">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Checkout
                                </button>
                            </form>
                        @else
                            <span style="color: #94a3b8; font-size: 12px;">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7a99; padding: 32px;">
                        <i class="fa-solid fa-id-badge" style="font-size: 28px; margin-bottom: 6px; color: #cbd5e1;"></i>
                        <div>Belum ada tamu yang tercatat hari ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Tamu Baru -->
<div class="custom-modal-backdrop" id="tamuModal">
    <div class="custom-modal-card">
        <div class="modal-head">
            <h3><i class="fa-solid fa-id-card" style="color: #2b43b9;"></i> Catat Kunjungan Tamu Baru</h3>
            <button type="button" class="modal-close-btn" onclick="closeTamuModal()">&times;</button>
        </div>
        <form action="{{ route('satpam.buku-tamu.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap Tamu <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_tamu" class="form-control" placeholder="Nama lengkap..." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Instansi / Asal Tamu</label>
                        <input type="text" name="instansi" class="form-control" placeholder="Contoh: Dinas Pendidikan / Wali Murid...">
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="08...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Bertemu dengan Siapa? <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="bertemu_dengan" class="form-control" placeholder="Contoh: Kepala Sekolah / Waka / Guru X..." required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Polisi / Kendaraan</label>
                        <input type="text" name="no_kendaraan" class="form-control" placeholder="Contoh: AG 1234 XY">
                    </div>
                </div>

                <div class="form-group">
                    <label>Jam Masuk Gerbang <span style="color:#ef4444;">*</span></label>
                    <input type="time" name="jam_masuk" value="{{ now()->format('H:i') }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Maksud & Keperluan Kunjungan <span style="color:#ef4444;">*</span></label>
                    <textarea name="keperluan" rows="3" class="form-control" placeholder="Tuliskan tujuan kunjungan..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action-secondary" onclick="closeTamuModal()">Batal</button>
                <button type="submit" class="btn-action-primary">Simpan Buku Tamu</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTamuModal() {
        document.getElementById('tamuModal').classList.add('show');
    }
    function closeTamuModal() {
        document.getElementById('tamuModal').classList.remove('show');
    }
</script>
@endsection
