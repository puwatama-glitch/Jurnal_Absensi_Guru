<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\WaliKelas;
use App\Models\GuruPiket;
use App\Models\GuruMapel;
use App\Models\Satpam;
use App\Models\KepalaSekolah;
use App\Models\Waka;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'nullable|string',
        ]);

        $loginInput = $credentials['username'];
        $password = $credentials['password'];
        $selectedRole = $request->input('role');
        $remember = $request->has('remember');

        // 1. Search user by email
        $user = User::where('email', $loginInput)->first();

        // 2. If not found by email, search across all separate role tables by NIP
        if (!$user) {
            $user = $this->findUserByNipInRoleTables($loginInput);
        }

        // 3. If user found and role selected, verify role match
        if ($user && $selectedRole && $selectedRole !== 'semua' && $user->role !== $selectedRole) {
            return back()->withErrors([
                'username' => 'Akun tidak memiliki hak akses sebagai ' . str_replace('_', ' ', strtoupper($selectedRole)) . '.',
            ])->onlyInput('username');
        }

        // 4. Attempt login
        if ($user && Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return $this->redirectUser(Auth::user());
        }

        return back()->withErrors([
            'username' => 'NIP, NISN, atau Email / Password tidak sesuai.',
        ])->onlyInput('username');
    }

    /**
     * Search for user by NIP across separate role tables.
     */
    protected function findUserByNipInRoleTables(string $nip): ?User
    {
        // Check admin
        $admin = Admin::where('nip', $nip)->first();
        if ($admin && $admin->user_id) return User::find($admin->user_id);

        // Check wali_kelas
        $wali = WaliKelas::where('nip', $nip)->first();
        if ($wali && $wali->user_id) return User::find($wali->user_id);

        // Check guru_piket
        $piket = GuruPiket::where('nip', $nip)->first();
        if ($piket && $piket->user_id) return User::find($piket->user_id);

        // Check guru_mapel
        $mapel = GuruMapel::where('nip', $nip)->first();
        if ($mapel && $mapel->user_id) return User::find($mapel->user_id);

        // Check satpam
        $satpam = Satpam::where('nip', $nip)->first();
        if ($satpam && $satpam->user_id) return User::find($satpam->user_id);

        // Check kepala_sekolah
        $kepsek = KepalaSekolah::where('nip', $nip)->first();
        if ($kepsek && $kepsek->user_id) return User::find($kepsek->user_id);

        // Check waka
        $waka = Waka::where('nip', $nip)->first();
        if ($waka && $waka->user_id) return User::find($waka->user_id);

        return null;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    protected function redirectUser($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'wali_kelas':
                return redirect()->route('jurnal.index');
            case 'guru_mapel':
                return redirect()->route('jurnal.create');
            case 'guru_piket':
            case 'kepala_sekolah':
            case 'waka':
            case 'satpam':
            default:
                return redirect()->route('jurnal.index');
        }
    }
}
