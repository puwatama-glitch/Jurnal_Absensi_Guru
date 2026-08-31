<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — SMKN 1 BOYOLANGU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: #f2f4f8;
            min-height: 100vh;
        }

        /* ── Top Navigation Bar ── */
        .topbar {
            background: linear-gradient(135deg, #3d56b2 0%, #2b3a8c 100%);
            padding: 0 40px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(43,67,185,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.25);
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
            transform: translateX(-2px);
        }

        .topbar-title {
            display: flex;
            flex-direction: column;
        }
        .topbar-title h1 {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .topbar-title p {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            font-weight: 600;
        }

        /* ── Main Content ── */
        .page-content {
            max-width: 1000px;
            margin: 36px auto;
            padding: 0 24px 60px;
        }

        .profil-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* ── Avatar Card ── */
        .avatar-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            text-align: center;
            position: sticky;
            top: 94px;
        }

        /* Avatar with upload overlay */
        .avatar-wrap {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 16px;
            cursor: pointer;
        }

        .avatar-img-circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e0e7ff;
            box-shadow: 0 8px 24px rgba(43,67,185,0.2);
            display: block;
        }

        .avatar-initials {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%);
            color: #ffffff;
            font-size: 38px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(43,67,185,0.25);
            letter-spacing: -1px;
        }

        .avatar-upload-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(15,23,42,0.55);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            gap: 4px;
        }
        .avatar-upload-overlay i { font-size: 20px; }

        .avatar-wrap:hover .avatar-upload-overlay { opacity: 1; }

        #photoInput { display: none; }

        .avatar-name { font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .avatar-email { font-size: 13px; color: #64748b; font-weight: 600; margin-bottom: 16px; }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #3730a3;
            font-size: 11.5px;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 20px;
            border: 1px solid #c7d2fe;
            margin-bottom: 22px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .avatar-divider { height: 1px; background: #f1f5f9; margin: 16px 0; }

        .avatar-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #475569;
            font-weight: 600;
            padding: 7px 0;
        }
        .avatar-stat .stat-label { display: flex; align-items: center; gap: 8px; }
        .avatar-stat .stat-label i { width: 16px; text-align: center; }
        .avatar-stat .stat-val { color: #0f172a; font-weight: 700; font-size: 12.5px; }

        /* Upload progress bar */
        .upload-progress {
            display: none;
            margin-top: 14px;
        }
        .upload-progress-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        .upload-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2b43b9, #6366f1);
            border-radius: 4px;
            width: 0%;
            transition: width 0.4s ease;
            animation: progressAnim 1.5s ease infinite;
        }
        @keyframes progressAnim {
            0% { width: 20%; } 50% { width: 80%; } 100% { width: 20%; }
        }
        .upload-label { font-size: 11.5px; color: #64748b; font-weight: 600; margin-bottom: 6px; }

        /* ── Right Column Forms ── */
        .form-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .form-card-hdr {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-card-icon {
            width: 42px; height: 42px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .form-card-icon.indigo { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
        .form-card-icon.slate  { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #475569; }

        .form-card-title-txt h3 { font-size: 16px; font-weight: 800; color: #0f172a; }
        .form-card-title-txt p  { font-size: 12.5px; color: #64748b; font-weight: 500; margin-top: 2px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }
        .form-row.full { grid-template-columns: 1fr; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.6px; }

        .form-input {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
            width: 100%;
        }
        .form-input:focus {
            border-color: #2b43b9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(43,67,185,0.1);
        }
        .form-input:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            background: #f1f5f9;
        }
        .form-input.is-invalid { border-color: #ef4444; background: #fff5f5; }
        .invalid-msg { font-size: 12px; font-weight: 600; color: #ef4444; margin-top: 3px; }

        .input-pw-wrap { position: relative; }
        .input-pw-wrap .form-input { padding-right: 44px; }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 14px; cursor: pointer;
            transition: color 0.15s ease;
        }
        .toggle-pw:hover { color: #2b43b9; }

        .warning-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #92400e;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(43,67,185,0.25);
            font-family: inherit;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(43,67,185,0.35); }

        /* Toast Notification */
        .toast-container { position: fixed; top: 24px; right: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
        .toast {
            display: flex; align-items: center; gap: 12px;
            background: #ffffff; border-radius: 14px; padding: 14px 20px;
            box-shadow: 0 10px 36px rgba(0,0,0,0.13);
            border-left: 4px solid #10b981;
            font-size: 14px; font-weight: 600; color: #0f172a;
            min-width: 300px;
            animation: slideIn .3s ease;
        }
        .toast.error { border-left-color: #ef4444; }
        .toast i.ok { color: #10b981; font-size: 18px; }
        .toast i.err { color: #ef4444; font-size: 18px; }
        @keyframes slideIn { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }
        /* ── Animated Logout Confirmation Modal ── */
        .logout-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .logout-modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }
        .logout-modal-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px 28px 28px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0;
            transform: scale(0.85) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .logout-modal-backdrop.show .logout-modal-card {
            transform: scale(1) translateY(0);
        }
        .logout-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2 0%, #fecdd3 100%);
            color: #ef4444;
            font-size: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
            animation: logoutPulse 2s infinite;
        }
        @keyframes logoutPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .logout-modal-title { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        .logout-modal-desc { font-size: 13.5px; color: #64748b; font-weight: 500; line-height: 1.5; margin-bottom: 24px; }
        .logout-modal-actions { display: flex; gap: 12px; }
        .btn-cancel-logout {
            flex: 1; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;
            border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-cancel-logout:hover { background: #e2e8f0; color: #0f172a; }
        .btn-confirm-logout {
            flex: 1; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff;
            border: none; border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }
        .btn-confirm-logout:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4); }

        .btn-logout-topbar {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-logout-topbar:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        /* ── Mobile Responsive Media Queries ── */
        @media (max-width: 900px) {
            .topbar { padding: 0 16px; height: 62px; }
            .topbar-right { display: none; }
            .page-content { padding: 0 14px 40px; margin: 20px auto; }
            .profil-grid { grid-template-columns: 1fr; gap: 18px; }
            .card-section { padding: 20px 16px; border-radius: 18px; }
            .form-grid { grid-template-columns: 1fr; }
            .toast { min-width: unset; width: calc(100vw - 32px); left: 16px; right: 16px; }
        }

        @media (max-width: 480px) {
            .topbar-title h1 { font-size: 15px; }
            .btn-back { padding: 7px 12px; font-size: 12px; }
            .btn-submit { width: 100%; justify-content: center; }
            .logout-modal-card { margin: 0 12px; padding: 24px 18px; max-width: calc(100vw - 24px); }
        }
    </style>
</head>
<body>

{{-- Logout Form --}}
<form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none;">
    @csrf
</form>

{{-- Animated Logout Modal --}}
<div class="logout-modal-backdrop" id="logoutModal" onclick="if(event.target === this) closeLogoutModal()">
    <div class="logout-modal-card">
        <div class="logout-icon-wrap">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>
        <h3 class="logout-modal-title">Konfirmasi Keluar</h3>
        <p class="logout-modal-desc">Apakah Anda yakin ingin keluar dari aplikasi Jurnal Absensi SMKN 1 Boyolangu?</p>
        <div class="logout-modal-actions">
            <button type="button" class="btn-cancel-logout" onclick="closeLogoutModal()">Batal</button>
            <button type="button" class="btn-confirm-logout" onclick="document.getElementById('logout-form').submit()">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Ya, Keluar
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="toast-container" id="toastContainer"></div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ session('success') }}', 'ok');
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ $errors->first() }}', 'err');
    });
</script>
@endif

{{-- Top Navigation Bar --}}
<nav class="topbar">
    <div class="topbar-left">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('admin.dashboard') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <div class="topbar-title">
            <h1>Profil Saya</h1>
            <p>Kelola informasi akun dan keamanan login Anda</p>
        </div>
    </div>
    <div class="topbar-right">
        <i class="fa-solid fa-shield-halved"></i>
        {{ strtoupper(str_replace('_', ' ', $user->role)) }}
    </div>
</nav>

{{-- Main Content --}}
<div class="page-content">
    <div class="profil-grid">

        {{-- ═══ Kolom Kiri: Avatar Card ═══ --}}
        <div class="avatar-card">

            {{-- Photo Upload Form --}}
            <form id="photoForm" action="{{ route('admin.profil.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="photoInput" name="photo" accept="image/*">
            </form>

            {{-- Avatar dengan overlay upload --}}
            <div class="avatar-wrap" onclick="document.getElementById('photoInput').click()" title="Klik untuk ganti foto profil">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" alt="Foto Profil" class="avatar-img-circle">
                @else
                    <div class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                @endif
                <div class="avatar-upload-overlay">
                    <i class="fa-solid fa-camera"></i>
                    <span>Ganti Foto</span>
                </div>
            </div>

            <button type="button" onclick="document.getElementById('photoInput').click()" style="background:#eaeff8;color:#2b43b9;border:1px solid #c7d2fe;border-radius:10px;padding:7px 16px;font-size:12.5px;font-weight:700;cursor:pointer;margin-bottom:14px;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s ease;">
                <i class="fa-solid fa-camera"></i> Ubah Foto Profil
            </button>

            {{-- Upload progress --}}
            <div class="upload-progress" id="uploadProgress">
                <div class="upload-label">Mengupload foto...</div>
                <div class="upload-progress-bar">
                    <div class="upload-progress-fill"></div>
                </div>
            </div>

            <div class="avatar-name">{{ $user->name }}</div>
            <div class="avatar-email">{{ $user->email }}</div>

            <div class="role-badge">
                <i class="fa-solid fa-shield-halved"></i>
                {{ str_replace('_', ' ', ucwords($user->role)) }}
            </div>

            <div class="avatar-divider"></div>

            <div class="avatar-stat">
                <div class="stat-label">
                    <i class="fa-solid fa-calendar" style="color:#2b43b9;"></i>
                    <span>Bergabung</span>
                </div>
                <span class="stat-val">{{ $user->created_at->format('d M Y') }}</span>
            </div>

            <div class="avatar-stat">
                <div class="stat-label">
                    <i class="fa-solid fa-circle-dot" style="color:{{ $user->is_active ? '#10b981' : '#ef4444' }};"></i>
                    <span>Status Akun</span>
                </div>
                <span class="stat-val" style="color:{{ $user->is_active ? '#10b981' : '#ef4444' }};">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="avatar-stat">
                <div class="stat-label">
                    <i class="fa-solid fa-pen" style="color:#f59e0b;"></i>
                    <span>Diperbarui</span>
                </div>
                <span class="stat-val">{{ $user->updated_at->format('d M Y') }}</span>
            </div>

        </div>

        {{-- ═══ Kolom Kanan: Form Cards ═══ --}}
        <div>

            {{-- Form: Informasi Akun --}}
            <div class="form-card">
                <div class="form-card-hdr">
                    <div class="form-card-icon indigo">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div class="form-card-title-txt">
                        <h3>Informasi Akun</h3>
                        <p>Perbarui nama lengkap dan alamat email akun Anda.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profil.info') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   placeholder="Nama lengkap" required>
                            @error('name')
                                <span class="invalid-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="email@contoh.com" required>
                            @error('email')
                                <span class="invalid-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row full">
                        <div class="form-group">
                            <label class="form-label">Role / Jabatan</label>
                            <input type="text" value="{{ str_replace('_', ' ', ucwords($user->role)) }}"
                                   class="form-input" disabled>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Form: Ganti Password --}}
            <div class="form-card">
                <div class="form-card-hdr">
                    <div class="form-card-icon slate">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="form-card-title-txt">
                        <h3>Keamanan Akun</h3>
                        <p>Ubah password login secara berkala untuk menjaga keamanan akun.</p>
                    </div>
                </div>

                <div class="warning-box">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol — minimal 8 karakter.
                </div>

                <form action="{{ route('admin.profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-row full">
                        <div class="form-group">
                            <label class="form-label">Password Saat Ini</label>
                            <div class="input-pw-wrap">
                                <input type="password" name="current_password" id="pw0"
                                       class="form-input {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan password lama" required>
                                <i class="fa-regular fa-eye toggle-pw" onclick="togglePwd('pw0', this)"></i>
                            </div>
                            @error('current_password')
                                <span class="invalid-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <div class="input-pw-wrap">
                                <input type="password" name="password" id="pw1"
                                       class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Min. 8 karakter" required>
                                <i class="fa-regular fa-eye toggle-pw" onclick="togglePwd('pw1', this)"></i>
                            </div>
                            @error('password')
                                <span class="invalid-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-pw-wrap">
                                <input type="password" name="password_confirmation" id="pw2"
                                       class="form-input" placeholder="Ulangi password baru" required>
                                <i class="fa-regular fa-eye toggle-pw" onclick="togglePwd('pw2', this)"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-key"></i> Perbarui Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePwd(id, icon) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Auto-submit photo on file change
    document.getElementById('photoInput').addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                showToast('Ukuran file terlalu besar. Maksimal 2MB.', 'err');
                this.value = '';
                return;
            }
            document.getElementById('uploadProgress').style.display = 'block';
            document.getElementById('photoForm').submit();
        }
    });

    // Toast notification
    function showToast(msg, type) {
        const tc = document.getElementById('toastContainer');
        const t  = document.createElement('div');
        t.className = type === 'err' ? 'toast error' : 'toast';
        t.innerHTML = type === 'err'
            ? `<i class="fa-solid fa-circle-xmark err"></i><span>${msg}</span>`
            : `<i class="fa-solid fa-circle-check ok"></i><span>${msg}</span>`;
        tc.appendChild(t);
        setTimeout(() => { t.style.transition = 'opacity .4s'; t.style.opacity = '0'; }, 3500);
        setTimeout(() => t.remove(), 4000);
    }
    // Logout modal handlers
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.add('show');
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.remove('show');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLogoutModal();
    });
</script>
</body>
</html>
