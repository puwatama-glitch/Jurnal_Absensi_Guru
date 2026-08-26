<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $search       = $request->input('search');
        $hari         = $request->input('hari');
        $kelas        = $request->input('kelas');
        $guru         = $request->input('guru');
        $tahunAjaranId = $request->input('tahun_ajaran');

        // Active Tahun Ajaran
        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();

        // If no filter selected, default to active tahun ajaran (if exists)
        if ($tahunAjaranId === null && $tahunAjaranAktif) {
            $selectedTahunAjaran = $tahunAjaranAktif->id;
        } elseif ($tahunAjaranId === 'all') {
            $selectedTahunAjaran = 'all';
        } else {
            $selectedTahunAjaran = $tahunAjaranId;
        }

        $query = JadwalPelajaran::with(['guru', 'kelas', 'mapel', 'tahunAjaran'])
            ->when($selectedTahunAjaran && $selectedTahunAjaran !== 'all', function ($q) use ($selectedTahunAjaran) {
                $q->where('id_tahun_ajaran', $selectedTahunAjaran);
            })
            ->when($hari && $hari !== 'all', function ($q) use ($hari) {
                $q->where('hari', $hari);
            })
            ->when($kelas, function ($q) use ($kelas) {
                $q->where('id_kelas', $kelas);
            })
            ->when($guru, function ($q) use ($guru) {
                $q->where('id_guru', $guru);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->whereHas('mapel', function ($q3) use ($search) {
                        $q3->where('nama_mapel', 'LIKE', "%{$search}%")
                           ->orWhere('kode_mapel', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('guru', function ($q3) use ($search) {
                        $q3->where('nama_lengkap', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('kelas', function ($q3) use ($search) {
                        $q3->where('nama_kelas', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('tahunAjaran', function ($q3) use ($search) {
                        $q3->where('nama', 'LIKE', "%{$search}%");
                    });
                });
            });

        $jadwalList = $query->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_ke')
            ->paginate(20)
            ->withQueryString();

        // Statistics
        $baseQuery = JadwalPelajaran::query();
        if ($selectedTahunAjaran && $selectedTahunAjaran !== 'all') {
            $baseQuery->where('id_tahun_ajaran', $selectedTahunAjaran);
        }

        $totalJadwal     = (clone $baseQuery)->count();
        $totalGuruAktif  = (clone $baseQuery)->distinct('id_guru')->count('id_guru');
        $totalKelasAktif = (clone $baseQuery)->distinct('id_kelas')->count('id_kelas');
        $totalMapelAktif = (clone $baseQuery)->distinct('id_mapel')->count('id_mapel');

        // Dropdown & suggestion data
        $guruList         = Guru::where('status_aktif', true)->orderBy('nama_lengkap')->get();
        $kelasList        = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList        = Mapel::orderBy('nama_mapel')->get();
        $tahunAjaranList  = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('id')->get();
        $existingTahunList = TahunAjaran::select('nama')->distinct()->pluck('nama')->toArray();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal.index', compact(
            'jadwalList',
            'totalJadwal', 'totalGuruAktif', 'totalKelasAktif', 'totalMapelAktif',
            'guruList', 'kelasList', 'mapelList', 'tahunAjaranList', 'tahunAjaranAktif',
            'existingTahunList', 'hariList',
            'search', 'hari', 'kelas', 'guru', 'selectedTahunAjaran'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:20',
            'semester'          => 'required|in:Ganjil,Genap',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_dari'          => 'required|integer|min:1|max:12',
            'jam_sampai'        => 'required|integer|min:1|max:12|gte:jam_dari',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
            'id_mapel'          => 'required|exists:mapel,id_mapel',
            'id_guru'           => 'required|exists:guru,id_guru',
            'id_kelas'          => 'required|exists:kelas,id_kelas',
        ]);

        // Validate max 4 jam range
        if (($validated['jam_sampai'] - $validated['jam_dari'] + 1) > 4) {
            return redirect()->route('admin.jadwal')
                ->with('error', 'Maksimal pemilihan jam adalah 4 jam berturut-turut.');
        }

        $namaTahun = trim($validated['nama_tahun_ajaran']);
        $semester  = $validated['semester'];
        $tahunAjaran   = $this->resolveTahunAjaran($namaTahun, $semester);
        $idTahunAjaran = $tahunAjaran->id;

        $jamRange = range((int)$validated['jam_dari'], (int)$validated['jam_sampai']);
        $errors   = [];

        foreach ($jamRange as $jam) {
            // Check kelas conflict for this slot
            $kelasConflict = JadwalPelajaran::where('id_tahun_ajaran', $idTahunAjaran)
                ->where('hari', $validated['hari'])
                ->where('jam_ke', $jam)
                ->where('id_kelas', $validated['id_kelas'])
                ->exists();

            if ($kelasConflict) {
                $errors[] = "Kelas sudah terisi di jam ke-{$jam}";
                continue;
            }

            // Check guru conflict
            $guruConflict = JadwalPelajaran::where('id_tahun_ajaran', $idTahunAjaran)
                ->where('hari', $validated['hari'])
                ->where('jam_ke', $jam)
                ->where('id_guru', $validated['id_guru'])
                ->exists();

            if ($guruConflict) {
                $guru = Guru::find($validated['id_guru']);
                $errors[] = "Guru {$guru->nama_lengkap} sudah mengajar di jam ke-{$jam}";
                continue;
            }

            JadwalPelajaran::create([
                'id_tahun_ajaran' => $idTahunAjaran,
                'hari'            => $validated['hari'],
                'jam_ke'          => $jam,
                'jam_mulai'       => $validated['jam_mulai'],
                'jam_selesai'     => $validated['jam_selesai'],
                'id_mapel'        => $validated['id_mapel'],
                'id_guru'         => $validated['id_guru'],
                'id_kelas'        => $validated['id_kelas'],
            ]);
        }

        $totalJam = count($jamRange);
        $sukses   = $totalJam - count($errors);

        if (!empty($errors)) {
            $pesanError = implode('; ', $errors);
            return redirect()->route('admin.jadwal')
                ->with('error', "Sebagian jadwal gagal disimpan: {$pesanError}. Berhasil: {$sukses}/{$totalJam} jam.");
        }

        $jamLabel = $validated['jam_dari'] === $validated['jam_sampai']
            ? "Jam ke-{$validated['jam_dari']}"
            : "Jam ke-{$validated['jam_dari']} s/d {$validated['jam_sampai']} ({$totalJam} slot)";

        return redirect()->route('admin.jadwal')
            ->with('success', "Jadwal {$namaTahun} ({$semester}) — {$jamLabel} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);

        $validated = $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:20',
            'semester'          => 'required|in:Ganjil,Genap',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_dari'          => 'required|integer|min:1|max:12',
            'jam_sampai'        => 'required|integer|min:1|max:12|gte:jam_dari',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
            'id_mapel'          => 'required|exists:mapel,id_mapel',
            'id_guru'           => 'required|exists:guru,id_guru',
            'id_kelas'          => 'required|exists:kelas,id_kelas',
        ]);

        if (($validated['jam_sampai'] - $validated['jam_dari'] + 1) > 4) {
            return redirect()->route('admin.jadwal')
                ->with('error', 'Maksimal pemilihan jam adalah 4 jam berturut-turut.');
        }

        $namaTahun     = trim($validated['nama_tahun_ajaran']);
        $semester      = $validated['semester'];
        $tahunAjaran   = $this->resolveTahunAjaran($namaTahun, $semester);
        $idTahunAjaran = $tahunAjaran->id;

        // Update only this single record (jam ke berubah = update satu slot saja)
        // Check conflicts excluding current
        $jam = (int)$validated['jam_dari']; // for single-edit, use jam_dari as the target slot

        $kelasConflict = JadwalPelajaran::where('id_tahun_ajaran', $idTahunAjaran)
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $jam)
            ->where('id_kelas', $validated['id_kelas'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)
            ->exists();

        if ($kelasConflict) {
            return redirect()->route('admin.jadwal')
                ->with('error', "Jadwal untuk kelas ini pada hari {$validated['hari']} jam ke-{$jam} ({$namaTahun} - {$semester}) sudah terisi.");
        }

        $guruConflict = JadwalPelajaran::where('id_tahun_ajaran', $idTahunAjaran)
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $jam)
            ->where('id_guru', $validated['id_guru'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)
            ->exists();

        if ($guruConflict) {
            $guru = Guru::find($validated['id_guru']);
            return redirect()->route('admin.jadwal')
                ->with('error', "Guru {$guru->nama_lengkap} sudah memiliki jadwal mengajar pada hari {$validated['hari']} jam ke-{$jam} ({$namaTahun} - {$semester}).");
        }

        $jadwal->update([
            'id_tahun_ajaran' => $idTahunAjaran,
            'hari'            => $validated['hari'],
            'jam_ke'          => $jam,
            'jam_mulai'       => $validated['jam_mulai'],
            'jam_selesai'     => $validated['jam_selesai'],
            'id_mapel'        => $validated['id_mapel'],
            'id_guru'         => $validated['id_guru'],
            'id_kelas'        => $validated['id_kelas'],
        ]);

        return redirect()->route('admin.jadwal')
            ->with('success', "Jadwal pelajaran TA {$namaTahun} ({$semester}) berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])->findOrFail($id);

        // Check if jadwal has related jurnal
        if ($jadwal->jurnalMengajar()->count() > 0) {
            return redirect()->route('admin.jadwal')
                ->with('error', "Jadwal ini tidak dapat dihapus karena sudah memiliki {$jadwal->jurnalMengajar()->count()} catatan jurnal mengajar.");
        }

        $info = "{$jadwal->mapel->nama_mapel} - {$jadwal->kelas->nama_kelas} ({$jadwal->hari}, Jam ke-{$jadwal->jam_ke})";
        $jadwal->delete();

        return redirect()->route('admin.jadwal')
            ->with('success', "Jadwal {$info} berhasil dihapus.");
    }

    /**
     * Set or manually create an active Tahun Ajaran
     */
    public function setTahunAjaran(Request $request)
    {
        $action = $request->input('mode', 'manual');

        if ($action === 'select' && $request->filled('id_tahun_ajaran')) {
            $target = TahunAjaran::findOrFail($request->input('id_tahun_ajaran'));
        } else {
            $validated = $request->validate([
                'nama_tahun_ajaran' => 'required|string|max:20',
                'semester'          => 'required|in:Ganjil,Genap',
            ]);

            $target = $this->resolveTahunAjaran(trim($validated['nama_tahun_ajaran']), $validated['semester']);
        }

        // Set all others to false, set target to true
        TahunAjaran::query()->update(['is_aktif' => false]);
        $target->is_aktif = true;
        $target->save();

        return redirect()->route('admin.jadwal', ['tahun_ajaran' => $target->id])
            ->with('success', "Tahun Ajaran {$target->nama} — Semester {$target->semester} berhasil ditetapkan sebagai Tahun Ajaran Aktif.");
    }

    /**
     * Helper to find or create TahunAjaran model
     */
    private function resolveTahunAjaran(string $nama, string $semester): TahunAjaran
    {
        $ta = TahunAjaran::where('nama', $nama)->where('semester', $semester)->first();

        if ($ta) {
            return $ta;
        }

        // Generate start/end dates
        $yearParts = explode('/', $nama);
        $startYear = isset($yearParts[0]) && is_numeric($yearParts[0]) ? (int)$yearParts[0] : now()->year;
        $endYear   = isset($yearParts[1]) && is_numeric($yearParts[1]) ? (int)$yearParts[1] : ($startYear + 1);

        if ($semester === 'Ganjil') {
            $tanggalMulai   = "{$startYear}-07-15";
            $tanggalSelesai = "{$startYear}-12-31";
        } else {
            $tanggalMulai   = "{$endYear}-01-02";
            $tanggalSelesai = "{$endYear}-06-30";
        }

        $isAktif = TahunAjaran::where('is_aktif', true)->doesntExist();

        return TahunAjaran::create([
            'nama'            => $nama,
            'semester'        => $semester,
            'tanggal_mulai'   => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'is_aktif'        => $isAktif,
        ]);
    }
}
