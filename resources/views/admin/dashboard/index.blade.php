@extends('layouts.admin')

@section('title', 'Dashboard - Jurnal Absensi SMKN 1 BOYOLANGU')

@section('styles')
<style>
    /* Section Greeting */
    .greeting-section {
        margin-bottom: 24px;
    }

    .greeting-title {
        font-size: 22px;
        font-weight: 700;
        color: #1b2559;
        margin-bottom: 4px;
    }

    .greeting-subtitle {
        font-size: 14px;
        color: #6b7a99;
        font-weight: 500;
    }

    /* Top 5 Stat Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #a3ed9d;
        color: #707e94;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .stat-icon.blue {
        background-color: #eef2ff;
        color: #2b43b9;
    }

    .stat-icon.green {
        background-color: #e6f9f0;
        color: #10b981;
    }

    .stat-icon.orange {
        background-color: #fff7ed;
        color: #f97316;
    }

    .stat-icon.red {
        background-color: #fef2f2;
        color: #ef4444;
    }

    .stat-icon.dark {
        background-color: #f1f5f9;
        color: #334155;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #1b2559;
        line-height: 1.1;
        margin-bottom: 8px;
    }

    .stat-footer {
        font-size: 12px;
        color: #707e94;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-badge-green {
        color: #10b981;
        font-weight: 700;
    }

    .stat-badge-red {
        color: #ef4444;
        font-weight: 700;
    }

    /* Middle Row Charts */
    .charts-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .chart-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .chart-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b2559;
    }

    .class-select {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        outline: none;
        cursor: pointer;
    }

    .line-chart-container {
        position: relative;
        height: 220px;
        width: 100%;
    }

    .donut-chart-container {
        position: relative;
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .donut-center-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }

    .donut-center-pct {
        font-size: 22px;
        font-weight: 800;
        color: #1b2559;
        line-height: 1;
    }

    .donut-center-lbl {
        font-size: 11px;
        color: #6b7a99;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Bottom Row Widgets */
    .widgets-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .widget-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef2f7;
    }

    .widget-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b2559;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .attention-list, .activity-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .attention-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .attention-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .attention-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .attention-icon.red {
        background-color: #ef4444;
        color: white;
    }

    .attention-icon.orange {
        background-color: #f97316;
        color: white;
    }

    .attention-icon.red-arrow {
        background-color: #ef4444;
        color: white;
    }

    .attention-content {
        display: flex;
        flex-direction: column;
    }

    .attention-head {
        font-size: 13px;
        font-weight: 700;
        color: #1b2559;
    }

    .attention-sub {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .activity-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .activity-content {
        display: flex;
        flex-direction: column;
    }

    .activity-desc {
        font-size: 13px;
        font-weight: 700;
        color: #1b2559;
    }

    .activity-time {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
<!-- Greeting Section -->
<div class="greeting-section">
    <h2 class="greeting-title">Selamat pagi, Admin</h2>
    <p class="greeting-subtitle">Berikut ringkasan aktivitas absensi & jurnal hari ini — {{ $dateFormatted }}</p>
</div>

<!-- 5 Stat Cards Grid -->
<div class="stats-grid">
    <!-- Card 1: TOTAL SISWA -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">TOTAL SISWA</span>
            <div class="stat-icon blue">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($totalSiswa, 0, ',', '.') }}</div>
        <div class="stat-footer">
            <span>{{ $totalRombel }} rombel aktif</span>
        </div>
    </div>

    <!-- Card 2: HADIR HARI INI -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">HADIR HARI INI</span>
            <div class="stat-icon green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($hadirHariIni, 0, ',', '.') }}</div>
        <div class="stat-footer">
            <span class="stat-badge-green">▲ {{ $pctHadir }}% dari total siswa</span>
        </div>
    </div>

    <!-- Card 3: Izin / Sakit -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Izin / Sakit</span>
            <div class="stat-icon orange">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $izinSakitHariIni }}</div>
        <div class="stat-footer">
            <span>{{ $pctIzinSakit }}% dari total siswa</span>
        </div>
    </div>

    <!-- Card 4: Alpa Hari Ini -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Alpa Hari Ini</span>
            <div class="stat-icon red">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
        <div class="stat-value">{{ $alpaHariIni }}</div>
        <div class="stat-footer">
            <span class="stat-badge-red">▲ naik dari kemarin ({{ $alpaKemarin }})</span>
        </div>
    </div>

    <!-- Card 5: Jurnal Terisi -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Jurnal Terisi</span>
            <div class="stat-icon dark">
                <i class="fa-solid fa-bookmark"></i>
            </div>
        </div>
        <div class="stat-value">{{ $jurnalTerisiCount }}/{{ $jurnalTargetCount }}</div>
        <div class="stat-footer">
            <span>jam pelajaran hari ini</span>
        </div>
    </div>
</div>

<!-- Middle Row Charts -->
<div class="charts-grid">
    <!-- Tren Kehadiran Siswa Line Chart -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">Tren Kehadiran Siswa — 7 Hari Terakhir</h3>
            <select class="class-select">
                <option value="all">semua Kelas</option>
                <option value="rpl">RPL</option>
                <option value="tkj">TKJ</option>
            </select>
        </div>
        <div class="line-chart-container">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Komposisi Hari Ini Donut Chart -->
    <div class="chart-card">
        <div class="chart-card-header">
            <h3 class="chart-card-title">Komposisi Hari Ini</h3>
        </div>
        <div class="donut-chart-container">
            <canvas id="donutChart"></canvas>
            <div class="donut-center-text">
                <div class="donut-center-pct">{{ $pctHadir }}%</div>
                <div class="donut-center-lbl">Kehadiran</div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row Widgets -->
<div class="widgets-grid">
    <!-- Perlu Perhatian -->
    <div class="widget-card">
        <h3 class="widget-title">
            <i class="fa-solid fa-triangle-exclamation" style="color: #eab308;"></i>
            <span>Perlu Perhatian</span>
        </h3>
        <div class="attention-list">
            <div class="attention-item">
                <div class="attention-icon red">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="attention-content">
                    <span class="attention-head">3 siswa alpa berturut-turut</span>
                    <span class="attention-sub">XII RPL 2 — sudah 3 hari tanpa keterangan</span>
                </div>
            </div>

            <div class="attention-item">
                <div class="attention-icon orange">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <div class="attention-content">
                    <span class="attention-head">14 jam pelajaran belum ada jurnal</span>
                    <span class="attention-sub">Perlu tindak lanjut sebelum jam pulang</span>
                </div>
            </div>

            <div class="attention-item">
                <div class="attention-icon red-arrow">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                <div class="attention-content">
                    <span class="attention-head">Kehadiran XI TKJ 1 di bawah 80%</span>
                    <span class="attention-sub">Hari ini hanya 76% siswa hadir</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="widget-card">
        <h3 class="widget-title">Aktivitas Terbaru</h3>
        <div class="activity-list">
            @foreach($aktivitasTerbaru as $act)
            <div class="activity-item">
                <div class="activity-avatar">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="activity-content">
                    <span class="activity-desc">{{ $act['deskripsi'] }}</span>
                    <span class="activity-time">{{ $act['waktu'] }} · {{ $act['tag'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Line Chart - Tren Kehadiran Siswa
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        
        const gradient = ctxTrend.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(43, 67, 185, 0.25)');
        gradient.addColorStop(1, 'rgba(43, 67, 185, 0.0)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    data: [200, 780, 260, 520, 200, 480, 680],
                    borderColor: '#2b43b9',
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.45,
                    pointBackgroundColor: '#2b43b9',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: '600', size: 11 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { display: false },
                        min: 0,
                        max: 900
                    }
                }
            }
        });

        // Donut Chart - Komposisi Hari Ini
        const ctxDonut = document.getElementById('donutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin/Sakit', 'Alpa'],
                datasets: [{
                    data: [{{ $pctHadir }}, {{ $pctIzinSakit }}, {{ 100 - $pctHadir - $pctIzinSakit }}],
                    backgroundColor: [
                        '#1b2559',
                        '#10b981',
                        '#00c49f'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });
    });
</script>
@endsection
