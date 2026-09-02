<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Waka;
use App\Models\Guru;
use App\Models\IzinGuru;
use App\Models\JurnalMengajar;
use App\Models\LaporanPiket;
use App\Models\GuruPiket;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WakaSdmDashboardController extends Controller
{
    /**
     * Dashboard Utama Waka SDM / Kepegawaian
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $today = Carbon::today()->format('Y-m-d');
        $todayFormatted = Carbon::today()->translatedFormat('l, d F Y');
        $hariIni = Carbon::today()->translatedFormat('l');

        // 1. KPI SDM Guru
        $totalGuru = Guru::count();

        // Guru yang terjadwal mengajar hari ini
        $guruTerjadwalTodayIds = JadwalPelajaran::where('hari', $hariIni)->distinct()->pluck('id_guru');
        $totalGuruTerjadwal = $guruTerjadwalTodayIds->count();

        // Guru yang sudah mengisi jurnal hari ini
        $guruHadirMengajarIds = JurnalMengajar::where('tanggal', $today)->distinct()->pluck('id_guru');
        $guruHadirCount = $guruHadirMengajarIds->count();

        // Guru yang sedang izin / sakit hari ini
        $guruIzinToday = IzinGuru::with('guru')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->get();
        $guruIzinCount = $guruIzinToday->count();

        // Guru belum mengisi jurnal dari yang terjadwal
        $guruBelumMengisiCount = max(0, $totalGuruTerjadwal - $guruHadirCount - $guruIzinCount);
        $pctKepatuhan = $totalGuruTerjadwal > 0 ? min(100, round(($guruHadirCount / $totalGuruTerjadwal) * 100)) : 0;

        // 2. Laporan Piket Hari Ini
        $laporanPiketToday = LaporanPiket::where('tanggal', $today)->first();

        // 3. Guru Piket Bertugas Hari Ini
        $guruPiketToday = GuruPiket::where('hari_piket', $hariIni)->get();

        // 4. Aktivitas Jurnal Mengajar Pendidik Terkini
        $recentJurnal = JurnalMengajar::with(['guru', 'kelas', 'mapel'])
            ->where('tanggal', $today)
            ->orderByDesc('id_jurnal')
            ->take(8)
            ->get();

        return view('waka_sdm.dashboard.index', compact(
            'user', 'waka', 'today', 'todayFormatted', 'hariIni',
            'totalGuru', 'totalGuruTerjadwal', 'guruHadirCount', 'guruIzinCount', 'guruBelumMengisiCount', 'pctKepatuhan',
            'guruIzinToday', 'laporanPiketToday', 'guruPiketToday', 'recentJurnal'
        ));
    }

    /**
     * Rekap Kehadiran & Kepatuhan Mengajar Guru
     */
    public function guru(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $search = $request->input('search');
        $bulan  = $request->input('bulan', Carbon::today()->format('Y-m'));

        $query = Guru::query()->with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%");
            });

        $guruList = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        // Attach statistics for each guru in selected month
        $guruList->getCollection()->transform(function ($g) use ($bulan) {
            $g->total_jurnal = JurnalMengajar::where('id_guru', $g->id_guru)
                ->where('tanggal', 'LIKE', "{$bulan}%")
                ->count();
            $g->total_izin = IzinGuru::where('id_guru', $g->id_guru)
                ->where('tanggal_mulai', 'LIKE', "{$bulan}%")
                ->count();
            return $g;
        });

        return view('waka_sdm.guru.index', compact(
            'user', 'waka', 'guruList', 'search', 'bulan'
        ));
    }

    /**
     * Monitoring & Persetujuan Izin Guru
     */
    public function izin(Request $request)
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $waka = $user->waka ?? null;

        $search = $request->input('search');
        $status = $request->input('status');

        $izinList = IzinGuru::with(['guru', 'disetujuiOlehUser'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('guru', fn($qg) => $qg->where('nama_lengkap', 'LIKE', "%{$search}%"))
                  ->orWhere('alasan', 'LIKE', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('waka_sdm.izin.index', compact(
            'user', 'waka', 'izinList', 'search', 'status'
        ));
    }

    /**
     * Update Status Persetujuan Izin Guru
     */
    public function updateStatusIzin(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        $izin = IzinGuru::findOrFail($id);
        $izin->update([
            'status' => $request->status,
            'disetujui_oleh' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', "Status izin guru {$izin->guru->nama_lengkap} berhasil diubah menjadi {$request->status}.");
    }
}
