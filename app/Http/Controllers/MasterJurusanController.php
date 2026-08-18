<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Kelas;

class MasterJurusanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Jurusan::withCount('kelas')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('kode_jurusan', 'LIKE', "%{$search}%")
                       ->orWhere('nama_jurusan', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status_aktif', (bool) $status);
            });

        $jurusanList = $query->orderBy('kode_jurusan')->paginate(15)->withQueryString();

        $totalJurusan = Jurusan::count();
        $aktif        = Jurusan::where('status_aktif', true)->count();
        $nonAktif     = Jurusan::where('status_aktif', false)->count();
        $totalKelas   = Kelas::count();

        return view('admin.master.jurusan', compact(
            'jurusanList',
            'totalJurusan', 'aktif', 'nonAktif', 'totalKelas',
            'search', 'status'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jurusan'  => 'required|string|max:20|unique:jurusan,kode_jurusan',
            'nama_jurusan'  => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'status_aktif'  => 'boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);
        Jurusan::create($validated);

        return redirect()->route('admin.master.jurusan')->with('success', "Jurusan {$validated['kode_jurusan']} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'kode_jurusan'  => "required|string|max:20|unique:jurusan,kode_jurusan,{$jurusan->id_jurusan},id_jurusan",
            'nama_jurusan'  => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'status_aktif'  => 'boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);
        $jurusan->update($validated);

        Kelas::where('id_jurusan', $jurusan->id_jurusan)
            ->update(['jurusan' => $jurusan->kode_jurusan]);

        return redirect()->route('admin.master.jurusan')->with('success', "Data jurusan {$jurusan->kode_jurusan} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::withCount('kelas')->findOrFail($id);

        if ($jurusan->kelas_count > 0) {
            return redirect()->route('admin.master.jurusan')
                ->with('error', "Jurusan {$jurusan->kode_jurusan} tidak dapat dihapus karena masih memiliki {$jurusan->kelas_count} kelas.");
        }

        $kode = $jurusan->kode_jurusan;
        $jurusan->delete();

        return redirect()->route('admin.master.jurusan')->with('success', "Jurusan {$kode} berhasil dihapus.");
    }
}
