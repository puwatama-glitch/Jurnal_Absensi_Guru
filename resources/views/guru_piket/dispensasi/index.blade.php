@extends('layouts.guru_piket')

@section('title', 'Dispensasi Siswa - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Manajemen Dispensasi Siswa')
@section('header_subtitle', 'Kelola izin keluar siswa, terbitkan surat dispensasi, dan pantau status kepulangan')

@section('header_extra')
    <button type="button" class="btn-action-primary" onclick="openDispensasiModal()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">
        <i class="fa-solid fa-plus"></i>
        <span>Terbitkan Dispensasi Baru</span>
    </button>
@endsection

@section('styles')
<style>
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .filter-select, .filter-input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 13px;
        font-family: inherit;
        font-weight: 600;
        color: #1b2559;
        outline: none;
    }
    .filter-select:focus, .filter-input:focus { border-color: #2b43b9; }

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
        padding: 14px;
        border-bottom: 1px solid #f4f7fe;
        color: #2b3674;
        vertical-align: middle;
    }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .status-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-badge.success { background: #e6f9f0; color: #10b981; }
    .status-badge.warning { background: #fff7ed; color: #f97316; }
    .status-badge.info    { background: #e0f2fe; color: #0369a1; }
    .status-badge.danger  { background: #fef2f2; color: #ef4444; }

    .action-btn-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    .btn-action.approve { background: #10b981; color: white; }
    .btn-action.return  { background: #0ea5e9; color: white; }
    .btn-action.print   { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
    .btn-action.print:hover { background: #e2e8f0; }

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
    }
    .btn-action-secondary {
        background: #f1f5f9;
        color: #334155;
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }

    /* Modal Styling */
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
        max-width: 540px;
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
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-piket.dispensasi') }}" class="filter-group">
        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal</span>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-input">
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Status</span>
            <select name="status" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Menunggu" {{ $status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Disetujui" {{ $status === 'Disetujui' ? 'selected' : '' }}>Disetujui / Keluar</option>
                <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai / Kembali</option>
                <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Cari Siswa</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Nama / NISN..." class="filter-input">
        </div>

        <div style="align-self: flex-end;">
            <button type="submit" class="btn-action-primary" style="padding: 8px 14px;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <div>
        <button type="button" class="btn-action-primary" onclick="openDispensasiModal()">
            <i class="fa-solid fa-plus"></i> + Tambah Dispensasi
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="section-card">
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa & Kelas</th>
                    <th>Waktu Izin</th>
                    <th>Alasan / Keperluan</th>
                    <th>Status</th>
                    <th>Diterbitkan Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensasiList as $idx => $d)
                <tr>
                    <td>{{ $dispensasiList->firstItem() + $idx }}</td>
                    <td>
                        <strong style="color: #1b2559; font-size: 13.5px;">{{ $d->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                        <div style="font-size: 11.5px; color: #6b7a99;">
                            {{ $d->siswa->kelas->nama_kelas ?? '-' }} &bull; NISN: {{ $d->siswa->nisn ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1b2559;">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="color: #ef4444; font-size: 11px;"></i> {{ Carbon\Carbon::parse($d->jam_keluar)->format('H:i') }}
                        </div>
                        <div style="font-size: 11.5px; color: #6b7a99;">
                            @if($d->jam_kembali)
                                <i class="fa-solid fa-arrow-right-to-bracket" style="color: #10b981; font-size: 11px;"></i> Kembali: {{ Carbon\Carbon::parse($d->jam_kembali)->format('H:i') }}
                            @else
                                <span style="color: #f97316;">Belum Kembali</span>
                            @endif
                        </div>
                    </td>
                    <td style="max-width: 200px;">
                        <div>{{ $d->alasan }}</div>
                        @if($d->bukti_file)
                            <a href="{{ Storage::url($d->bukti_file) }}" target="_blank" style="font-size: 11px; color: #2b43b9; font-weight: 700;">
                                <i class="fa-solid fa-paperclip"></i> Lihat Lampiran
                            </a>
                        @endif
                    </td>
                    <td>
                        @if($d->status === 'Menunggu')
                            <span class="status-badge warning">Menunggu</span>
                        @elseif($d->status === 'Disetujui' && !$d->jam_kembali)
                            <span class="status-badge info">Sedang Keluar</span>
                        @elseif($d->status === 'Selesai' || ($d->status === 'Disetujui' && $d->jam_kembali))
                            <span class="status-badge success">Kembali / Selesai</span>
                        @else
                            <span class="status-badge danger">{{ $d->status }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 12px; color: #2b3674;">{{ $d->diinputOlehUser->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #94a3b8;">{{ $d->created_at->format('H:i, d M') }}</div>
                    </td>
                    <td>
                        <div class="action-btn-wrap">
                            @if($d->status === 'Menunggu')
                                <form action="{{ route('guru-piket.dispensasi.status', $d->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="setujui">
                                    <button type="submit" class="btn-action approve" title="Setujui Izin">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                </form>
                            @elseif($d->status === 'Disetujui' && !$d->jam_kembali)
                                <form action="{{ route('guru-piket.dispensasi.status', $d->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="kembali">
                                    <button type="submit" class="btn-action return" title="Konfirmasi Siswa Kembali">
                                        <i class="fa-solid fa-rotate-left"></i> Kembali
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('guru-piket.dispensasi.cetak', $d->id) }}" target="_blank" class="btn-action print" title="Cetak Surat Izin Satpam">
                                <i class="fa-solid fa-print"></i> Cetak Slip
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #6b7a99; padding: 36px;">
                        <i class="fa-solid fa-ticket-simple" style="font-size: 32px; margin-bottom: 8px;"></i>
                        <div>Belum ada data dispensasi pada tanggal atau filter ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $dispensasiList->links() }}
    </div>
</div>

<!-- Modal Quick Dispensasi Siswa -->
<div class="custom-modal-backdrop" id="dispensasiModal">
    <div class="custom-modal-card">
        <div class="modal-head">
            <h3><i class="fa-solid fa-ticket-simple" style="color: #2b43b9;"></i> Terbitkan Surat Dispensasi Siswa</h3>
            <button type="button" class="modal-close-btn" onclick="closeDispensasiModal()">&times;</button>
        </div>
        <form action="{{ route('guru-piket.dispensasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih Siswa <span style="color:#ef4444;">*</span></label>
                    <select name="id_siswa" class="form-control" required>
                        <option value="">-- Cari & Pilih Siswa --</option>
                        @foreach($siswaSelectOption as $s)
                            <option value="{{ $s->id_siswa }}">{{ $s->nama_lengkap }} ({{ $s->kelas->nama_kelas ?? '-' }}) - NISN: {{ $s->nisn }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Dispensasi <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jam Keluar <span style="color:#ef4444;">*</span></label>
                        <input type="time" name="jam_keluar" value="{{ now()->format('H:i') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Perkiraan Jam Kembali</label>
                        <input type="time" name="jam_kembali" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alasan / Keperluan Keluar <span style="color:#ef4444;">*</span></label>
                    <textarea name="alasan" rows="3" class="form-control" placeholder="Contoh: Mengikuti perwakilan lomba / Izin ke Puskesmas..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Bukti Surat / Foto (Opsional)</label>
                    <input type="file" name="bukti_file" class="form-control" accept="image/*,application/pdf">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action-secondary" onclick="closeDispensasiModal()">Batal</button>
                <button type="submit" class="btn-action-primary">Terbitkan Surat</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openDispensasiModal() {
        document.getElementById('dispensasiModal').classList.add('show');
    }
    function closeDispensasiModal() {
        document.getElementById('dispensasiModal').classList.remove('show');
    }
</script>
@endsection
