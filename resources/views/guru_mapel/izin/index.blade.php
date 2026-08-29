@extends('layouts.guru_mapel')

@section('title', 'Pengajuan Izin Mengajar - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Pengajuan Izin Tidak Mengajar')
@section('header_subtitle', 'Ajukan izin berhalangan hadir mengajar dan pantau persetujuan dari pihak sekolah')

@section('styles')
<style>
    .two-cols-layout {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 24px;
    }

    .form-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }

    .card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-head h3 {
        font-size: 16px;
        font-weight: 800;
        color: #1b2559;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: #2b3674;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        font-size: 13.5px;
        font-family: inherit;
        color: #1b2559;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .form-control:focus {
        border-color: #2b43b9;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .btn-submit {
        background: #2b43b9;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(43, 67, 185, 0.25);
        transition: all 0.2s ease;
    }
    .btn-submit:hover {
        background: #1e35a0;
        transform: translateY(-2px);
    }

    /* Table */
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
    .status-badge.menunggu  { background: #fff7ed; color: #f97316; }
    .status-badge.disetujui { background: #e6f9f0; color: #10b981; }
    .status-badge.ditolak   { background: #fef2f2; color: #ef4444; }

    @media (max-width: 1024px) {
        .two-cols-layout { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="two-cols-layout">
    <!-- Left: Form Pengajuan Izin -->
    <div class="form-card">
        <div class="card-head">
            <i class="fa-solid fa-file-signature" style="color: #2b43b9; font-size: 18px;"></i>
            <h3>Formulir Izin Tidak Mengajar</h3>
        </div>

        <form action="{{ route('guru-mapel.izin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Mulai <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ $today }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ $today }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Jenis Izin <span style="color:#ef4444;">*</span></label>
                <select name="jenis_izin" class="form-control" required>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin" selected>Izin Urusan Pribadi / Keluarga</option>
                    <option value="Dinas_Luar">Tugas Dinas Luar Sekolah</option>
                    <option value="Cuti">Cuti Resmi</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label>Alasan & Titipan Tugas Siswa <span style="color:#ef4444;">*</span></label>
                <textarea name="alasan" rows="5" class="form-control" placeholder="Tuliskan alasan izin serta instruksi materi/tugas mandiri untuk kelas yang ditinggalkan..." required></textarea>
            </div>

            <div class="form-group">
                <label>Lampiran Bukti (Surat Dokter / Tugas - Opsional)</label>
                <input type="file" name="bukti_file" class="form-control" accept="image/*,application/pdf">
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Izin
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Riwayat Izin -->
    <div class="form-card">
        <div class="card-head">
            <i class="fa-solid fa-clock-rotate-left" style="color: #2b43b9; font-size: 18px;"></i>
            <h3>Riwayat Pengajuan Izin Saya</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal Izin</th>
                        <th>Jenis</th>
                        <th>Alasan / Tugas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izinList as $iz)
                    <tr>
                        <td>
                            <strong style="color: #1b2559;">{{ Carbon\Carbon::parse($iz->tanggal_mulai)->translatedFormat('d M Y') }}</strong>
                            @if($iz->tanggal_mulai != $iz->tanggal_selesai)
                                <div style="font-size: 11px; color: #707e94;">s/d {{ Carbon\Carbon::parse($iz->tanggal_selesai)->translatedFormat('d M Y') }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge" style="background: #f1f5f9; color: #334155;">{{ str_replace('_', ' ', $iz->jenis_izin) }}</span>
                        </td>
                        <td style="max-width: 180px;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $iz->alasan }}">
                                {{ $iz->alasan }}
                            </div>
                            @if($iz->bukti_file)
                                <a href="{{ Storage::url($iz->bukti_file) }}" target="_blank" style="font-size: 11px; color: #2b43b9; font-weight: 700;">
                                    <i class="fa-solid fa-paperclip"></i> Lihat Bukti
                                </a>
                            @endif
                        </td>
                        <td>
                            @php $st = strtolower($iz->status); @endphp
                            <span class="status-badge {{ $st }}">{{ $iz->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #707e94; padding: 36px;">
                            <i class="fa-solid fa-calendar-check" style="font-size: 32px; margin-bottom: 8px; color: #cbd5e1;"></i>
                            <div>Belum ada riwayat pengajuan izin yang tercatat.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $izinList->links() }}
        </div>
    </div>
</div>
@endsection
