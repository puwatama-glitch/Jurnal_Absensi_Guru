@extends('layouts.satpam')

@section('title', 'Buku Tamu Digital - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Buku Tamu Digital')
@section('header_subtitle', 'Pencatatan identitas tamu kedinasan, wali murid, dan instansi luar yang berkunjung')

@section('header_extra')
    <button type="button" class="btn-action-primary" onclick="openTamuModal()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">
        <i class="fa-solid fa-user-plus"></i> + Catat Tamu Baru
    </button>
@endsection

@section('styles')
<style>
    /* Stats */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .summary-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .summary-icon.red  { background: #fef2f2; color: #ef4444; }
    .summary-icon.blue { background: #eef2ff; color: #2b43b9; }
    .summary-val { font-size: 26px; font-weight: 800; color: #1b2559; line-height: 1.1; }
    .summary-label { font-size: 12px; font-weight: 700; color: #707e94; text-transform: uppercase; margin-top: 2px; }

    /* Filter Bar */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .filter-input, .filter-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 13px;
        font-family: inherit;
        font-weight: 600;
        color: #1b2559;
        outline: none;
    }
    .filter-input:focus, .filter-select:focus { border-color: #2b43b9; }

    .section-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }

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

    .status-badge {
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-badge.dalam   { background: #fef2f2; color: #ef4444; }
    .status-badge.selesai { background: #e6f9f0; color: #10b981; }

    .btn-action-primary {
        background: #2b43b9;
        color: white;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-action-secondary {
        background: #f1f5f9;
        color: #334155;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Modal */
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

    @media (max-width: 768px) {
        .summary-grid { grid-template-columns: 1fr; }
        .filter-card { padding: 16px; }
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-input, .filter-select { width: 100%; }
        .form-row { grid-template-columns: 1fr; }
        .section-card { padding: 16px; border-radius: 14px; }
        .custom-modal-card { width: 100%; max-width: 100%; margin: 8px; }
    }
</style>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon red"><i class="fa-solid fa-id-badge"></i></div>
        <div>
            <div class="summary-val">{{ $tamuAktifCount }} Tamu</div>
            <div class="summary-label">Tamu Sedang Berada di Area Sekolah</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon blue"><i class="fa-solid fa-address-book"></i></div>
        <div>
            <div class="summary-val">{{ $totalTamuHariIni }} Kunjungan</div>
            <div class="summary-label">Total Tamu Tercatat Hari Ini</div>
        </div>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('satpam.buku-tamu') }}" class="filter-form">
        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Pilih Tanggal</span>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="filter-input">
        </div>

        <div style="flex: 1; min-width: 200px;">
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Cari Nama / Instansi / No. Polisi</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Ketik kata kunci..." class="filter-input" style="width: 100%;">
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Status</span>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Di Dalam" {{ $status === 'Di Dalam' ? 'selected' : '' }}>Di Dalam</option>
                <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div style="align-self: flex-end;">
            <button type="submit" class="btn-action-primary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="section-card">
    <div class="table-responsive-wrap">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Waktu Masuk & Keluar</th>
                    <th>Nama Tamu & Instansi</th>
                    <th>No. HP</th>
                    <th>Bertemu Dengan</th>
                    <th>Keperluan Kunjungan</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tamuList as $t)
                <tr>
                    <td>
                        <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($t->jam_masuk)->format('H:i') }} WIB</strong>
                        @if($t->jam_keluar)
                            <div style="font-size: 11px; color: #10b981;">Keluar: {{ Carbon\Carbon::parse($t->jam_keluar)->format('H:i') }}</div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #1b2559;">{{ $t->nama_tamu }}</strong>
                        <div style="font-size: 11.5px; color: #6b7a99;">{{ $t->instansi ?? 'Pribadi / Wali Murid' }}</div>
                    </td>
                    <td>
                        <span style="font-size: 12px; color: #334155;">{{ $t->no_hp ?? '-' }}</span>
                    </td>
                    <td>
                        <strong style="color: #2b43b9;">{{ $t->bertemu_dengan }}</strong>
                    </td>
                    <td style="max-width: 220px;">
                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $t->keperluan }}">
                            {{ $t->keperluan }}
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #334155;">{{ $t->no_kendaraan ?? '-' }}</span>
                    </td>
                    <td>
                        @if($t->status === 'Di Dalam')
                            <span class="status-badge dalam">Di Dalam</span>
                        @else
                            <span class="status-badge selesai">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($t->status === 'Di Dalam')
                            <form action="{{ route('satpam.buku-tamu.checkout', $t->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action-secondary" style="color: #ef4444; border-color: #fca5a5;">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Checkout
                                </button>
                            </form>
                        @else
                            <span style="color: #94a3b8; font-size: 12px;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #707e94; padding: 36px;">
                        <i class="fa-solid fa-address-book" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1;"></i>
                        <div>Belum ada data kunjungan tamu pada tanggal ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $tamuList->links() }}
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
