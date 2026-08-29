@extends('layouts.satpam')

@section('title', 'Riwayat Log Gerbang - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Riwayat & Log Lalu Lintas Pos Gerbang')
@section('header_subtitle', 'Arsip catatan keluar-masuk siswa dan kunjungan tamu sekolah')

@section('header_extra')
    <a href="{{ route('satpam.riwayat.cetak', ['tanggal' => $tanggal]) }}" target="_blank" class="btn-action-primary" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">
        <i class="fa-solid fa-print"></i> Cetak Laporan Pos Jaga
    </a>
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
    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
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

    .category-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .cat-tab-btn {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 700;
        color: #6b7a99;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .cat-tab-btn:hover { border-color: #2b43b9; color: #2b43b9; }
    .cat-tab-btn.active {
        background: #2b43b9;
        color: white;
        border-color: #2b43b9;
        box-shadow: 0 4px 14px rgba(43, 67, 185, 0.25);
    }

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
    .status-badge.selesai { background: #e6f9f0; color: #10b981; }
    .status-badge.proses  { background: #fff7ed; color: #f97316; }

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
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('satpam.riwayat') }}" class="filter-form">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div>
            <span style="font-size: 11px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Pilih Tanggal Log</span>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-input">
        </div>
        <div style="align-self: flex-end;">
            <button type="submit" class="btn-action-primary">
                <i class="fa-solid fa-filter"></i> Tampilkan
            </button>
        </div>
    </form>

    <div style="font-size: 13.5px; font-weight: 700; color: #1b2559;">
        <i class="fa-solid fa-calendar-day" style="color: #2b43b9;"></i> {{ $todayFormatted }}
    </div>
</div>

<!-- Tabs Siswa vs Tamu -->
<div class="category-tabs">
    <a href="{{ route('satpam.riwayat', ['tanggal' => $tanggal, 'tab' => 'siswa']) }}" class="cat-tab-btn {{ $tab === 'siswa' ? 'active' : '' }}">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>Dispensasi Siswa Keluar ({{ $dispensasiList->count() }})</span>
    </a>
    <a href="{{ route('satpam.riwayat', ['tanggal' => $tanggal, 'tab' => 'tamu']) }}" class="cat-tab-btn {{ $tab === 'tamu' ? 'active' : '' }}">
        <i class="fa-solid fa-address-book"></i>
        <span>Buku Tamu Kunjungan ({{ $tamuList->count() }})</span>
    </a>
</div>

<!-- Table Card -->
<div class="section-card">
    @if($tab === 'siswa')
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Jam Keluar</th>
                        <th>Jam Kembali</th>
                        <th>Nama Siswa & NISN</th>
                        <th>Kelas</th>
                        <th>Alasan Keluar</th>
                        <th>Penerbit Izin</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispensasiList as $d)
                    <tr>
                        <td><strong style="color: #1b2559;">{{ Carbon\Carbon::parse($d->jam_keluar)->format('H:i') }} WIB</strong></td>
                        <td>
                            @if($d->jam_kembali)
                                <strong style="color: #10b981;">{{ Carbon\Carbon::parse($d->jam_kembali)->format('H:i') }} WIB</strong>
                            @else
                                <span style="color: #ef4444; font-weight: 700;">Belum Kembali</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #1b2559;">{{ $d->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                            <div style="font-size: 11px; color: #707e94;">NISN: {{ $d->siswa->nisn ?? '-' }}</div>
                        </td>
                        <td><strong style="color: #2b3674;">{{ $d->siswa->kelas->nama_kelas ?? '-' }}</strong></td>
                        <td>{{ $d->alasan }}</td>
                        <td>{{ $d->diinputOlehUser->name ?? 'Guru Piket' }}</td>
                        <td>
                            @if($d->jam_kembali || $d->status === 'Selesai')
                                <span class="status-badge selesai">Selesai Kembali</span>
                            @else
                                <span class="status-badge proses">Di Luar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #707e94; padding: 36px;">
                            Tidak ada data pergerakan siswa pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Nama Tamu & Instansi</th>
                        <th>Keperluan Kunjungan</th>
                        <th>Bertemu</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tamuList as $t)
                    <tr>
                        <td><strong style="color: #1b2559;">{{ Carbon\Carbon::parse($t->jam_masuk)->format('H:i') }} WIB</strong></td>
                        <td>
                            @if($t->jam_keluar)
                                <strong style="color: #10b981;">{{ Carbon\Carbon::parse($t->jam_keluar)->format('H:i') }} WIB</strong>
                            @else
                                <span style="color: #ef4444; font-weight: 700;">Masih di Dalam</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #1b2559;">{{ $t->nama_tamu }}</strong>
                            <div style="font-size: 11.5px; color: #6b7a99;">{{ $t->instansi ?? 'Wali Murid / Pribadi' }}</div>
                        </td>
                        <td>{{ $t->keperluan }}</td>
                        <td><strong style="color: #2b43b9;">{{ $t->bertemu_dengan }}</strong></td>
                        <td>{{ $t->no_kendaraan ?? '-' }}</td>
                        <td>
                            @if($t->status === 'Di Dalam')
                                <span class="status-badge proses">Di Dalam</span>
                            @else
                                <span class="status-badge selesai">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #707e94; padding: 36px;">
                            Tidak ada data kunjungan tamu pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
