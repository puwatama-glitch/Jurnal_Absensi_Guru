<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JurnalMengajar;

class JurnalMengajarController extends Controller
{
    /**
     * Menampilkan semua data jurnal.
     */
    public function index()
    {
        $jurnal = JurnalMengajar::all();

        return view('jurnal.index', compact('jurnal'));
    }

    /**
     * Menampilkan form tambah data.
     */
    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();

        return view('jurnal.create', compact('guru', 'kelas'));
    }

    /**
     * Menyimpan data ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required',
            'id_kelas' => 'required',
            'tanggal' => 'required',
            'jam_ke' => 'required|numeric',
            'materi' => 'required',
            'jumlah_siswa_hadir' => 'required|numeric',
            'jumlah_siswa_tidak_hadir' => 'required|numeric',
            'status_guru' => 'required',
            'catatan' => 'nullable'
        ]);

        JurnalMengajar::create($request->all());

        return redirect()->route('jurnal.create')
    ->with('success', 'Jurnal berhasil disimpan.');
    }
    /**
     * Menampilkan detail data.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Mengupdate data.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Menghapus data.
     */
    public function destroy(string $id)
    {
        //
    }
}