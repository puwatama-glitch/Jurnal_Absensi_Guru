@extends('layouts.guru_mapel')

@section('title', 'Riwayat Jurnal Mengajar - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Riwayat Jurnal Mengajar')
@section('header_subtitle', 'Arsip lengkap catatan pembelajaran dan presensi siswa yang telah Anda laksanakan')

@section('header_extra')
    <a href="{{ route('guru-mapel.jurnal.create') }}" class="btn-action-primary" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">
        <i class="fa-solid fa-plus"></i> + Tambah Jurnal Baru
    </a>
@endsection

@section('styles')
<style>
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

    /* Summary Pills Grid */
    .summary-pills-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
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

    /* Table Card */
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
    .status-badge.hadir      { background: #e6f9f0; color: #10b981; }
    .status-badge.sakit      { background: #e0f2fe; color: #0369a1; }
    .status-badge.izin       { background: #fff7ed; color: #f97316; }
    .status-badge.alpha      { background: #fef2f2; color: #ef4444; }

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
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    .btn-action-secondary:hover { background: #e2e8f0; }

    @media (max-width: 900px) {
        .summary-pills-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 576px) {
        .summary-pills-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-input, .filter-select { width: 100%; }
        .btn-action-primary { width: 100%; justify-content: center; }
        .section-card { padding: 16px; border-radius: 14px; }
    }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-mapel.jurnal.riwayat') }}" class="filter-form">
        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal Mulai</span>
            <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" class="filter-input">
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal Selesai</span>
            <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}" class="filter-input">
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Kelas</span>
            <select name="id_kelas" class="filter-select">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $kelasFilter == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Mata Pelajaran</span>
            <select name="id_mapel" class="filter-select">
                <option value="">Semua Mapel</option>
                @foreach($mapelList as $m)
                    <option value="{{ $m->id_mapel }}" {{ $mapelFilter == $m->id_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Cari Materi</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Kata kunci materi..." class="filter-input">
        </div>

        <div style="align-self: flex-end;">
            <button type="submit" class="btn-action-primary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Summary Pills -->
<div class="summary-pills-grid">
    <div class="summary-pill-card">
        <div class="pill-title">Total Pertemuan</div>
        <div class="pill-count" style="color: #2b43b9;">{{ $summary['total_sesi'] }}</div>
    </div>
    <div class="summary-pill-card">
        <div class="pill-title">Presensi Hadir</div>
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
</div>

<!-- Table Card -->
<div class="section-card">
    <div class="table-responsive-wrap">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal & Jam</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Materi Pokok Pembelajaran</th>
                    <th>Kehadiran Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnalList as $j)
                <tr>
                    <td>
                        <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</strong>
                        <div style="font-size: 11.5px; color: #707e94;">Jam ke-{{ $j->jam_ke }} ({{ $j->jam_mulai ? Carbon\Carbon::parse($j->jam_mulai)->format('H:i') : '-' }})</div>
                    </td>
                    <td>
                        <strong style="color: #2b3674; font-size: 13.5px;">{{ $j->kelas->nama_kelas ?? '-' }}</strong>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #1b2559;">{{ $j->mapel->nama_mapel ?? '-' }}</div>
                    </td>
                    <td style="max-width: 240px;">
                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $j->materi }}">
                            {{ $j->materi }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge hadir">{{ $j->jumlah_siswa_hadir }} Hadir</span>
                        @if($j->jumlah_siswa_tidak_hadir > 0)
                            <span class="status-badge alpha">{{ $j->jumlah_siswa_tidak_hadir }} Absen</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('guru-mapel.jurnal.show', $j->id_jurnal) }}" class="btn-action-secondary">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #707e94; padding: 36px;">
                        <i class="fa-solid fa-book-bookmark" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1;"></i>
                        <div>Belum ada data jurnal mengajar yang sesuai filter.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $jurnalList->links() }}
    </div>
</div>
@endsection
