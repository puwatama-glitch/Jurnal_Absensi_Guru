<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Jurnal Absensi SMKN 1 BOYOLANGU')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            gap: 6px;
            margin-top: 15px;
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

        /* Accordion Sub-menu */
        .sidebar-accordion-toggle {
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
            cursor: pointer;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
        }

        .sidebar-accordion-toggle:hover {
            background-color: #eaeff8;
            color: #2b43b9;
        }

        .sidebar-accordion-toggle.open {
            color: #2b43b9;
            background-color: #eaeff8;
        }

        .sidebar-accordion-toggle i.menu-icon {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-accordion-toggle .chevron {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.25s ease;
        }

        .sidebar-accordion-toggle.open .chevron {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            list-style: none;
            display: none;
            flex-direction: column;
            gap: 2px;
            margin-top: 4px;
            padding-left: 14px;
        }

        .sidebar-submenu.open {
            display: flex;
        }

        .sidebar-submenu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 10px;
            color: #6b7a99;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-submenu li.active a {
            color: #2b43b9;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(43, 67, 185, 0.08);
        }

        .sidebar-submenu li a:hover {
            background-color: #eaeff8;
            color: #2b43b9;
        }

        .sidebar-submenu li a i {
            font-size: 14px;
            width: 18px;
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

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
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
            animation: fadeInSlideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .header-title-box h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
            animation: fadeIn 0.5s ease-out;
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

        .notif-bell {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1b2559;
            position: relative;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .notif-bell:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .notif-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 10px;
            height: 10px;
            background-color: #ff3b30;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulseGlow 2s infinite;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 16px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            transition: opacity 0.2s ease;
        }

        .avatar-img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
            transition: transform 0.2s ease;
        }

        .user-profile-badge:hover .avatar-img {
            transform: scale(1.06);
        }

        .user-info-text {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 700;
            font-size: 15px;
            color: white;
        }

        .user-email {
            font-size: 12px;
            opacity: 0.75;
        }

        /* Micro Animations */
        @keyframes fadeInSlideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulseGlow {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 59, 48, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 59, 48, 0); }
        }

        /* Profile Dropdown */
        .profile-dropdown-wrap {
            position: relative;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 16px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: opacity 0.2s ease;
            user-select: none;
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 14px);
            right: 0;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.14);
            border: 1px solid #e2e8f0;
            min-width: 220px;
            padding: 8px;
            display: none;
            z-index: 9999;
            animation: dropIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .profile-dropdown.show { display: block; }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 12px 14px 10px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 6px;
        }
        .dropdown-header .d-name { font-size: 14px; font-weight: 800; color: #0f172a; }
        .dropdown-header .d-email { font-size: 12px; color: #64748b; font-weight: 600; margin-top: 2px; }
        .dropdown-header .d-role {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 20px;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .dropdown-item:hover { background: #f8fafc; color: #2b43b9; }
        .dropdown-item i { width: 18px; text-align: center; font-size: 14px; }

        .dropdown-item.danger { color: #dc2626; }
        .dropdown-item.danger:hover { background: #fff5f5; color: #b91c1c; }

        .dropdown-divider { height: 1px; background: #f1f5f9; margin: 6px 0; }

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

        .logout-modal-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .logout-modal-desc {
            font-size: 13.5px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .logout-modal-actions {
            display: flex;
            gap: 12px;
        }

        .btn-cancel-logout {
            flex: 1;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-cancel-logout:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-confirm-logout {
            flex: 1;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }
        .btn-confirm-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Logout Form -->
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none;">
        @csrf
    </form>

    <!-- Animated Logout Modal -->
    <div class="logout-modal-backdrop" id="logoutModal" onclick="if(event.target === this) closeLogoutModal()">
        <div class="logout-modal-card">
            <div class="logout-icon-wrap">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3 class="logout-modal-title">Konfirmasi Keluar</h3>
            <p class="logout-modal-desc">Apakah Anda yakin ingin keluar dari aplikasi Jurnal Absensi Guru SMKN 1 Boyolangu?</p>
            <div class="logout-modal-actions">
                <button type="button" class="btn-cancel-logout" onclick="closeLogoutModal()">Batal</button>
                <button type="button" class="btn-confirm-logout" onclick="document.getElementById('logout-form').submit()">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Ya, Keluar
                </button>
            </div>
        </div>
    </div>

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
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-border-all"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/absensi*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/absensi') }}">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span>Absensi &amp; Jurnal Mengajar</span>
                    </a>
                </li>

                {{-- Master Data Accordion --}}
                <li>
                    <button
                        class="sidebar-accordion-toggle {{ request()->is('admin/master*') ? 'open' : '' }}"
                        id="masterAccordionBtn"
                        type="button"
                        onclick="toggleMasterAccordion()"
                    >
                        <i class="fa-solid fa-database menu-icon"></i>
                        <span>Master Data</span>
                        <i class="fa-solid fa-chevron-down chevron"></i>
                    </button>
                    <ul class="sidebar-submenu {{ request()->is('admin/master*') ? 'open' : '' }}" id="masterSubmenu">
                        <li class="{{ request()->is('admin/master/siswa*') ? 'active' : '' }}">
                            <a href="{{ url('/admin/master/siswa') }}">
                                <i class="fa-solid fa-users"></i>
                                <span>Manajemen Siswa</span>
                            </a>
                        </li>
                        <li class="{{ request()->is('admin/master/guru*') ? 'active' : '' }}">
                            <a href="{{ url('/admin/master/guru') }}">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <span>Manajemen Guru</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/laporan') }}">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-bottom">
            <a href="{{ url('/admin/help') }}">
                <i class="fa-solid fa-circle-info"></i>
                <span>Help Centre</span>
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
                <h1>@yield('header_title', 'Dashboard')</h1>
                <p>@yield('header_subtitle', 'Jurnal Absensi Guru & Siswa SMKN 1 BOYOLANGU')</p>
            </div>
            <div class="header-user-nav">
                <div class="notif-bell">
                    <i class="fa-regular fa-bell" style="font-size: 20px;"></i>
                    <span class="notif-badge"></span>
                </div>
                <div class="profile-dropdown-wrap" id="profileDropdownWrap">
                    <div class="user-profile-badge" onclick="toggleProfileDropdown()">
                        <img src="{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Admin').'&background=ffffff&color=2b43b9&bold=true' }}" alt="Avatar" class="avatar-img">
                        <div class="user-info-text">
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="user-email">{{ Auth::user()->email ?? 'admin@smkn1boyolangu.sch.id' }}</span>
                        </div>
                    </div>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-header">
                            <div class="d-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="d-email">{{ Auth::user()->email ?? 'admin@smkn1boyolangu.sch.id' }}</div>
                            <span class="d-role">{{ str_replace('_', ' ', Auth::user()->role ?? 'admin') }}</span>
                        </div>

                        <a href="{{ route('admin.profil') }}" class="dropdown-item">
                            <i class="fa-solid fa-user-pen"></i> Profil Saya
                        </a>

                        <div class="dropdown-divider"></div>

                        <button type="button" onclick="closeProfileDropdown(); openLogoutModal();" class="dropdown-item danger" style="width:100%;background:none;border:none;font-family:inherit;text-align:left;">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    @yield('scripts')
<script>
    function toggleMasterAccordion() {
        const btn = document.getElementById('masterAccordionBtn');
        const menu = document.getElementById('masterSubmenu');
        btn.classList.toggle('open');
        menu.classList.toggle('open');
    }

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

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('profileDropdownWrap');
        if (wrap && !wrap.contains(e.target)) {
            closeProfileDropdown();
        }
    });

    // ESC key closes logout modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
            closeProfileDropdown();
        }
    });
</script>
</body>
</html>
