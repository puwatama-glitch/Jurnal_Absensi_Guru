@extends('layouts.guru_mapel')

@section('title', 'Rekap Presensi Siswa - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Rekap Presensi Siswa per Mata Pelajaran')
@section('header_subtitle', 'Pantau akumulasi kehadiran siswa di kelas yang Anda ampu')

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
    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .filter-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 13px;
        font-family: inherit;
        font-weight: 600;
        color: #1b2559;
        outline: none;
    }
    .filter-select:focus { border-color: #2b43b9; }

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

    .progress-bar-wrap {
        width: 100px;
        height: 6px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 4px;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-mapel.rekap') }}" class="filter-form">
        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Pilih Kelas</span>
            <select name="id_kelas" onchange="this.form.submit()" class="filter-select">
                @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->jurusan ?? 'Umum' }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Pilih Mata Pelajaran</span>
            <select name="id_mapel" onchange="this.form.submit()" class="filter-select">
                @foreach($mapelList as $m)
                    <option value="{{ $m->id_mapel }}" {{ $idMapel == $m->id_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div style="font-size: 13px; font-weight: 700; color: #2b43b9;">
        <i class="fa-solid fa-graduation-cap"></i> {{ $selectedKelas->nama_kelas ?? '-' }} &bull; {{ $selectedMapel->nama_mapel ?? '-' }}
    </div>
</div>

<!-- Table Card -->
<div class="section-card">
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Siswa & NISN</th>
                    <th style="text-align: center;">Total Sesi</th>
                    <th style="text-align: center; color: #10b981;">Hadir</th>
                    <th style="text-align: center; color: #0369a1;">Sakit</th>
                    <th style="text-align: center; color: #f97316;">Izin</th>
                    <th style="text-align: center; color: #ef4444;">Alpha</th>
                    <th style="text-align: center; color: #2b43b9;">Dispensasi</th>
                    <th>Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaRekap as $idx => $r)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong style="color: #1b2559;">{{ $r->siswa->nama_lengkap }}</strong>
                        <div style="font-size: 11px; color: #707e94;">NISN: {{ $r->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $r->total_pertemuan }}</td>
                    <td style="text-align: center; font-weight: 800; color: #10b981;">{{ $r->hadir }}</td>
                    <td style="text-align: center; font-weight: 700; color: #0369a1;">{{ $r->sakit }}</td>
                    <td style="text-align: center; font-weight: 700; color: #f97316;">{{ $r->izin }}</td>
                    <td style="text-align: center; font-weight: 700; color: #ef4444;">{{ $r->alpha }}</td>
                    <td style="text-align: center; font-weight: 700; color: #2b43b9;">{{ $r->dispensasi }}</td>
                    <td>
                        <div style="font-weight: 800; color: {{ $r->persentase >= 75 ? '#10b981' : '#ef4444' }}; font-size: 13px;">
                            {{ $r->persentase }}%
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill" style="width: {{ $r->persentase }}%; background: {{ $r->persentase >= 75 ? '#10b981' : '#ef4444' }};"></div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #707e94; padding: 36px;">
                        <i class="fa-solid fa-chart-simple" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1;"></i>
                        <div>Belum ada data rekap presensi untuk kelas dan mata pelajaran ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
