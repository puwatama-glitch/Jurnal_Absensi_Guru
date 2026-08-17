<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MasterGuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Guru::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama_lengkap', 'LIKE', "%{$search}%")
                       ->orWhere('nip', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status_aktif', (bool) $status);
            });

        $guruList = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $totalGuru  = Guru::count();
        $aktif      = Guru::where('status_aktif', true)->count();
        $nonAktif   = Guru::where('status_aktif', false)->count();
        $walikelas  = Kelas::whereNotNull('wali_kelas_id')->count();

        return view('admin.master.guru', compact(
            'guruList',
            'totalGuru', 'aktif', 'nonAktif', 'walikelas',
            'search', 'status'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'           => 'required|string|max:30|unique:guru,nip',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'email'         => 'nullable|email|unique:users,email',
            'status_aktif'  => 'boolean',
        ]);

        // Create User account if email provided
        $userId = null;
        if ($request->filled('email')) {
            $user = User::create([
                'name'     => $validated['nama_lengkap'],
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password', 'password')),
                'role'     => 'guru_mapel',
                'is_active'=> true,
            ]);
            $userId = $user->id;
        }

        Guru::create([
            'user_id'       => $userId,
            'nip'           => $validated['nip'],
            'nama_lengkap'  => $validated['nama_lengkap'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'no_hp'         => $validated['no_hp'] ?? null,
            'alamat'        => $validated['alamat'] ?? null,
            'status_aktif'  => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('admin.master.guru')->with('success', "Guru {$validated['nama_lengkap']} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nip'           => "required|string|max:30|unique:guru,nip,{$guru->id_guru},id_guru",
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'status_aktif'  => 'boolean',
        ]);

        $guru->update([
            'nip'           => $validated['nip'],
            'nama_lengkap'  => $validated['nama_lengkap'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'no_hp'         => $validated['no_hp'] ?? $guru->no_hp,
            'alamat'        => $validated['alamat'] ?? $guru->alamat,
            'status_aktif'  => $request->boolean('status_aktif', true),
        ]);

        // Update linked user name if exists
        if ($guru->user) {
            $guru->user->update(['name' => $validated['nama_lengkap']]);
        }

        return redirect()->route('admin.master.guru')->with('success', "Data guru {$guru->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $nama = $guru->nama_lengkap;

        if ($guru->user) {
            $guru->user->delete();
        }
        $guru->delete();

        return redirect()->route('admin.master.guru')->with('success', "Guru {$nama} berhasil dihapus (soft delete).");
    }
}
