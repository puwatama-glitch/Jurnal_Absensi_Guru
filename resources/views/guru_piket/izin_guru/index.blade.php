@extends('layouts.guru_piket')

@section('title', 'Monitoring Izin Guru - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Monitoring Izin Guru & Kelas Terdampak')
@section('header_subtitle', 'Pantau guru yang berhalangan hadir dan kelola kebutuhan tugas mandiri / guru pengganti')

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
    .filter-input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 13px;
        font-family: inherit;
        font-weight: 600;
        color: #1b2559;
        outline: none;
    }
    .filter-input:focus { border-color: #2b43b9; }

    .two-cols-layout {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
    }

    .section-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b2559;
        display: flex;
        align-items: center;
        gap: 10px;
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
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .status-badge.warning { background: #fff7ed; color: #f97316; }
    .status-badge.danger  { background: #fef2f2; color: #ef4444; }
    .status-badge.success { background: #e6f9f0; color: #10b981; }

    @media (max-width: 1024px) {
        .two-cols-layout { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-piket.izin-guru') }}" style="display: flex; align-items: center; gap: 12px;">
        <span style="font-size: 13px; font-weight: 700; color: #707e94;">Pilih Tanggal:</span>
        <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-input">
    </form>

    <div style="font-size: 13.5px; font-weight: 700; color: #2b43b9;">
        <i class="fa-solid fa-calendar-day"></i> {{ $todayFormatted }}
    </div>
</div>

<div class="two-cols-layout">
    <!-- Left: Daftar Guru Izin Hari Ini -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fa-solid fa-user-xmark" style="color: #f97316;"></i>
                <span>Daftar Guru Izin / Sakit ({{ $izinGuruList->count() }})</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Jenis</th>
                        <th>Alasan & Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinGuruList as $iz)
                    <tr>
                        <td>
                            <strong style="color: #1b2559; font-size: 13.5px;">{{ $iz->guru->nama_lengkap ?? 'Guru' }}</strong>
                            <div style="font-size: 11.5px; color: #6b7a99;">
                                NIP: {{ $iz->guru->nip ?? '-' }} &bull; {{ $iz->guru->no_hp ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge warning">{{ $iz->jenis_izin }}</span>
                        </td>
                        <td>
                            <div>{{ $iz->alasan }}</div>
                            @if($iz->bukti_file)
                                <a href="{{ Storage::url($iz->bukti_file) }}" target="_blank" style="font-size: 11px; color: #2b43b9; font-weight: 700;">
                                    <i class="fa-solid fa-paperclip"></i> Surat Izin/Dokter
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #6b7a99; padding: 32px;">
                            <i class="fa-solid fa-circle-check" style="font-size: 28px; color: #10b981; margin-bottom: 6px;"></i>
                            <div>Tidak ada guru yang mengajukan izin pada tanggal ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Jadwal & Kelas Terdampak -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i>
                <span>Jadwal & Kelas Terdampak (Perlu Tindak Lanjut Piket)</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kelas & Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Berhalangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalTerdampak as $jt)
                    <tr>
                        <td>
                            <strong style="color: #1b2559; font-size: 13.5px;">{{ $jt->kelas->nama_kelas ?? '-' }}</strong>
                            <div style="font-size: 11.5px; color: #ef4444; font-weight: 700;">
                                <i class="fa-solid fa-clock"></i> Jam ke-{{ $jt->jam_ke }} ({{ Carbon\Carbon::parse($jt->jam_mulai)->format('H:i') }} - {{ Carbon\Carbon::parse($jt->jam_selesai)->format('H:i') }})
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #2b3674;">{{ $jt->mapel->nama_mapel ?? '-' }}</div>
                            <div style="font-size: 11px; color: #6b7a99;">Kode: {{ $jt->mapel->kode_mapel ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="color: #1b2559; font-weight: 700;">{{ $jt->guru->nama_lengkap ?? '-' }}</div>
                            <span class="status-badge danger">Butuh Tugas Mandiri</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #6b7a99; padding: 32px;">
                            <i class="fa-solid fa-circle-check" style="font-size: 28px; color: #10b981; margin-bottom: 6px;"></i>
                            <div>Tidak ada jadwal kelas yang kosong / terdampak pada hari ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
