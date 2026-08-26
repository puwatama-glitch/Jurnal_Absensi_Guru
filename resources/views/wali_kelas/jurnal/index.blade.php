@extends('layouts.wali_kelas')

@section('title', 'Jurnal & Presensi Kelas Binaan - SMKN 1 BOYOLANGU')
@section('header_title', 'Jurnal & Presensi Kelas Binaan')
@section('header_subtitle', 'Pantau riwayat pengisian jurnal dan presensi harian siswa')

@section('header_extra')
    @if($kelas)
    <div class="header-kelas-pill">
        <i class="fa-solid fa-clipboard-check"></i>
        <div>
            <div class="header-kelas-title">Kelas Binaan</div>
            <div class="header-kelas-name">{{ $kelas->nama_kelas }} ({{ $jurnalList->count() }} Sesi)</div>
        </div>
    </div>
    @endif
@endsection

@section('styles')
<style>
    .page-action-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filter-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }

    .filter-input {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
    }

    .table-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
    }
    .table-hdr {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-title { font-size: 17px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .count-badge { font-size: 12px; font-weight: 700; color: #2b43b9; background: #eaeff8; padding: 5px 14px; border-radius: 20px; }

    .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .data-table th {
        font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.6px;
        padding: 12px 16px; text-align: left; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .data-table th:first-child { border-radius: 10px 0 0 10px; }
    .data-table th:last-child  { border-radius: 0 10px 10px 0; }
    .data-table td {
        padding: 16px; font-size: 13.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .data-table tbody tr:hover td { background: #f8fafc; }

    .badge-jam {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f1f5f9; color: #334155; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 8px;
    }
</style>
@endsection

@section('content')

@if(!$kelas)
    <div style="background:#ffffff;border-radius:24px;padding:60px 24px;text-align:center;border:1px solid #e2e8f0;">
        <i class="fa-solid fa-clipboard-question" style="font-size:56px;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
        <h3 style="font-size:20px;font-weight:800;color:#0f172a;margin-bottom:8px;">Belum Ada Kelas Binaan</h3>
        <p style="font-size:14px;color:#64748b;">Akun ini belum memiliki kelas binaan yang ditugaskan.</p>
    </div>
@else

    {{-- Filter Bar --}}
    <form action="{{ route('wali-kelas.jurnal') }}" method="GET">
    <div class="page-action-card">
        <div class="filter-group">
            <div class="filter-item">
                <label class="filter-label">Pilih Tanggal Pembelajaran</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="filter-input" onchange="this.form.submit()">
            </div>
            <div class="filter-item" style="align-self:flex-end;">
                <button type="submit" style="background:#2b43b9;color:#ffffff;border:none;border-radius:12px;padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;">
                    <i class="fa-solid fa-filter"></i> Tampilkan
                </button>
            </div>
        </div>

        @if(Auth::user()->role === 'admin' && count($allKelasList) > 1)
        <div>
            <label style="font-size:11px;font-weight:800;color:#64748b;display:block;margin-bottom:4px;text-transform:uppercase;">Pilih Kelas:</label>
            <select name="kelas_id" onchange="this.form.submit()" style="background:#f8fafc;border:1.5px solid #cbd5e1;border-radius:12px;padding:9px 14px;font-size:13px;font-weight:700;color:#0f172a;">
                @foreach($allKelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $k->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    </form>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-hdr">
            <div class="table-title">
                <i class="fa-solid fa-book-open"></i>
                <span>Riwayat Sesi Mengajar &amp; Presensi Siswa</span>
            </div>
            <span class="count-badge"><i class="fa-solid fa-calendar-check"></i> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, j F Y') }}</span>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Jam / Waktu</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                    <th>Materi yang Diajarkan</th>
                    <th>Kehadiran Siswa</th>
                    <th>Catatan Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnalList as $i => $j)
                <tr>
                    <td style="color:#94a3b8;font-weight:700;font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <span class="badge-jam">
                            <i class="fa-regular fa-clock"></i> {{ $j->jam_ke ?? 'Jam Pelajaran' }}
                        </span>
                        <div style="font-size:11px;color:#94a3b8;margin-top:3px;font-weight:600;">
                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:700;color:#0f172a;font-size:14px;">{{ $j->mapel->nama_mapel ?? '-' }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;">{{ $j->mapel->kode_mapel ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700;color:#0f172a;font-size:13.5px;">{{ $j->guru->nama_lengkap ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px;color:#334155;max-width:260px;line-height:1.4;">
                            {{ $j->materi ?? '— Belum diisi materi' }}
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <span style="font-size:12px;font-weight:800;color:#065f46;">
                                <i class="fa-solid fa-circle-check" style="color:#10b981;"></i> {{ $j->jumlah_siswa_hadir }} Hadir
                            </span>
                            @if($j->jumlah_siswa_tidak_hadir > 0)
                            <span style="font-size:11.5px;font-weight:700;color:#dc2626;">
                                <i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i> {{ $j->jumlah_siswa_tidak_hadir }} Tidak Hadir
                            </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px;color:#64748b;max-width:200px;font-style:italic;">
                            {{ $j->catatan ?? '— Tidak ada catatan' }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:54px 20px;color:#94a3b8;">
                        <i class="fa-solid fa-calendar-xmark" style="font-size:46px;margin-bottom:14px;color:#cbd5e1;display:block;"></i>
                        <p style="font-size:16px;font-weight:800;color:#0f172a;">Tidak ada data jurnal mengajar pada tanggal ini</p>
                        <p style="font-size:13px;margin-top:4px;color:#64748b;">Pilih tanggal lain untuk melihat riwayat jurnal.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endif

@endsection
