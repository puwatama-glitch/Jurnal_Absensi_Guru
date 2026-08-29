@extends('layouts.satpam')

@section('title', 'Monitoring Gerbang - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Live Monitoring Gerbang Sekolah')
@section('header_subtitle', 'Pemantauan real-time siswa yang sedang berada di luar lingkungan sekolah')

@section('header_extra')
    <button type="button" onclick="window.location.reload()" class="btn-action-secondary" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; box-shadow: none;">
        <i class="fa-solid fa-rotate"></i> Refresh Data
    </button>
@endsection

@section('styles')
<style>
    .status-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .tab-btn {
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
        white-space: nowrap;
    }
    .tab-btn:hover { border-color: #2b43b9; color: #2b43b9; background: #f8fafc; }
    .tab-btn.active {
        background: #2b43b9;
        color: white;
        border-color: #2b43b9;
        box-shadow: 0 4px 14px rgba(43, 67, 185, 0.25);
    }
    .tab-badge {
        background: rgba(0, 0, 0, 0.08);
        padding: 2px 7px;
        border-radius: 20px;
        font-size: 11px;
    }
    .tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .section-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
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

    .btn-action-sm {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-action-sm.success { background: #10b981; color: white; }
    .btn-action-sm.success:hover { background: #059669; }
</style>
@endsection

@section('content')
<!-- Filter Status Tabs -->
<div class="status-tabs">
    <a href="{{ route('satpam.monitoring', ['status' => 'di_luar']) }}" class="tab-btn {{ $statusFilter === 'di_luar' ? 'active' : '' }}">
        <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
        <span>Sedang di Luar</span>
        <span class="tab-badge">{{ $counts['di_luar'] }}</span>
    </a>
    <a href="{{ route('satpam.monitoring', ['status' => 'selesai']) }}" class="tab-btn {{ $statusFilter === 'selesai' ? 'active' : '' }}">
        <i class="fa-solid fa-rotate-left"></i>
        <span>Sudah Kembali</span>
        <span class="tab-badge">{{ $counts['selesai'] }}</span>
    </a>
    <a href="{{ route('satpam.monitoring', ['status' => 'semua']) }}" class="tab-btn {{ $statusFilter === 'semua' ? 'active' : '' }}">
        <i class="fa-solid fa-list-check"></i>
        <span>Semua Hari Ini</span>
        <span class="tab-badge">{{ $counts['semua'] }}</span>
    </a>
</div>

<!-- Table Card -->
<div class="section-card">
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Waktu Keluar</th>
                    <th>Nama Siswa & NISN</th>
                    <th>Kelas & Jurusan</th>
                    <th>Alasan / Keperluan</th>
                    <th>Status Gerbang</th>
                    <th>Aksi Satpam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensasiList as $d)
                <tr>
                    <td>
                        <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($d->jam_keluar)->format('H:i') }} WIB</strong>
                        @if($d->jam_kembali)
                            <div style="font-size: 11px; color: #10b981;">Kembali: {{ Carbon\Carbon::parse($d->jam_kembali)->format('H:i') }} WIB</div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #1b2559;">{{ $d->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                        <div style="font-size: 11px; color: #707e94;">NISN: {{ $d->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td>
                        <strong style="color: #2b3674;">{{ $d->siswa->kelas->nama_kelas ?? '-' }}</strong>
                    </td>
                    <td style="max-width: 220px;">
                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $d->alasan }}">
                            {{ $d->alasan }}
                        </div>
                    </td>
                    <td>
                        @if(empty($d->jam_kembali) && $d->status === 'Disetujui')
                            <span class="status-pill warning">Sedang di Luar</span>
                        @elseif(!empty($d->jam_kembali) || $d->status === 'Selesai')
                            <span class="status-pill success">Sudah Kembali</span>
                        @else
                            <span class="status-pill info">{{ $d->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if(empty($d->jam_kembali) && $d->status === 'Disetujui')
                            <form action="{{ route('satpam.dispensasi.status', $d->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="kembali">
                                <button type="submit" class="btn-action-sm success">
                                    <i class="fa-solid fa-check"></i> Konfirmasi Kembali
                                </button>
                            </form>
                        @else
                            <span style="color: #94a3b8; font-size: 12px;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #707e94; padding: 40px;">
                        <i class="fa-solid fa-clipboard-check" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1;"></i>
                        <div>Tidak ada data pergerakan siswa pada status ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
