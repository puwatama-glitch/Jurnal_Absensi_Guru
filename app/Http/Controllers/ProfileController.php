<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profil.index', compact('user'));
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('admin.profil')
            ->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        if (!$request->hasFile('photo')) {
            return back()->withErrors(['photo' => 'Silakan pilih file foto terlebih dahulu.']);
        }

        $file = $request->file('photo');

        if (!$file->isValid()) {
            return back()->withErrors(['photo' => 'File foto tidak valid atau melebihi batas ukuran upload server.']);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {
            return back()->withErrors(['photo' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.']);
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return back()->withErrors(['photo' => 'Ukuran foto maksimal adalah 5MB.']);
        }

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $path = $file->store('profile-photos', 'public');
            $user->photo = $path;
            $user->save();

            return redirect()->route('admin.profil')
                ->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Gagal menyimpan foto: ' . $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profil')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
