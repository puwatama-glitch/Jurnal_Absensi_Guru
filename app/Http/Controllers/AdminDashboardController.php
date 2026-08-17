<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JurnalMengajar;
use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // ── KPI Cards ──────────────────────────────────────────
        $totalSiswa  = Siswa::where('status_aktif', true)->count();
        $totalGuru   = Guru::where('status_aktif', true)->count();
        $totalRombel = Kelas::count();

        // Jurnal hari ini
        $jurnalTerisiCount = JurnalMengajar::where('tanggal', $today)->count();
        $jurnalTargetCount = 48;

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
        $pctHadir = $totalPresensiHariIni > 0
            ? round(($hadirHariIni / $totalPresensiHariIni) * 100)
            : 0;

        $pctIzinSakit = $totalPresensiHariIni > 0
            ? round(($izinSakitHariIni / $totalPresensiHariIni) * 100)
            : 0;

        // ── Trend Kehadiran 7 Hari Terakhir ────────────────────
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

        // ── Aktivitas Terbaru ───────────────────────────────────
        $logs = DB::table('log_aktivitas')->orderBy('created_at', 'desc')->take(5)->get();
        $aktivitasTerbaru = $logs->map(fn($l) => [
            'deskripsi' => $l->deskripsi,
            'waktu'     => Carbon::parse($l->created_at)->translatedFormat('H:i'),
            'tag'       => $l->aksi,
        ])->toArray();

        // ── Perlu Perhatian (real alerts) ───────────────────────
        $weekAgo = Carbon::today()->subDays(7)->format('Y-m-d');
        $alpaCountByStudent = PresensiSiswa::whereHas('jurnal', fn($q) => $q->whereBetween('tanggal', [$weekAgo, $today]))
            ->where('status', 'Alpha')
            ->select('id_siswa', DB::raw('COUNT(*) as cnt'))
            ->groupBy('id_siswa')
            ->having('cnt', '>=', 3)
            ->count();

        $perluPerhatian = [];
        if ($alpaCountByStudent > 0) {
            $perluPerhatian[] = [
                'type'     => 'danger',
                'title'    => "{$alpaCountByStudent} siswa alpa ≥ 3 kali dalam 7 hari",
                'subtitle' => 'Perlu tindak lanjut segera',
                'icon'     => 'x-circle',
            ];
        }

        $jurnalKosong = JurnalMengajar::where('tanggal', $today)->where('materi', '')->count();
        if ($jurnalKosong > 0) {
            $perluPerhatian[] = [
                'type'     => 'warning',
                'title'    => "{$jurnalKosong} jurnal belum diisi materi",
                'subtitle' => 'Hari ini masih ada jurnal kosong',
                'icon'     => 'bookmark-warning',
            ];
        }

        if (empty($perluPerhatian)) {
            $perluPerhatian[] = [
                'type'     => 'success',
                'title'    => 'Semua berjalan baik hari ini',
                'subtitle' => 'Tidak ada alert yang memerlukan perhatian',
                'icon'     => 'check-circle',
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
