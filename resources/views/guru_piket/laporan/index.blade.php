@extends('layouts.guru_piket')

@section('title', 'Laporan & Catatan Piket - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Laporan & Catatan Piket Harian')
@section('header_subtitle', 'Tulis ringkasan kejadian penting selama piket dan arsip laporan piket harian')

@section('styles')
<style>
    .two-cols-layout {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 24px;
    }

    .section-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b2559;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: #2b3674; margin-bottom: 6px; }
    .form-control {
        width: 100%;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        font-size: 13.5px;
        font-family: inherit;
        color: #1b2559;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .form-control:focus { border-color: #2b43b9; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .btn-submit {
        background: #2b43b9;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(43, 67, 185, 0.2);
        transition: all 0.2s ease;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(43, 67, 185, 0.3); }

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

    @media (max-width: 1024px) {
        .two-cols-layout { grid-template-columns: 1fr; }
    }
    @media (max-width: 576px) {
        .form-row { grid-template-columns: 1fr; }
        .section-card { padding: 16px; border-radius: 14px; }
        .btn-submit { width: 100%; justify-content: center; }
    }
</style>
@endsection

@section('content')
<div class="two-cols-layout">
    <!-- Left: Form Input / Edit Laporan Hari Ini -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fa-solid fa-file-pen" style="color: #2b43b9;"></i>
                <span>Catatan & Laporan Piket Harian ({{ $todayFormatted }})</span>
            </div>
            <a href="{{ route('guru-piket.laporan.cetak', ['tanggal' => $tanggal]) }}" target="_blank" class="btn-submit" style="padding: 8px 16px; font-size: 12.5px; background: white; color: #1b2559; box-shadow: none; border: 1.5px solid #e2e8f0;">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </a>
        </div>

        <form action="{{ route('guru-piket.laporan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Tanggal Piket</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" onchange="window.location.href='{{ route('guru-piket.laporan') }}?tanggal='+this.value">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Waktu Mulai Piket</label>
                    <input type="time" name="jam_mulai_piket" value="{{ $laporan->jam_mulai_piket ?? '06:45' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Waktu Selesai Piket</label>
                    <input type="time" name="jam_selesai_piket" value="{{ $laporan->jam_selesai_piket ?? '15:30' }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Uraian Kejadian & Catatan Khusus Piket</label>
                <textarea name="catatan_kejadian" rows="8" class="form-control" placeholder="Tuliskan catatan kejadian penting, misalnya:&#10;- Jam 06.45: Penertiban kedisiplinan dan atribut di gerbang utama.&#10;- Jam 08.30: Monitoring kelas kosong dan pemberian materi penugasan mandiri.&#10;- Jam 11.00: Penanganan siswa sakit di UKS.&#10;- Situasi KBM secara umum berjalan tertib dan lancar.">{{ $laporan->catatan_kejadian ?? '' }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Catatan Piket
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Riwayat Laporan Piket Sebelumnya -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fa-solid fa-clock-rotate-left" style="color: #2b43b9;"></i>
                <span>Arsip Laporan Piket Terakhir</span>
            </div>
        </div>

        <div class="table-responsive-wrap">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allRiwayatLaporan as $rl)
                    <tr>
                        <td>
                            <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($rl->tanggal)->translatedFormat('d M Y') }}</strong>
                            <div style="font-size: 11px; color: #6b7a99;">{{ Carbon\Carbon::parse($rl->tanggal)->translatedFormat('l') }}</div>
                        </td>
                        <td>
                            <div style="font-size: 12.5px; font-weight: 700; color: #2b3674;">{{ $rl->user->name ?? 'Guru Piket' }}</div>
                        </td>
                        <td>
                            <span style="background: #e6f9f0; color: #10b981; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
                                {{ $rl->status_piket ?? 'Selesai' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('guru-piket.laporan.cetak', ['tanggal' => $rl->tanggal->format('Y-m-d')]) }}" target="_blank" style="color: #2b43b9; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-print"></i> Cetak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7a99; padding: 32px;">
                            <i class="fa-solid fa-box-open" style="font-size: 28px; margin-bottom: 6px;"></i>
                            <div>Belum ada riwayat laporan piket tersimpan.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
