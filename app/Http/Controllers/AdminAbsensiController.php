<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalMengajar;
use App\Models\PresensiSiswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class AdminAbsensiController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // Default ke hari ini
        $tanggalInput = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $selectedTanggal = Carbon::parse($tanggalInput)->format('Y-m-d');
        $formattedDateTitle = Carbon::parse($selectedTanggal)->translatedFormat('l, j F Y');

        $selectedKelas  = $request->input('id_kelas');
        $selectedJam    = $request->input('jam_ke');
        $selectedStatus = $request->input('status');

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $jamList = [
            'Jam 1-2' => 'Jam 1-2 (07:00-08:30)',
            'Jam 3-4' => 'Jam 3-4 (08:30-10:00)',
            'Jam 5-6' => 'Jam 5-6 (10:15-11:45)',
            'Jam 7-8' => 'Jam 7-8 (12:30-14:00)',
        ];

        // Fetch Jurnal Mengajar
        $query = JurnalMengajar::with(['guru', 'kelas', 'mapel', 'presensiSiswa.siswa'])
            ->where('tanggal', $selectedTanggal)
            ->when($selectedKelas, fn($q) => $q->where('id_kelas', $selectedKelas))
            ->when($selectedJam, fn($q) => $q->where('jam_ke', 'LIKE', "%{$selectedJam}%"));

        $jurnalList = $query->orderBy('jam_mulai', 'asc')->get();

        // KPI Statistics — dari data real
        $jurnalSudahDiisi = JurnalMengajar::where('tanggal', $selectedTanggal)->count();
        $totalSesi        = max($jurnalSudahDiisi, 0);
        $belumDiisiGuru   = 0; // Tanpa target hardcode

        $siswaTidakMasuk = PresensiSiswa::whereHas('jurnal', function ($q) use ($selectedTanggal) {
            $q->where('tanggal', $selectedTanggal);
        })->whereIn('status', ['Sakit', 'Izin', 'Alpha', 'Dispensasi'])->count();

        return view('admin.absensi.index', compact(
            'selectedTanggal', 'formattedDateTitle',
            'selectedKelas', 'selectedJam', 'selectedStatus',
            'kelasList', 'jamList', 'jurnalList',
            'totalSesi', 'jurnalSudahDiisi', 'belumDiisiGuru', 'siswaTidakMasuk'
        ));
    }

    public function show($id)
    {
        Carbon::setLocale('id');
        $jurnal = JurnalMengajar::with(['guru', 'kelas', 'mapel', 'presensiSiswa.siswa'])->find($id);

        if (!$jurnal) {
            return response()->json(['status' => 'error', 'message' => 'Data jurnal tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id_jurnal'           => $jurnal->id_jurnal,
                'tanggal'             => Carbon::parse($jurnal->tanggal)->translatedFormat('l, j F Y'),
                'jam_ke'              => $jurnal->jam_ke,
                'jam_mulai'           => substr($jurnal->jam_mulai ?? '07:00', 0, 5),
                'jam_selesai'         => substr($jurnal->jam_selesai ?? '08:30', 0, 5),
                'kelas'               => $jurnal->kelas->nama_kelas ?? '-',
                'guru'                => $jurnal->guru->nama_lengkap ?? '-',
                'mapel'               => $jurnal->mapel->nama_mapel ?? '-',
                'materi'              => $jurnal->materi,
                'catatan'             => $jurnal->catatan ?? '-',
                'status_guru'         => $jurnal->status_guru,
                'jumlah_hadir'        => $jurnal->jumlah_siswa_hadir,
                'jumlah_tidak_hadir'  => $jurnal->jumlah_siswa_tidak_hadir,
                'presensi'            => $jurnal->presensiSiswa->map(fn($p) => [
                    'nama_siswa'  => $p->siswa->nama_lengkap ?? 'Siswa',
                    'nis'         => $p->siswa->nis ?? '-',
                    'status'      => $p->status,
                    'keterangan'  => $p->keterangan ?? '-',
                ]),
            ]
        ]);
    }

    public function export(Request $request)
    {
        $tanggalInput    = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $selectedTanggal = Carbon::parse($tanggalInput)->format('Y-m-d');

        $jurnals = JurnalMengajar::with(['guru', 'kelas', 'mapel'])
            ->where('tanggal', $selectedTanggal)
            ->get();

        $csvData = "ID Jurnal;Tanggal;Jam Ke;Kelas;Guru;Materi;Jumlah Hadir;Jumlah Tidak Hadir;Status Guru\n";
        foreach ($jurnals as $j) {
            $csvData .= sprintf(
                "%d;%s;%s;%s;%s;\"%s\";%d;%d;%s\n",
                $j->id_jurnal,
                $j->tanggal->format('Y-m-d'),
                $j->jam_ke,
                $j->kelas->nama_kelas ?? '',
                $j->guru->nama_lengkap ?? '',
                str_replace('"', '""', $j->materi),
                $j->jumlah_siswa_hadir,
                $j->jumlah_siswa_tidak_hadir,
                $j->status_guru
            );
        }

        return Response::make($csvData, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Laporan_Jurnal_Absensi_' . $selectedTanggal . '.csv"',
        ]);
    }
}
