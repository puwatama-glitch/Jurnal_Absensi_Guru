@extends('layouts.waka_kurikulum')

@section('title', 'Dashboard Waka Kurikulum')

@section('styles')
<style>
    .kpi-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px;
    }
    .kpi-card {
        background: white; border-radius: 18px; padding: 20px 22px;
        border: 1px solid #e2e8f0; display: flex; align-items: flex-start; gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.07); }
    .kpi-icon {
        width: 50px; height: 50px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .kpi-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .kpi-icon.sky    { background: #e0f2fe; color: #0369a1; }
    .kpi-icon.green  { background: #dcfce7; color: #15803d; }
    .kpi-icon.amber  { background: #fef3c7; color: #b45309; }
    .kpi-icon.rose   { background: #fee2e2; color: #b91c1c; }
    .kpi-icon.violet { background: #ede9fe; color: #7c3aed; }
    .kpi-body {}
    .kpi-val { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; }
    .kpi-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; margin-top: 4px; letter-spacing: 0.5px; }
    .kpi-sub { font-size: 12px; color: #64748b; margin-top: 3px; }

    .progress-bar-wrap { background: #f1f5f9; border-radius: 8px; height: 8px; margin-top: 8px; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 8px; transition: width 0.6s ease; }

    .card { background: white; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; }
    .card-header {
        padding: 18px 22px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-title { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .card-title i { color: #0ea5e9; }
    .card-body { padding: 20px 22px; }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        padding: 11px 14px; text-align: left; font-size: 11px;
        font-weight: 700; text-transform: uppercase; color: #94a3b8;
        border-bottom: 1px solid #f1f5f9; letter-spacing: 0.5px;
    }
    .data-table td { padding: 13px 14px; font-size: 13.5px; border-bottom: 1px solid #f8fafc; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #fafbfc; }

    .badge {
        display: inline-flex; align-items: center; padding: 3px 10px;
        border-radius: 8px; font-size: 11.5px; font-weight: 700;
    }
    .badge.hadir     { background: #d1fae5; color: #065f46; }
    .badge.alpha     { background: #fee2e2; color: #b91c1c; }
    .badge.sakit     { background: #fef3c7; color: #b45309; }
    .badge.izin      { background: #dbeafe; color: #1d4ed8; }
    .badge.disp      { background: #ede9fe; color: #6d28d9; }

    .tingkat-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .tingkat-card {
        background: #f8fafc; border-radius: 14px; padding: 16px 18px;
        border: 1px solid #e2e8f0;
    }
    .tingkat-label { font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .tingkat-val { font-size: 20px; font-weight: 800; color: #0f172a; }
    .tingkat-sub { font-size: 11.5px; color: #94a3b8; margin-top: 2px; }

    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }

    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 36px; margin-bottom: 12px; color: #cbd5e1; }
    .empty-state p { font-size: 13.5px; }

    @media (max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .charts-row { grid-template-columns: 1fr; }
        .tingkat-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('header_title', 'Dashboard Waka Kurikulum')
@section('header_subtitle', 'Monitoring Pembelajaran Harian — ' . $todayFormatted)

@section('content')
<div>

    {{-- KPI GRID --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="fa-solid fa-door-open"></i></div>
            <div class="kpi-body">
                <div class="kpi-val">{{ $totalKelas }}</div>
                <div class="kpi-lbl">Total Kelas</div>
                <div class="kpi-sub">Rombongan Belajar Aktif</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon sky"><i class="fa-solid fa-book-open"></i></div>
            <div class="kpi-body">
                <div class="kpi-val">{{ $totalMapel }}</div>
                <div class="kpi-lbl">Mata Pelajaran</div>
                <div class="kpi-sub">Tercatat di Sistem</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="kpi-body">
                <div class="kpi-val">{{ $jurnalTerisiCount }} / {{ $totalJadwalHariIni }}</div>
                <div class="kpi-lbl">Jurnal Hari Ini</div>
                <div class="kpi-sub">
                    {{ $pctKbmBerjalan }}% KBM Terlaporkan
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width: {{ $pctKbmBerjalan }}%; background: {{ $pctKbmBerjalan >= 80 ? '#10b981' : ($pctKbmBerjalan >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-body">
                <div class="kpi-val">{{ $pctHadir }}%</div>
                <div class="kpi-lbl">Kehadiran Siswa</div>
                <div class="kpi-sub">{{ $hadirCount }} hadir dari {{ $totalPresensi }} sesi</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width: {{ $pctHadir }}%; background: {{ $pctHadir >= 90 ? '#10b981' : ($pctHadir >= 75 ? '#f59e0b' : '#ef4444') }};"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TINGKAT KBM STATS --}}
    <div class="charts-row">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-bar"></i> Ketercapaian Jurnal per Tingkat</div>
            </div>
            <div class="card-body">
                <div class="tingkat-stats">
                    @foreach($tingkatStats as $tk => $stat)
                    <div class="tingkat-card">
                        <div class="tingkat-label">TINGKAT {{ $tk }}</div>
                        <div class="tingkat-val">{{ $stat['terisi'] }} <span style="font-size:14px;font-weight:500;color:#94a3b8;">/ {{ $stat['target'] }}</span></div>
                        <div class="tingkat-sub">{{ $stat['pct'] }}% jurnal terisi hari ini</div>
                        <div class="progress-bar-wrap" style="margin-top: 10px;">
                            <div class="progress-bar" style="width: {{ $stat['pct'] }}%; background: {{ $stat['pct'] >= 80 ? '#10b981' : ($stat['pct'] >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Presensi Summary --}}
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-top: 20px;">
                    @foreach([
                        ['Hadir', $hadirCount, '#10b981'],
                        ['Sakit', $sakitCount, '#f59e0b'],
                        ['Izin', $izinCount, '#3b82f6'],
                        ['Alpha', $alphaCount, '#ef4444'],
                        ['Disp.', $dispCount, '#8b5cf6'],
                    ] as $ps)
                    <div style="text-align: center; background: #f8fafc; border-radius: 12px; padding: 12px 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 20px; font-weight: 800; color: {{ $ps[2] }};">{{ $ps[1] }}</div>
                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-top: 3px;">{{ $ps[0] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list-check"></i> Jurnal Mengajar Terkini</div>
                <a href="{{ route('waka-kurikulum.jurnal') }}" style="font-size: 13px; color: #0ea5e9; font-weight: 700; text-decoration: none;">Lihat Semua</a>
            </div>
            <div style="overflow: hidden;">
                @if($recentJurnal->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <p>Belum ada jurnal dientri hari ini.</p>
                    </div>
                @else
                    <div class="table-responsive-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Guru</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentJurnal as $j)
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; font-size: 13px; color: #0f172a;">{{ Str::limit($j->guru->nama_lengkap ?? '-', 24) }}</div>
                                    </td>
                                    <td style="color: #64748b;">{{ $j->kelas->nama_kelas ?? '-' }}</td>
                                    <td style="color: #64748b;">{{ Str::limit($j->mapel->nama_mapel ?? '-', 16) }}</td>
                                    <td>
                                        <span style="font-size: 12px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 6px;">
                                            J{{ $j->jam_ke }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
