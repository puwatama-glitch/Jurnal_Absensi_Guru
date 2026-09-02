@extends('layouts.waka_sdm')
@section('title', 'Dashboard Waka SDM')
@section('header_title', 'Dashboard Waka SDM')
@section('header_subtitle', 'Monitoring Kehadiran & Kepegawaian Guru — ' . $todayFormatted)
@section('styles')
<style>
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
    .kpi-card { background: white; border-radius: 18px; padding: 20px 22px; border: 1px solid #e2e8f0; display: flex; align-items: flex-start; gap: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.07); }
    .kpi-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .kpi-icon.violet { background: #ede9fe; color: #7c3aed; }
    .kpi-icon.green  { background: #dcfce7; color: #15803d; }
    .kpi-icon.amber  { background: #fef3c7; color: #b45309; }
    .kpi-icon.rose   { background: #fee2e2; color: #b91c1c; }
    .kpi-val { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; }
    .kpi-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; margin-top: 4px; }
    .kpi-sub { font-size: 12px; color: #64748b; margin-top: 3px; }
    .progress-bar-wrap { background: #f1f5f9; border-radius: 8px; height: 8px; margin-top: 8px; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 8px; transition: width 0.6s ease; }

    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .card { background: white; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; }
    .card-header { padding: 18px 22px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
    .card-title { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .card-title i { color: #8b5cf6; }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #f1f5f9; }
    .data-table td { padding: 13px 16px; font-size: 13.5px; border-bottom: 1px solid #f8fafc; color: #334155; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #f8fafc; }
    .guru-name { font-weight: 700; font-size: 13px; color: #0f172a; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 8px; font-size: 11.5px; font-weight: 700; }
    .badge.hadir { background: #d1fae5; color: #065f46; }
    .badge.alpha { background: #fee2e2; color: #b91c1c; }
    .badge.sakit { background: #fef3c7; color: #b45309; }
    .badge.izin  { background: #dbeafe; color: #1d4ed8; }
    .badge.pending { background: #fef3c7; color: #92400e; }
    .badge.disetujui { background: #d1fae5; color: #065f46; }
    .badge.ditolak { background: #fee2e2; color: #b91c1c; }

    .empty-state { text-align: center; padding: 50px 20px; color: #94a3b8; }
    .empty-state i { font-size: 36px; margin-bottom: 12px; }

    @media (max-width: 1024px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } .charts-row { grid-template-columns: 1fr; } }
</style>
@endsection
@section('content')
<div>
    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon violet"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <div class="kpi-val">{{ $totalGuru }}</div>
                <div class="kpi-lbl">Total Guru</div>
                <div class="kpi-sub">Guru Aktif Terdaftar</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="fa-solid fa-user-check"></i></div>
            <div>
                <div class="kpi-val">{{ $guruHadirCount }}</div>
                <div class="kpi-lbl">Guru Hadir</div>
                <div class="kpi-sub">Sudah Isi Jurnal Hari Ini</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width: {{ $pctGuruHadir }}%; background: #10b981;"></div>
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber"><i class="fa-solid fa-file-circle-question"></i></div>
            <div>
                <div class="kpi-val">{{ $izinPendingCount }}</div>
                <div class="kpi-lbl">Izin Pending</div>
                <div class="kpi-sub">Menunggu Persetujuan</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon rose"><i class="fa-solid fa-user-slash"></i></div>
            <div>
                <div class="kpi-val">{{ $guruAlpaCount }}</div>
                <div class="kpi-lbl">Guru Tidak Hadir</div>
                <div class="kpi-sub">Tanpa Izin Hari Ini</div>
            </div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="charts-row">
        {{-- Guru yg belum isi jurnal --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-user-clock"></i> Guru Belum Isi Jurnal Hari Ini</div>
                <span style="font-size: 12px; background: #fee2e2; color: #b91c1c; padding: 2px 10px; border-radius: 8px; font-weight: 700;">{{ $guruAlpaCount }} Guru</span>
            </div>
            @if($guruBelumIsiJurnal->isEmpty())
                <div class="empty-state"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i><p>Semua guru sudah mengisi jurnal! 🎉</p></div>
            @else
                <div class="table-responsive-wrap">
                    <table class="data-table">
                        <thead><tr><th>Nama Guru</th><th>NIP</th><th>Jadwal Hari Ini</th></tr></thead>
                        <tbody>
                            @foreach($guruBelumIsiJurnal->take(10) as $g)
                            <tr>
                                <td><div class="guru-name">{{ $g->nama_lengkap }}</div></td>
                                <td style="color: #64748b; font-size: 12.5px;">{{ $g->nip ?? '-' }}</td>
                                <td><span class="badge alpha">{{ $g->jadwal_count }} JP</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Izin Pending --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-file-circle-check"></i> Izin Mengajar Pending</div>
                <a href="{{ route('waka-sdm.izin') }}" style="font-size: 13px; color: #8b5cf6; font-weight: 700; text-decoration: none;">Lihat Semua</a>
            </div>
            @if($izinPending->isEmpty())
                <div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Tidak ada permohonan izin pending.</p></div>
            @else
                <div class="table-responsive-wrap">
                    <table class="data-table">
                        <thead><tr><th>Guru</th><th>Tanggal</th><th>Alasan</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($izinPending->take(8) as $iz)
                            <tr>
                                <td><div class="guru-name">{{ Str::limit($iz->guru->nama_lengkap ?? '-', 20) }}</div></td>
                                <td style="font-size: 12.5px; color: #64748b;">{{ \Carbon\Carbon::parse($iz->tanggal)->format('d M Y') }}</td>
                                <td style="font-size: 12px; color: #475569; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($iz->alasan, 28) }}</td>
                                <td><span class="badge pending">Pending</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
