<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Satpam Gate - Jurnal Absensi SMKN 1 BOYOLANGU')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f2f4f8;
            color: #2b3674;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: #f6f7fb;
            border-right: 1px solid #e3e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 28px 20px;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .sidebar-logo-img {
            width: 46px;
            height: 46px;
            object-fit: contain;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-title {
            font-weight: 800;
            font-size: 15px;
            line-height: 1.2;
            color: #1b2559;
            letter-spacing: -0.2px;
        }

        .sidebar-subtitle {
            font-size: 13px;
            color: #707e94;
            font-weight: 600;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 10px;
        }

        .sidebar-category-header {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            padding: 14px 14px 4px 14px;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .sidebar-category-header:first-child {
            padding-top: 4px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #6b7a99;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-menu li.active a {
            color: #2b43b9;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(43, 67, 185, 0.08);
            font-weight: 700;
        }

        .sidebar-menu li.active a::before {
            content: '';
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background-color: #2b43b9;
            border-radius: 4px 0 0 4px;
        }

        .sidebar-menu li a:hover:not(.active) {
            background-color: #eaeff8;
            color: #2b43b9;
        }

        .sidebar-menu li a i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-bottom {
            border-top: 1px solid #e3e8f0;
            padding-top: 20px;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 16px;
            border-radius: 12px;
            color: #6b7a99;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .sidebar-bottom a.logout {
            color: #e63946;
        }

        .sidebar-bottom a:hover {
            background-color: #eaeff8;
        }

        .sidebar-bottom a.logout:hover {
            background-color: #ffeef0;
        }

        .sidebar-bottom a.help-link {
            font-size: 11.5px;
            font-weight: 600;
            color: #94a3b8;
            padding: 7px 16px;
            gap: 10px;
            line-height: 1.35;
        }

        .sidebar-bottom a.help-link i {
            font-size: 13px;
            color: #94a3b8;
        }

        .sidebar-bottom a.help-link:hover {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .sidebar-bottom a.help-link.active {
            color: #2b43b9;
            background-color: #f1f5f9;
            font-weight: 700;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
            min-width: 0;
        }

        /* Header Card Banner */
        .top-header-banner {
            background: linear-gradient(135deg, #3d56b2 0%, #2b3a8c 100%);
            border-radius: 20px;
            padding: 24px 32px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 24px rgba(43, 67, 185, 0.2);
            margin-bottom: 24px;
        }

        .header-title-box h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .header-title-box p {
            font-size: 14px;
            opacity: 0.85;
            font-weight: 500;
        }

        .header-user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* User Profile Pill */
        .profile-dropdown-wrap {
            position: relative;
        }

        .user-profile-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 30px;
            padding: 4px 16px 4px 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .user-profile-badge:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .avatar-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            color: white;
        }

        .user-email {
            font-size: 11px;
            opacity: 0.8;
            line-height: 1.2;
            color: white;
        }

        .profile-dropdown {
            position: absolute;
            top: 56px;
            right: 0;
            width: 240px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #eef2f7;
            display: none;
            flex-direction: column;
            padding: 8px;
            z-index: 1000;
        }

        .profile-dropdown.show { display: flex; }

        .dropdown-header {
            padding: 12px 14px 8px;
            border-bottom: 1px solid #f4f7fe;
            margin-bottom: 6px;
        }

        .d-name { font-weight: 700; font-size: 14px; color: #1b2559; }
        .d-email { font-size: 12px; color: #a3aed0; }
        .d-role {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            background: #eef2ff;
            color: #2b43b9;
            padding: 2px 6px;
            border-radius: 4px;
            margin-top: 4px;
            display: inline-block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #707e94;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f4f7fe;
            color: #2b43b9;
        }

        .dropdown-item.danger { color: #e63946; }
        .dropdown-item.danger:hover { background-color: #ffeef0; color: #e63946; }
        .dropdown-divider { height: 1px; background-color: #f4f7fe; margin: 4px 0; }

        /* Alerts */
        .alert-box {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 600;
        }

        .alert-box.success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-box.danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Logout Modal */
        .logout-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .logout-modal-backdrop.show { display: flex; }
        .logout-modal-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            padding: 26px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .logout-modal-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #fef2f2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px auto;
        }
        .logout-modal-title { font-size: 18px; font-weight: 800; color: #1b2559; margin-bottom: 6px; }
        .logout-modal-desc { font-size: 13.5px; color: #6b7a99; margin-bottom: 22px; }
        .logout-modal-actions { display: flex; gap: 10px; }
        .btn-modal {
            flex: 1;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-modal.cancel { background: #f1f5f9; color: #475569; }
        .btn-modal.cancel:hover { background: #e2e8f0; }
        .btn-modal.confirm { background: #ef4444; color: white; }
        .btn-modal.confirm:hover { background: #dc2626; }

        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; padding: 16px 20px; }
            .main-wrapper { padding: 16px; }
            .top-header-banner { flex-direction: column; align-items: flex-start; gap: 16px; }
            .header-user-nav { width: 100%; justify-content: space-between; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <img src="{{ asset('asset/logo.png') }}" alt="Logo Jurnal Absensi" class="sidebar-logo-img">
                <div class="sidebar-brand-text">
                    <span class="sidebar-title">JURNAL<br>ABSENSI</span>
                    <span class="sidebar-subtitle">SMKN 1 BOYOLANGU</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-category-header">Menu Utama</li>
                <li class="{{ request()->routeIs('satpam.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('satpam.dashboard') }}">
                        <i class="fa-solid fa-border-all"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('satpam.monitoring') ? 'active' : '' }}">
                    <a href="{{ route('satpam.monitoring') }}">
                        <i class="fa-solid fa-tower-observation"></i>
                        <span>Monitoring Gerbang</span>
                    </a>
                </li>

                <li class="sidebar-category-header">Layanan Keamanan & Gate</li>
                <li class="{{ request()->routeIs('satpam.dispensasi*') ? 'active' : '' }}">
                    <a href="{{ route('satpam.dispensasi') }}">
                        <i class="fa-solid fa-ticket-simple"></i>
                        <span>Verifikasi Dispensasi</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('satpam.buku-tamu*') ? 'active' : '' }}">
                    <a href="{{ route('satpam.buku-tamu') }}">
                        <i class="fa-solid fa-address-book"></i>
                        <span>Buku Tamu Digital</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('satpam.riwayat*') ? 'active' : '' }}">
                    <a href="{{ route('satpam.riwayat') }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Riwayat Log Gerbang</span>
                    </a>
                </li>

                @if(Auth::user()->role === 'admin')
                <li class="sidebar-category-header">Akses Administrator</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" style="color: #6366f1;">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Admin</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="sidebar-bottom">
            <a href="{{ route('satpam.help') }}" class="help-link {{ request()->routeIs('satpam.help') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-question"></i>
                <span>Panduan Satpam</span>
            </a>
            <a href="#" class="logout" onclick="openLogoutModal(); return false;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log out</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        <!-- Top Header Card -->
        <header class="top-header-banner">
            <div class="header-title-box">
                <h1>@yield('header_title', 'Pos Keamanan & Gate')</h1>
                <p>@yield('header_subtitle', 'Sistem Monitoring Keluar-Masuk Siswa & Buku Tamu Sekolah')</p>
            </div>
            <div class="header-user-nav">
                @yield('header_extra')

                {{-- User Profile Dropdown --}}
                <div class="profile-dropdown-wrap" id="profileDropdownWrap">
                    <div class="user-profile-badge" onclick="toggleProfileDropdown()">
                        <img src="{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Satpam').'&background=e0e7ff&color=2b43b9&bold=true' }}" alt="Avatar" class="avatar-img">
                        <div class="user-info-text">
                            <span class="user-name">{{ Auth::user()->name ?? 'Satpam Gate' }}</span>
                            <span class="user-email">{{ Auth::user()->email }}</span>
                        </div>
                    </div>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-header">
                            <div class="d-name">{{ Auth::user()->name ?? 'Satpam Gate' }}</div>
                            <div class="d-email">{{ Auth::user()->email }}</div>
                            <span class="d-role">{{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        </div>

                        <a href="{{ route('admin.profil') }}" class="dropdown-item">
                            <i class="fa-solid fa-user-pen"></i> Profil Saya
                        </a>

                        <div class="dropdown-divider"></div>

                        <button type="button" onclick="closeProfileDropdown(); openLogoutModal();" class="dropdown-item danger" style="width:100%;background:none;border:none;font-family:inherit;text-align:left;cursor:pointer;">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert-box success">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color: inherit; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box danger">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color: inherit; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Logout Modal -->
    <div class="logout-modal-backdrop" id="logoutModal">
        <div class="logout-modal-card">
            <div class="logout-modal-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3 class="logout-modal-title">Konfirmasi Keluar</h3>
            <p class="logout-modal-desc">Apakah Anda yakin ingin keluar dari sesi Pos Keamanan Gerbang SMKN 1 Boyolangu?</p>
            <div class="logout-modal-actions">
                <button type="button" class="btn-modal cancel" onclick="closeLogoutModal()">Batal</button>
                <form action="{{ route('logout') }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-modal confirm" style="width: 100%;">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        function closeProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.remove('show');
        }

        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.add('show');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) modal.classList.remove('show');
        }

        document.addEventListener('click', function(e) {
            const profileWrap = document.getElementById('profileDropdownWrap');
            if (profileWrap && !profileWrap.contains(e.target)) {
                closeProfileDropdown();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
                closeProfileDropdown();
            }
        });
    </script>
</body>
</html>
