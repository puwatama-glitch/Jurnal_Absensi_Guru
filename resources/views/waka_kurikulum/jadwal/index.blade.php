@extends('layouts.waka_kurikulum')
@section('title', 'Jadwal Pelajaran - Waka Kurikulum')
@section('header_title', 'Jadwal Pelajaran')
@section('header_subtitle', 'Monitoring jadwal pelajaran seluruh rombongan belajar')
@section('styles')
<style>
    .filter-bar { background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 18px 22px; margin-bottom: 22px; display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap; }
    .filter-item { display: flex; flex-direction: column; gap: 6px; }
    .filter-label { font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; }
    .filter-input, .filter-select { padding: 9px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 13.5px; font-family: inherit; min-width: 140px; }
    .filter-input:focus, .filter-select:focus { outline: none; border-color: #0ea5e9; }
    .btn-filter { padding: 9px 18px; background: #0ea5e9; color: white; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .hari-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
    .hari-tab { padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; background: #f1f5f9; color: #64748b; border: 1.5px solid #e2e8f0; transition: all 0.15s; }
    .hari-tab.active { background: #0ea5e9; color: white; border-color: #0ea5e9; }
    .hari-tab:hover:not(.active) { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .table-card { background: white; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; }
    .table-hdr { padding: 18px 22px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
    .table-title { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .table-title i { color: #0ea5e9; }
    .count-badge { font-size: 12px; background: #e0f2fe; color: #0369a1; padding: 2px 10px; border-radius: 8px; font-weight: 700; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #f1f5f9; }
    .data-table td { padding: 13px 16px; font-size: 13.5px; border-bottom: 1px solid #f8fafc; color: #334155; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #f8fafc; }
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 42px; margin-bottom: 12px; }
</style>
@endsection

@section('content')
<div>
    {{-- Hari Filter Tabs --}}
    <div class="hari-tabs">
        @foreach(['Semua', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
            <a href="{{ route('waka-kurikulum.jadwal', array_merge(request()->except('hari'), ['hari' => $h])) }}"
               class="hari-tab {{ $hariFilter === $h ? 'active' : '' }}">{{ $h }}</a>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form action="{{ route('waka-kurikulum.jadwal') }}" method="GET" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%;">
            <input type="hidden" name="hari" value="{{ $hariFilter }}">
            <div class="filter-item">
                <span class="filter-label">Tingkat</span>
                <select name="tingkat" class="filter-select">
                    <option value="">Semua Tingkat</option>
                    @foreach(['X', 'XI', 'XII'] as $tk)
                        <option value="{{ $tk }}" {{ $tingkat === $tk ? 'selected' : '' }}>Tingkat {{ $tk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item">
                <span class="filter-label">Kelas</span>
                <select name="id_kelas" class="filter-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-item">
                <span class="filter-label">Jurusan</span>
                <select name="id_jurusan" class="filter-select">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusanList as $jur)
                        <option value="{{ $jur->id_jurusan }}" {{ $idJurusan == $jur->id_jurusan ? 'selected' : '' }}>{{ $jur->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-hdr">
            <div class="table-title">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Jadwal Pelajaran — {{ $hariFilter === 'Semua' ? 'Semua Hari' : $hariFilter }}</span>
                <span class="count-badge">{{ $jadwalList->count() }} Entri</span>
            </div>
        </div>
        @if($jadwalList->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-calendar-xmark"></i>
                <p>Tidak ada jadwal ditemukan untuk filter yang dipilih.</p>
            </div>
        @else
            <div class="table-responsive-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalList as $i => $jp)
                        <tr>
                            <td style="color: #94a3b8; font-weight: 700;">{{ $i + 1 }}</td>
                            <td>
                                <span style="font-size: 12.5px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 8px;">{{ $jp->hari }}</span>
                            </td>
                            <td style="font-size: 12.5px; color: #64748b; font-weight: 600;">
                                {{ substr($jp->jam_mulai, 0, 5) }} – {{ substr($jp->jam_selesai, 0, 5) }}
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0f172a;">{{ $jp->kelas->nama_kelas ?? '-' }}</span>
                            </td>
                            <td>{{ $jp->mapel->nama_mapel ?? '-' }}</td>
                            <td style="font-weight: 600;">{{ $jp->guru->nama_lengkap ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
