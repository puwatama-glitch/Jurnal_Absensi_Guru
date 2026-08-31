@extends('layouts.guru_piket')

@section('title', 'Monitoring Kelas - Jurnal Absensi SMKN 1 BOYOLANGU')
@section('header_title', 'Monitoring Kelas Real-Time')
@section('header_subtitle', 'Pantau kelancaran pembelajaran dan status pengisian jurnal di seluruh rombel')

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
    
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }
    .room-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef2f7;
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }
    .room-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .room-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .room-title { font-size: 16px; font-weight: 800; color: #1b2559; }
    .room-sub { font-size: 12px; color: #6b7a99; font-weight: 600; }
    
    .status-pill {
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .status-pill.success { background: #e6f9f0; color: #10b981; }
    .status-pill.warning { background: #fff7ed; color: #f97316; }
    .status-pill.danger  { background: #fef2f2; color: #ef4444; }
    .status-pill.secondary { background: #f1f5f9; color: #64748b; }
    .status-pill.light   { background: #ffffff; color: #94a3b8; border: 1px solid #e2e8f0; }

    .room-info-block {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        font-size: 12.5px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #2b3674;
    }
    .info-item i { width: 16px; color: #2b43b9; font-size: 12px; }

    @media (max-width: 768px) {
        .filter-card { flex-direction: column; align-items: stretch; padding: 16px; }
        .filter-group { flex-direction: column; align-items: stretch; }
        .filter-select, .filter-input { width: 100%; }
        .grid-container { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('guru-piket.monitoring') }}" class="filter-group">
        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tanggal Pantauan</span>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-input">
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Tingkat</span>
            <select name="tingkat" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Tingkat</option>
                <option value="X" {{ $tingkatFilter === 'X' ? 'selected' : '' }}>Kelas X</option>
                <option value="XI" {{ $tingkatFilter === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                <option value="XII" {{ $tingkatFilter === 'XII' ? 'selected' : '' }}>Kelas XII</option>
            </select>
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Jurusan</span>
            <select name="jurusan" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Jurusan</option>
                @foreach($allJurusanList as $jur)
                    <option value="{{ $jur }}" {{ $jurusanFilter === $jur ? 'selected' : '' }}>{{ $jur }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <span style="font-size: 11.5px; font-weight: 800; color: #707e94; text-transform: uppercase; display: block; margin-bottom: 4px;">Status Kelas</span>
            <select name="status" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Sedang Mengajar" {{ $statusFilter === 'Sedang Mengajar' ? 'selected' : '' }}>Sedang Mengajar</option>
                <option value="Belum Ada Jurnal" {{ $statusFilter === 'Belum Ada Jurnal' ? 'selected' : '' }}>Belum Ada Jurnal</option>
                <option value="Guru Izin" {{ $statusFilter === 'Guru Izin' ? 'selected' : '' }}>Guru Izin / Kosong</option>
            </select>
        </div>
    </form>

    <div style="font-size: 13px; font-weight: 700; color: #2b43b9;">
        <i class="fa-solid fa-calendar-check"></i> {{ $todayFormatted }}
    </div>
</div>

<!-- Class Cards Grid -->
<div class="grid-container">
    @forelse($kelasMonitoring as $item)
    <div class="room-card">
        <div>
            <div class="room-card-head">
                <div>
                    <div class="room-title">{{ $item->kelas->nama_kelas }}</div>
                    <div class="room-sub">{{ $item->kelas->jurusan ?? 'Semua Jurusan' }} &bull; Tingkat {{ $item->kelas->tingkat }}</div>
                </div>
                <span class="status-pill {{ $item->badge }}">{{ $item->status }}</span>
            </div>

            <div class="room-info-block">
                <div class="info-item">
                    <i class="fa-solid fa-user-tie"></i>
                    <span><strong>Guru:</strong> {{ $item->guru }}</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-book"></i>
                    <span><strong>Mapel:</strong> {{ $item->mapel }}</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-clock"></i>
                    <span><strong>Waktu:</strong> {{ $item->jam }}</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span><strong>Wali Kelas:</strong> {{ $item->kelas->waliKelas->nama_lengkap ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #6b7a99;">
            <span>Jurnal Terisi: <strong style="color: #1b2559;">{{ $item->total_jurnal }}</strong> sesi</span>
            <span>Jadwal: <strong style="color: #1b2559;">{{ $item->total_jadwal }}</strong> sesi</span>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: white; border-radius: 16px; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03); border: 1px solid #eef2f7;">
        <i class="fa-solid fa-chalkboard" style="font-size: 42px; color: #cbd5e1; margin-bottom: 12px;"></i>
        <h4 style="color: #1b2559; margin-bottom: 4px;">Tidak Ada Data Kelas Ditemukan</h4>
        <p style="color: #6b7a99; font-size: 13px;">Silakan periksa filter pencarian atau tanggal jadwal pelajaran.</p>
    </div>
    @endforelse
</div>
@endsection
