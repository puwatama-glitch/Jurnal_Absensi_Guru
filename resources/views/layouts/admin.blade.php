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
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 16px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }

        .avatar-img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
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
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-border-all"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/absensi*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/absensi') }}">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span>Absensi & Jurnal Mengajar</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/siswa*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/siswa') }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Manajemen Siswa</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/guru*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/guru') }}">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Manajemen Guru</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/master*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/master') }}">
                        <i class="fa-solid fa-database"></i>
                        <span>Master Data</span>
                    </a>
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
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <a href="#" class="logout" onclick="document.getElementById('logout-form').submit(); return false;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Log out</span>
                </a>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        <!-- Top Header Card -->
        <header class="top-header-banner">
            <div class="header-title-box">
                <h1>Dashboard</h1>
                <p>Jurnal Absensi Guru & Siswa SMKN 1 BOYOLANGU</p>
            </div>
            <div class="header-user-nav">
                <div class="notif-bell">
                    <i class="fa-regular fa-bell" style="font-size: 20px;"></i>
                    <span class="notif-badge"></span>
                </div>
                <div class="user-profile-badge">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" alt="Admin Avatar" class="avatar-img">
                    <div class="user-info-text">
                        <span class="user-name">{{ Auth::user()->name ?? 'Admin 1' }}</span>
                        <span class="user-email">{{ Auth::user()->email ?? 'Admin@com' }}</span>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
