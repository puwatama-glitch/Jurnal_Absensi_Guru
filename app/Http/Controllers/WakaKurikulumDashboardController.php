<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Waka;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\JurnalMengajar;
use App\Models\PresensiSiswa;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WakaKurikulumDashboardController extends Controller
{
    /**
     * Dashboard Utama Waka Kurikulum
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $today = Carbon::today()->format('Y-m-d');
        $todayFormatted = Carbon::today()->translatedFormat('l, d F Y');
        $hariIni = Carbon::today()->translatedFormat('l');

        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();

        // 1. KPI Metrics
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $totalGuru  = Guru::count();

        // Target jadwal hari ini
        $totalJadwalHariIni = JadwalPelajaran::where('hari', $hariIni)->count();
        $jurnalTerisiCount = JurnalMengajar::where('tanggal', $today)->count();
        $pctKbmBerjalan = $totalJadwalHariIni > 0 ? min(100, round(($jurnalTerisiCount / $totalJadwalHariIni) * 100)) : 0;

        // Presensi Siswa Hari Ini (dari seluruh jurnal mengajar)
        $presensiToday = PresensiSiswa::whereHas('jurnal', fn($q) => $q->where('tanggal', $today))->get();
        $totalPresensi = $presensiToday->count();
        $hadirCount = $presensiToday->where('status', 'Hadir')->count();
        $sakitCount = $presensiToday->where('status', 'Sakit')->count();
        $izinCount  = $presensiToday->where('status', 'Izin')->count();
        $alphaCount = $presensiToday->where('status', 'Alpha')->count();
        $dispCount  = $presensiToday->where('status', 'Dispensasi')->count();
        $pctHadir = $totalPresensi > 0 ? round(($hadirCount / $totalPresensi) * 100, 1) : 0;

        // 2. Monitoring Jurnal Mengajar Terkini Hari Ini
        $recentJurnal = JurnalMengajar::with(['guru', 'kelas', 'mapel'])
            ->where('tanggal', $today)
            ->orderByDesc('id_jurnal')
            ->take(10)
            ->get();

        // 3. Ketercapaian Jurnal per Tingkat Kelas
        $tingkatStats = [];
        foreach (['X', 'XI', 'XII'] as $tk) {
            $kelasTingkatIds = Kelas::where('tingkat', $tk)->pluck('id_kelas');
            $jadwalTingkatCount = JadwalPelajaran::where('hari', $hariIni)->whereIn('id_kelas', $kelasTingkatIds)->count();
            $jurnalTingkatCount = JurnalMengajar::where('tanggal', $today)->whereIn('id_kelas', $kelasTingkatIds)->count();
            $tingkatStats[$tk] = [
                'target' => $jadwalTingkatCount,
                'terisi' => $jurnalTingkatCount,
                'pct'    => $jadwalTingkatCount > 0 ? min(100, round(($jurnalTingkatCount / $jadwalTingkatCount) * 100)) : 0,
            ];
        }

        // 4. Progress per Jurusan
        $allJurusan = Jurusan::all();
        $jurusanLabels = [];
        $jurusanJurnalCount = [];
        foreach ($allJurusan as $jur) {
            $kelasJurusanIds = Kelas::where('id_jurusan', $jur->id_jurusan)->pluck('id_kelas');
            $jurusanLabels[] = $jur->kode_jurusan;
            $jurusanJurnalCount[] = JurnalMengajar::where('tanggal', $today)->whereIn('id_kelas', $kelasJurusanIds)->count();
        }

        return view('waka_kurikulum.dashboard.index', compact(
            'user', 'waka', 'today', 'todayFormatted', 'hariIni', 'tahunAjaranAktif',
            'totalKelas', 'totalMapel', 'totalGuru', 'totalJadwalHariIni', 'jurnalTerisiCount', 'pctKbmBerjalan',
            'totalPresensi', 'hadirCount', 'sakitCount', 'izinCount', 'alphaCount', 'dispCount', 'pctHadir',
            'recentJurnal', 'tingkatStats', 'jurusanLabels', 'jurusanJurnalCount'
        ));
    }

    /**
     * Monitoring Seluruh Jurnal Mengajar Guru
     */
    public function jurnal(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $search  = $request->input('search');
        $idKelas = $request->input('id_kelas');
        $idMapel = $request->input('id_mapel');
        $idGuru  = $request->input('id_guru');

        $jurnalList = JurnalMengajar::with(['guru', 'kelas', 'mapel'])
            ->when($tanggal, fn($q) => $q->where('tanggal', $tanggal))
            ->when($idKelas, fn($q) => $q->where('id_kelas', $idKelas))
            ->when($idMapel, fn($q) => $q->where('id_mapel', $idMapel))
            ->when($idGuru,  fn($q) => $q->where('id_guru', $idGuru))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qs) use ($search) {
                    $qs->where('materi', 'LIKE', "%{$search}%")
                       ->orWhere('catatan', 'LIKE', "%{$search}%")
                       ->orWhereHas('guru', fn($qg) => $qg->where('nama_lengkap', 'LIKE', "%{$search}%"));
                });
            })
            ->orderByDesc('id_jurnal')
            ->paginate(15)
            ->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $guruList  = Guru::orderBy('nama_lengkap')->get();

        return view('waka_kurikulum.jurnal.index', compact(
            'user', 'waka', 'jurnalList', 'kelasList', 'mapelList', 'guruList',
            'tanggal', 'search', 'idKelas', 'idMapel', 'idGuru'
        ));
    }

    /**
     * Monitoring Jadwal Pelajaran
     */
    public function jadwal(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $hariFilter = $request->input('hari', Carbon::today()->translatedFormat('l'));
        $idKelas    = $request->input('id_kelas');
        $idJurusan  = $request->input('id_jurusan');
        $tingkat    = $request->input('tingkat');

        $query = JadwalPelajaran::with(['guru', 'kelas', 'mapel'])
            ->when($hariFilter && $hariFilter !== 'Semua', fn($q) => $q->where('hari', $hariFilter))
            ->when($idKelas, fn($q) => $q->where('id_kelas', $idKelas))
            ->when($tingkat, function ($q) use ($tingkat) {
                $q->whereHas('kelas', fn($qk) => $qk->where('tingkat', $tingkat));
            })
            ->when($idJurusan, function ($q) use ($idJurusan) {
                $q->whereHas('kelas', fn($qk) => $qk->where('id_jurusan', $idJurusan));
            });

        $jadwalList = $query->orderBy('jam_mulai')->get();
        $kelasList  = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $jurusanList = Jurusan::all();

        return view('waka_kurikulum.jadwal.index', compact(
            'user', 'waka', 'jadwalList', 'kelasList', 'jurusanList',
            'hariFilter', 'idKelas', 'idJurusan', 'tingkat'
        ));
    }
}
