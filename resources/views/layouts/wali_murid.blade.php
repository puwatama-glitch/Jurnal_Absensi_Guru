<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Wali Murid - Jurnal Absensi SMKN 1 BOYOLANGU')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #f2f4f8; color: #2b3674; display: flex; min-height: 100vh; }
        .sidebar {
            width: 260px; background-color: #f6f7fb; border-right: 1px solid #e3e8f0;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 28px 20px; flex-shrink: 0;
        }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; }
        .sidebar-logo-img { width: 46px; height: 46px; object-fit: contain; }
        .sidebar-brand-text { display: flex; flex-direction: column; }
        .sidebar-title { font-weight: 800; font-size: 15px; line-height: 1.2; color: #1b2559; }
        .sidebar-subtitle { font-size: 11.5px; color: #707e94; font-weight: 600; margin-top: 2px; }
        .sidebar-menu { list-style: none; display: flex; flex-direction: column; gap: 4px; margin-top: 10px; }
        .sidebar-category-header { font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; padding: 14px 14px 4px 14px; }
        .sidebar-category-header:first-child { padding-top: 4px; }
        .sidebar-menu li a {
            display: flex; align-items: center; gap: 14px; padding: 12px 16px;
            border-radius: 12px; color: #6b7a99; text-decoration: none;
            font-weight: 600; font-size: 14px; transition: all 0.2s ease; position: relative;
        }
        .sidebar-menu li.active a { color: #10b981; background-color: #ffffff; box-shadow: 0 4px 12px rgba(16,185,129,0.1); font-weight: 700; }
        .sidebar-menu li.active a::before { content: ''; position: absolute; right: -20px; top: 50%; transform: translateY(-50%); width: 4px; height: 24px; background-color: #10b981; border-radius: 4px 0 0 4px; }
        .sidebar-menu li a:hover:not(.active) { background-color: #d1fae5; color: #047857; }
        .sidebar-menu li a i { font-size: 18px; width: 22px; text-align: center; }
        .sidebar-bottom { border-top: 1px solid #e3e8f0; padding-top: 20px; margin-top: auto; display: flex; flex-direction: column; gap: 8px; }
        .sidebar-bottom a { display: flex; align-items: center; gap: 14px; padding: 10px 16px; border-radius: 12px; color: #6b7a99; text-decoration: none; font-weight: 600; font-size: 14px; }
        .sidebar-bottom a.logout { color: #e63946; }
        .sidebar-bottom a:hover { background-color: #d1fae5; }
        .sidebar-bottom a.logout:hover { background-color: #ffeef0; }
        .main-wrapper { flex: 1; padding: 28px 32px; overflow-x: hidden; }
        .top-header-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            border-radius: 20px; padding: 24px 28px; margin-bottom: 28px;
            display: flex; align-items: center; justify-content: space-between;
            color: white; position: relative; overflow: hidden;
        }
        .top-header-banner::before { content: ''; position: absolute; top: -40px; right: -40px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,0.08); }
        .header-title-box h1 { font-size: 26px; font-weight: 800; }
        .header-title-box p { font-size: 14px; opacity: 0.85; margin-top: 4px; }
        .header-user-nav { display: flex; align-items: center; gap: 16px; }
        .profile-dropdown-wrap { position: relative; }
        .user-profile-badge { display: flex; align-items: center; gap: 10px; padding: 8px 14px; border-radius: 14px; background: rgba(255,255,255,0.15); cursor: pointer; border: 1px solid rgba(255,255,255,0.25); transition: all 0.2s; }
        .user-profile-badge:hover { background: rgba(255,255,255,0.22); }
        .avatar-img { width: 36px; height: 36px; border-radius: 10px; object-fit: cover; }
        .user-info-text { display: flex; flex-direction: column; }
        .user-name { font-weight: 700; font-size: 13.5px; color: white; }
        .user-email { font-size: 11px; color: rgba(255,255,255,0.75); }
        .profile-dropdown { display: none; position: absolute; top: calc(100% + 10px); right: 0; width: 260px; background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 9999; border: 1px solid #f1f5f9; overflow: hidden; }
        .profile-dropdown.open { display: block; }
        .dropdown-header { padding: 16px 18px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .d-name { font-weight: 800; font-size: 14px; color: #0f172a; }
        .d-email { font-size: 12px; color: #64748b; margin-top: 2px; }
        .d-role { display: inline-block; margin-top: 6px; font-size: 10.5px; font-weight: 700; background: #d1fae5; color: #047857; padding: 2px 8px; border-radius: 6px; }
        .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 12px 18px; color: #475569; text-decoration: none; font-size: 13.5px; font-weight: 600; transition: background 0.15s; }
        .dropdown-item:hover { background: #f8fafc; color: #0f172a; }
        .dropdown-item.danger { color: #ef4444; }
        .dropdown-item.danger:hover { background: #fef2f2; }
        .dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }

        /* Student info card */
        .siswa-info-card {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0; border-radius: 16px;
            padding: 18px 22px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 18px;
        }
        .siswa-avatar {
            width: 56px; height: 56px; border-radius: 16px; background: #10b981;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: white; font-weight: 800; flex-shrink: 0;
        }
        .siswa-detail { flex: 1; }
        .siswa-name { font-size: 16px; font-weight: 800; color: #064e3b; }
        .siswa-meta { font-size: 12.5px; color: #047857; margin-top: 2px; }

        .alert-box { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-radius: 14px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600; }
        .alert-box.success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-box.danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .logout-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.55); z-index: 10000; align-items: center; justify-content: center; }
        .logout-modal-overlay.open { display: flex; }
        .logout-modal-card { background: white; border-radius: 24px; padding: 32px 28px 28px; max-width: 380px; width: 90%; text-align: center; }
        .logout-modal-icon { width: 60px; height: 60px; border-radius: 18px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px auto; }
        .logout-modal-title { font-size: 18px; font-weight: 800; color: #1b2559; margin-bottom: 6px; }
        .logout-modal-desc { font-size: 13.5px; color: #6b7a99; margin-bottom: 22px; }
        .logout-modal-actions { display: flex; gap: 10px; }
        .btn-modal { flex: 1; padding: 10px 16px; border-radius: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s ease; }
        .btn-modal.cancel { background: #f1f5f9; color: #475569; }
        .btn-modal.confirm { background: #ef4444; color: white; }
        .mobile-topbar { display: none; background: #ffffff; border-bottom: 1px solid #e2e8f0; height: 62px; padding: 0 16px; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 9980; }
        .mobile-topbar-left { display: flex; align-items: center; gap: 12px; }
        .btn-sidebar-hamburger { width: 40px; height: 40px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; }
        .mobile-brand { display: flex; align-items: center; gap: 8px; }
        .mobile-logo-img { width: 32px; height: 32px; object-fit: contain; }
        .mobile-brand-title { font-size: 15px; font-weight: 800; color: #1b2559; }
        .sidebar-header-mobile { display: none; justify-content: flex-end; margin-bottom: 8px; }
        .btn-close-sidebar { width: 34px; height: 34px; border-radius: 10px; border: none; background: #f1f5f9; color: #64748b; font-size: 16px; cursor: pointer; }
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9989; }
        .sidebar-backdrop.open { display: block; }
        .table-responsive-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        @media (max-width: 1024px) {
            .mobile-topbar { display: flex; }
            .sidebar { position: fixed; top: 0; left: 0; height: 100vh; z-index: 9990; transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 20px 40px rgba(0,0,0,0.25); background: #ffffff; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-header-mobile { display: flex; }
            .main-wrapper { padding: 16px 14px; width: 100%; overflow-x: hidden; }
            .top-header-banner { padding: 18px 20px; border-radius: 16px; flex-direction: column; align-items: flex-start; gap: 14px; margin-bottom: 18px; }
            .header-title-box h1 { font-size: 22px; }
            .header-user-nav { width: 100%; justify-content: space-between; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.18); }
        }
        @media (max-width: 768px) { .kpi-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; } }
        @media (max-width: 480px) { .kpi-grid { grid-template-columns: 1fr !important; } .siswa-info-card { flex-direction: column; text-align: center; } }
    </style>
    @yield('styles')
</head>
<body>
    <div class="mobile-topbar">
        <div class="mobile-topbar-left">
            <button type="button" class="btn-sidebar-hamburger" onclick="toggleMobileSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="mobile-brand">
                <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="mobile-logo-img">
                <span class="mobile-brand-title">PORTAL SISWA</span>
            </div>
        </div>
    </div>
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>
    <aside class="sidebar">
        <div>
            <div class="sidebar-header-mobile">
                <button type="button" class="btn-close-sidebar" onclick="closeMobileSidebar()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="sidebar-brand">
                <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="sidebar-logo-img">
                <div class="sidebar-brand-text">
                    <span class="sidebar-title">JURNAL<br>ABSENSI</span>
                    <span class="sidebar-subtitle">SMKN 1 BOYOLANGU</span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-category-header">Menu Utama</li>
                <li class="{{ request()->routeIs('wali-murid.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('wali-murid.dashboard') }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <li class="sidebar-category-header">Informasi Siswa</li>
                <li class="{{ request()->routeIs('wali-murid.presensi') ? 'active' : '' }}">
                    <a href="{{ route('wali-murid.presensi') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Riwayat Presensi</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('wali-murid.jurnal') ? 'active' : '' }}">
                    <a href="{{ route('wali-murid.jurnal') }}">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Materi Pelajaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('wali-murid.jadwal') ? 'active' : '' }}">
                    <a href="{{ route('wali-murid.jadwal') }}">
                        <i class="fa-solid fa-calendar-week"></i>
                        <span>Jadwal Kelas</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('wali-murid.dispensasi') ? 'active' : '' }}">
                    <a href="{{ route('wali-murid.dispensasi') }}">
                        <i class="fa-solid fa-file-circle-check"></i>
                        <span>Surat Dispensasi</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-bottom">
            <a href="#" class="logout" onclick="openLogoutModal(); return false;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log out</span>
            </a>
        </div>
    </aside>
    <main class="main-wrapper">
        <header class="top-header-banner">
            <div class="header-title-box">
                <h1>@yield('header_title', 'Portal Wali Murid / Siswa')</h1>
                <p>@yield('header_subtitle', 'Pantau kehadiran, materi pelajaran, dan jadwal putra-putri Anda')</p>
            </div>
            <div class="header-user-nav">
                @yield('header_extra')
                <div class="profile-dropdown-wrap" id="profileDropdownWrap">
                    <div class="user-profile-badge" onclick="toggleProfileDropdown()">
                        <img src="{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Wali Murid').'&background=10b981&color=ffffff&bold=true' }}" alt="Avatar" class="avatar-img">
                        <div class="user-info-text">
                            <span class="user-name">{{ Str::limit(Auth::user()->name ?? 'Wali Murid', 22) }}</span>
                            <span class="user-email">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-header">
                            <div class="d-name">{{ Auth::user()->name ?? 'Wali Murid' }}</div>
                            <div class="d-email">{{ Auth::user()->email }}</div>
                            <span class="d-role">WALI MURID / SISWA</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <button type="button" onclick="closeProfileDropdown(); openLogoutModal();" class="dropdown-item danger" style="width:100%;background:none;border:none;font-family:inherit;text-align:left;cursor:pointer;">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </header>
        @if(session('success'))
            <div class="alert-box success"><div style="display:flex;align-items:center;gap:10px;"><i class="fa-solid fa-circle-check" style="font-size:18px;"></i><span>{{ session('success') }}</span></div><button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="fa-solid fa-xmark"></i></button></div>
        @endif
        @if(session('error'))
            <div class="alert-box danger"><div style="display:flex;align-items:center;gap:10px;"><i class="fa-solid fa-triangle-exclamation" style="font-size:18px;"></i><span>{{ session('error') }}</span></div><button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="fa-solid fa-xmark"></i></button></div>
        @endif
        @yield('content')
    </main>
    <div class="logout-modal-overlay" id="logoutModalOverlay">
        <div class="logout-modal-card">
            <div class="logout-modal-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div class="logout-modal-title">Konfirmasi Logout</div>
            <div class="logout-modal-desc">Apakah Anda yakin ingin keluar dari portal wali murid?</div>
            <div class="logout-modal-actions">
                <button type="button" class="btn-modal cancel" onclick="closeLogoutModal()">Batal</button>
                <form action="{{ route('logout') }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-modal confirm" style="width:100%;">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleProfileDropdown() { document.getElementById('profileDropdown').classList.toggle('open'); }
        function closeProfileDropdown() { document.getElementById('profileDropdown').classList.remove('open'); }
        document.addEventListener('click', function(e) { const w = document.getElementById('profileDropdownWrap'); if (w && !w.contains(e.target)) closeProfileDropdown(); });
        function openLogoutModal() { document.getElementById('logoutModalOverlay').classList.add('open'); }
        function closeLogoutModal() { document.getElementById('logoutModalOverlay').classList.remove('open'); }
        function toggleMobileSidebar() { document.querySelector('.sidebar').classList.toggle('mobile-open'); document.getElementById('sidebarBackdrop').classList.toggle('open'); }
        function closeMobileSidebar() { document.querySelector('.sidebar').classList.remove('mobile-open'); document.getElementById('sidebarBackdrop').classList.remove('open'); }
    </script>
    @yield('scripts')
</body>
</html>
