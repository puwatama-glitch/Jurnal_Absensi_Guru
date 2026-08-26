<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JurnalMengajar;
use App\Models\PresensiSiswa;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $today     = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $hariIni   = Carbon::today()->translatedFormat('l');

        // ── KPI Cards ──────────────────────────────────────────
        $totalSiswa  = Siswa::where('status_aktif', true)->count();
        $totalGuru   = Guru::where('status_aktif', true)->count();
        $totalRombel = Kelas::count();

        // Target & Realisasi Jurnal hari ini
        $jurnalTerisiCount = JurnalMengajar::where('tanggal', $today)->count();
        $targetHariIni     = JadwalPelajaran::where('hari', $hariIni)->count();
        $jurnalTargetCount = $targetHariIni > 0 ? $targetHariIni : max(1, JadwalPelajaran::count());

        // Presensi hari ini
        $hadirHariIni = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $today))
            ->where('status', 'Hadir')->distinct('id_siswa')->count('id_siswa');

        $izinSakitHariIni = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $today))
            ->whereIn('status', ['Sakit', 'Izin', 'Dispensasi'])
            ->distinct('id_siswa')->count('id_siswa');

        $alpaHariIni = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $today))
            ->where('status', 'Alpha')->distinct('id_siswa')->count('id_siswa');

        $alpaKemarin = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $yesterday))
            ->where('status', 'Alpha')->distinct('id_siswa')->count('id_siswa');

        $totalPresensiHariIni = $hadirHariIni + $izinSakitHariIni + $alpaHariIni;

        // If today has no attendance records yet (e.g. morning/outside class hours), calculate percentages gracefully
        $pctHadir = $totalPresensiHariIni > 0
            ? round(($hadirHariIni / $totalPresensiHariIni) * 100)
            : 0;

        $pctIzinSakit = $totalPresensiHariIni > 0
            ? round(($izinSakitHariIni / $totalPresensiHariIni) * 100)
            : 0;

        $pctAlpa = $totalPresensiHariIni > 0
            ? (100 - $pctHadir - $pctIzinSakit)
            : 0;

        // ── Trend Kehadiran 7 Hari Terakhir (Real Data) ────────
        $trendLabels = [];
        $trendData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $day   = Carbon::today()->subDays($i);
            $count = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $day->format('Y-m-d')))
                ->where('status', 'Hadir')->count();

            $trendLabels[] = $day->translatedFormat('D');
            $trendData[]   = $count;
        }

        $trend7Hari = [
            'labels' => $trendLabels,
            'data'   => $trendData,
        ];

        // ── Aktivitas Terbaru (Real Multi-Source Feed) ──────────
        $activityFeed = collect();

        // 1. Jurnal Mengajar Terbaru yang diinput guru
        $recentJurnal = JurnalMengajar::with(['guru', 'kelas', 'mapel'])
            ->orderByDesc('id_jurnal')
            ->take(8)
            ->get();

        foreach ($recentJurnal as $j) {
            $guruName  = $j->guru->nama_lengkap ?? 'Guru';
            $mapelName = $j->mapel->nama_mapel ?? 'Mata Pelajaran';
            $kelasName = $j->kelas->nama_kelas ?? 'Kelas';
            $jamKe     = $j->jam_ke ? " (Jam ke-{$j->jam_ke})" : "";
            $tglTime   = $j->created_at ?? Carbon::parse($j->tanggal);

            $activityFeed->push([
                'deskripsi'  => "{$guruName} menginput jurnal {$mapelName} di {$kelasName}{$jamKe}",
                'waktu'      => $tglTime->diffForHumans(),
                'tag'        => 'Jurnal',
                'icon'       => 'fa-solid fa-book-open-reader',
                'icon_bg'    => 'linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%)',
                'icon_color' => '#3730a3',
                'timestamp'  => $tglTime->timestamp,
            ]);
        }

        // 2. Presensi Siswa Non-Hadir (Alpha, Sakit, Izin, Dispensasi)
        $recentPresensi = PresensiSiswa::with(['siswa.kelas', 'jurnal.mapel'])
            ->whereIn('status', ['Alpha', 'Sakit', 'Izin', 'Dispensasi'])
            ->orderByDesc('id')
            ->take(6)
            ->get();

        foreach ($recentPresensi as $p) {
            $siswaName = $p->siswa->nama_lengkap ?? 'Siswa';
            $kelasName = $p->siswa->kelas->nama_kelas ?? '-';
            $ket       = $p->keterangan ? " — \"{$p->keterangan}\"" : "";
            $tglTime   = $p->created_at ?? ($p->jurnal ? Carbon::parse($p->jurnal->tanggal) : now());

            $icon = match($p->status) {
                'Alpha'       => 'fa-solid fa-user-xmark',
                'Sakit'       => 'fa-solid fa-notes-medical',
                'Izin'        => 'fa-solid fa-envelope-open-text',
                'Dispensasi'  => 'fa-solid fa-id-badge',
                default       => 'fa-solid fa-clipboard-user',
            };

            $iconBg = match($p->status) {
                'Alpha'       => '#fee2e2',
                'Sakit'       => '#fef3c7',
                'Izin'        => '#e0f2fe',
                'Dispensasi'  => '#ede9fe',
                default       => '#f1f5f9',
            };

            $iconColor = match($p->status) {
                'Alpha'       => '#ef4444',
                'Sakit'       => '#d97706',
                'Izin'        => '#0284c7',
                'Dispensasi'  => '#7c3aed',
                default       => '#475569',
            };

            $activityFeed->push([
                'deskripsi'  => "{$siswaName} ({$kelasName}) tercatat {$p->status}{$ket}",
                'waktu'      => $tglTime->diffForHumans(),
                'tag'        => $p->status,
                'icon'       => $icon,
                'icon_bg'    => $iconBg,
                'icon_color' => $iconColor,
                'timestamp'  => $tglTime->timestamp,
            ]);
        }

        // 3. Jadwal Pelajaran Terbaru
        $recentJadwal = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
            ->orderByDesc('id_jadwal')
            ->take(4)
            ->get();

        foreach ($recentJadwal as $jadwal) {
            $mapelName = $jadwal->mapel->nama_mapel ?? 'Mapel';
            $kelasName = $jadwal->kelas->nama_kelas ?? 'Kelas';
            $tglTime   = $jadwal->created_at ?? now();

            $activityFeed->push([
                'deskripsi'  => "Jadwal {$mapelName} ({$kelasName}) — {$jadwal->hari} jam ke-{$jadwal->jam_ke} diperbarui",
                'waktu'      => $tglTime->diffForHumans(),
                'tag'        => 'Jadwal',
                'icon'       => 'fa-solid fa-calendar-check',
                'icon_bg'    => '#dcfce7',
                'icon_color' => '#15803d',
                'timestamp'  => $tglTime->timestamp,
            ]);
        }

        // 4. Log dari log_aktivitas jika ada
        $logs = DB::table('log_aktivitas')->orderByDesc('created_at')->take(4)->get();
        foreach ($logs as $l) {
            $tglTime = Carbon::parse($l->created_at);
            $activityFeed->push([
                'deskripsi'  => $l->deskripsi,
                'waktu'      => $tglTime->diffForHumans(),
                'tag'        => $l->aksi,
                'icon'       => 'fa-solid fa-bolt',
                'icon_bg'    => '#f1f5f9',
                'icon_color' => '#475569',
                'timestamp'  => $tglTime->timestamp,
            ]);
        }

        // Sort descending by timestamp and take top 6
        $aktivitasTerbaru = $activityFeed->sortByDesc('timestamp')->take(6)->values()->all();

        // ── Perlu Perhatian (100% Real Dynamic Alerts) ──────────
        $perluPerhatian = [];

        // 1. Siswa dengan Catatan Alpha (Prioritas Tinggi)
        $alpaStudents = PresensiSiswa::where('status', 'Alpha')
            ->with(['siswa.kelas'])
            ->select('id_siswa', DB::raw('count(*) as total_alpha'))
            ->groupBy('id_siswa')
            ->orderByDesc('total_alpha')
            ->get();

        if ($alpaStudents->count() > 0) {
            $topAlpa   = $alpaStudents->first();
            $siswaName = $topAlpa->siswa->nama_lengkap ?? 'Siswa';
            $kelasName = $topAlpa->siswa->kelas->nama_kelas ?? '-';
            $totalSiswaAlpa = $alpaStudents->count();

            $perluPerhatian[] = [
                'type'     => 'danger',
                'title'    => "{$totalSiswaAlpa} siswa memiliki catatan Alpha (tertinggi: {$siswaName} {$topAlpa->total_alpha}x)",
                'subtitle' => "Kelas {$kelasName} — segera koordinasikan dengan wali kelas & BK",
                'icon'     => 'fa-solid fa-triangle-exclamation',
                'url'      => route('admin.absensi'),
            ];
        }

        // 2. Kelas dengan Tingkat Ketidakhadiran Tertinggi
        $absenByClass = PresensiSiswa::whereIn('status', ['Alpha', 'Sakit', 'Izin'])
            ->join('siswa', 'presensi_siswa.id_siswa', '=', 'siswa.id_siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'kelas.nama_kelas',
                'kelas.id_kelas',
                DB::raw('count(*) as total_absen'),
                DB::raw("SUM(CASE WHEN presensi_siswa.status = 'Alpha' THEN 1 ELSE 0 END) as alpha_cnt"),
                DB::raw("SUM(CASE WHEN presensi_siswa.status = 'Sakit' THEN 1 ELSE 0 END) as sakit_cnt"),
                DB::raw("SUM(CASE WHEN presensi_siswa.status = 'Izin' THEN 1 ELSE 0 END) as izin_cnt")
            )
            ->groupBy('kelas.id_kelas', 'kelas.nama_kelas')
            ->orderByDesc('total_absen')
            ->first();

        if ($absenByClass && $absenByClass->total_absen > 0) {
            $details = [];
            if ($absenByClass->alpha_cnt > 0) $details[] = "{$absenByClass->alpha_cnt} Alpha";
            if ($absenByClass->sakit_cnt > 0) $details[] = "{$absenByClass->sakit_cnt} Sakit";
            if ($absenByClass->izin_cnt > 0) $details[] = "{$absenByClass->izin_cnt} Izin";
            $detailStr = implode(', ', $details);

            $perluPerhatian[] = [
                'type'     => 'warning',
                'title'    => "Ketidakhadiran menonjol di kelas {$absenByClass->nama_kelas}",
                'subtitle' => "Total {$absenByClass->total_absen} ketidakhadiran ({$detailStr})",
                'icon'     => 'fa-solid fa-users-viewfinder',
                'url'      => route('admin.absensi'),
            ];
        }

        // 3. Jadwal Hari Ini yang Belum Terisi Jurnalnya
        if ($targetHariIni > 0 && $jurnalTerisiCount < $targetHariIni) {
            $selisih = $targetHariIni - $jurnalTerisiCount;
            $perluPerhatian[] = [
                'type'     => 'warning',
                'title'    => "{$selisih} dari {$targetHariIni} jadwal hari {$hariIni} belum diisi jurnalnya",
                'subtitle' => "Pantau pengisian jurnal guru pengajar hari ini",
                'icon'     => 'fa-solid fa-clock-rotate-left',
                'url'      => route('admin.absensi'),
            ];
        }

        // 4. Siswa Sakit & Izin yang Memerlukan Pemantauan
        $sakitIzinCount = PresensiSiswa::whereIn('status', ['Sakit', 'Izin'])->count();
        if ($sakitIzinCount > 0 && count($perluPerhatian) < 3) {
            $perluPerhatian[] = [
                'type'     => 'info',
                'title'    => "{$sakitIzinCount} catatan siswa Sakit / Izin terdata",
                'subtitle' => "Pastikan surat keterangan izin/sakit telah diserahkan ke wali kelas",
                'icon'     => 'fa-solid fa-notes-medical',
                'url'      => route('admin.absensi'),
            ];
        }

        // 5. Fallback jika seluruh data kehadiran bersih tanpa anomali
        if (empty($perluPerhatian)) {
            $perluPerhatian[] = [
                'type'     => 'success',
                'title'    => 'Seluruh aktivitas presensi & pembelajaran berjalan normal',
                'subtitle' => 'Tidak ada peringatan atau catatan pelanggaran kehadiran',
                'icon'     => 'fa-solid fa-circle-check',
                'url'      => route('admin.absensi'),
            ];
        }

        $authUser      = Auth::user();
        $namaUser      = $authUser ? $authUser->name : 'Admin';
        $dateFormatted = Carbon::now()->translatedFormat('l, j F Y');

        return view('admin.dashboard.index', compact(
            'totalSiswa',
            'totalRombel',
            'hadirHariIni',
            'izinSakitHariIni',
            'alpaHariIni',
            'alpaKemarin',
            'pctHadir',
            'pctIzinSakit',
            'pctAlpa',
            'jurnalTerisiCount',
            'jurnalTargetCount',
            'trend7Hari',
            'perluPerhatian',
            'aktivitasTerbaru',
            'dateFormatted',
            'namaUser'
        ));
    }

    public function help()
    {
        return view('admin.help.index');
    }
}
