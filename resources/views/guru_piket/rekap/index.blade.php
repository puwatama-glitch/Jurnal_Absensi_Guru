@extends('layouts.guru_piket')

@section('title', 'Rekap Presensi Siswa - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Rekap Presensi Siswa Se-Sekolah')
@section('header_subtitle', 'Pantau ringkasan kehadiran siswa dari seluruh jurnal mengajar harian')

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

    /* Summary Pills Grid */
    .summary-pills-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    .summary-pill-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 14px;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
        text-align: center;
    }
    .pill-count { font-size: 22px; font-weight: 800; color: #1b2559; margin-top: 2px; }
    .pill-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #707e94; }

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
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-badge.hadir      { background: #e6f9f0; color: #10b981; }
    .status-badge.sakit      { background: #e0f2fe; color: #0369a1; }
    .status-badge.izin       { background: #fff7ed; color: #f97316; }
    .status-badge.alpha      { background: #fef2f2; color: #ef4444; }
    .status-badge.dispensasi { background: #eef2ff; color: #2b43b9; }

    @media (max-width: 900px) {
        .summary-pills-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 576px) {
        .summary-pills-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .filter-card { flex-direction: column; align-items: stretch; padding: 16px; }
        .filter-group { flex-direction: column; align-items: stretch; }
        .filter-select, .filter-input { width: 100%; }
        .section-card { padding: 16px; border-radius: 14px; }
    }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-piket.rekap') }}" class="filter-group">
        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal</span>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-input">
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Status Kehadiran</span>
            <select name="status" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Hadir" {{ $statusFilter === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="Sakit" {{ $statusFilter === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="Izin" {{ $statusFilter === 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Alpha" {{ $statusFilter === 'Alpha' ? 'selected' : '' }}>Alpha (Tanpa Keterangan)</option>
                <option value="Dispensasi" {{ $statusFilter === 'Dispensasi' ? 'selected' : '' }}>Dispensasi</option>
            </select>
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Kelas</span>
            <select name="id_kelas" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Kelas</option>
                @foreach($allKelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $kelasFilter == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div style="font-size: 13px; font-weight: 700; color: #2b43b9;">
        <i class="fa-solid fa-calendar-check"></i> {{ $todayFormatted }}
    </div>
</div>

<!-- Summary Pills -->
<div class="summary-pills-grid">
    <div class="summary-pill-card">
        <div class="pill-title">Total Presensi</div>
        <div class="pill-count" style="color: #1b2559;">{{ $summary['total'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Hadir</div>
        <div class="pill-count" style="color: #10b981;">{{ $summary['hadir'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Sakit</div>
        <div class="pill-count" style="color: #0284c7;">{{ $summary['sakit'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Izin</div>
        <div class="pill-count" style="color: #f97316;">{{ $summary['izin'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Alpha</div>
        <div class="pill-count" style="color: #ef4444;">{{ $summary['alpha'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Dispensasi</div>
        <div class="pill-count" style="color: #2b43b9;">{{ $summary['dispensasi'] }}</div>
    </div>
</div>

<!-- Table Card -->
<div class="section-card">
    <div class="table-responsive-wrap">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa & NISN</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran & Guru</th>
                    <th>Status Presensi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensiList as $idx => $p)
                <tr>
                    <td>{{ $presensiList->firstItem() + $idx }}</td>
                    <td>
                        <strong style="color: #1b2559; font-size: 13.5px;">{{ $p->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                        <div style="font-size: 11px; color: #6b7a99;">NISN: {{ $p->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td>
                        <strong style="color: #2b3674;">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</strong>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1b2559;">{{ $p->jurnal->mapel->nama_mapel ?? '-' }}</div>
                        <div style="font-size: 11.5px; color: #6b7a99;">Guru: {{ $p->jurnal->guru->nama_lengkap ?? '-' }}</div>
                    </td>
                    <td>
                        @php
                            $st = strtolower($p->status);
                        @endphp
                        <span class="status-badge {{ $st }}">{{ $p->status }}</span>
                    </td>
                    <td>
                        <span style="color: #6b7a99; font-size: 12px;">{{ $p->keterangan ?? '-' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7a99; padding: 36px;">
                        <i class="fa-solid fa-clipboard-user" style="font-size: 32px; margin-bottom: 8px;"></i>
                        <div>Tidak ada data presensi siswa yang sesuai pada filter ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $presensiList->links() }}
    </div>
</div>
@endsection
