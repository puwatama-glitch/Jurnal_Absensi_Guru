<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Absensi — SMKN 1 Boyolangu</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT HERO PANEL -->
    <div class="hero-panel">
        <!-- Header Branding -->
        <div class="hero-header">
            <div class="school-logo-icon">
                <i class="bi bi-bank2"></i>
            </div>
            <div class="brand-text">
                <h4>SMKN 1 BOYOLANGU</h4>
                <p>KABUPATEN TULUNGAGUNG</p>
            </div>
        </div>

        <!-- Hero Main Content -->
        <div class="hero-content">
            <h1 class="hero-title">Digitalisasi Presensi &<br>Disiplin Siswa</h1>
            <p class="hero-description">
                Sistem informasi pencatatan kehadiran, monitoring kelas, dan jurnal absensi harian terintegrasi untuk mewujudkan transparansi dan ketepatan waktu.
            </p>
        </div>

        <!-- Hero Footer Metrics -->
        <div class="hero-footer">
            <div class="hero-metrics">
                <div class="metric-item">
                    <div class="metric-title">E-Disiplin</div>
                    <div class="metric-sub">Ketepatan Waktu</div>
                </div>
                <div class="metric-item">
                    <div class="metric-title">Akuntabel</div>
                    <div class="metric-sub">Data Valid</div>
                </div>
            </div>
            <div class="hero-shield-badge">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="form-panel">
        <div class="login-card">
            <!-- Icon Badge -->
            <div class="card-icon-wrapper">
                <i class="bi bi-shield-check"></i>
            </div>

            <h2 class="card-title">JURNAL ABSENSI</h2>
            <div class="card-subtitle">SMKN 1 BOYOLANGU</div>

            <!-- Validation Error Alert -->
            @if ($errors->any())
                <div class="alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <!-- Role Access Select -->
                <div class="form-group">
                    <label class="form-label" for="role">Hak Akses / Role</label>
                    <div class="input-wrapper">
                        <select name="role" id="role" class="form-input form-select" onchange="quickFillDemoCredential()">
                            <option value="">-- Pilih Role (Otomatis) --</option>
                            <option value="admin">Admin (Pengelola Sistem)</option>
                            <option value="wali_kelas">Wali Kelas</option>
                            <option value="guru_piket">Guru Piket</option>
                            <option value="guru_mapel">Guru Mapel</option>
                            <option value="satpam">Satpam (Keamanan Gate)</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="waka">Waka (Wakil Kepala Sekolah)</option>
                        </select>
                        <i class="bi bi-person-badge input-icon"></i>
                    </div>
                </div>

                <!-- Username Input -->
                <div class="form-group">
                    <label class="form-label" for="username">Username / NIP / Email</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            class="form-input" 
                            placeholder="Masukkan NIP, NISN atau ID User" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus
                        >
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-input" 
                            placeholder="••••••••••••" 
                            required
                        >
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="toggle-password" id="togglePasswordBtn" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Options Row -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat Saya</span>
                    </label>
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <span>Masuk ke Jurnal</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Card Footer Help Text -->
            <div class="card-help">
                <i class="bi bi-shield-lock"></i>
                <span>Butuh bantuan akses? Hubungi <strong>Tim IT SMKN 1 Boyolangu</strong></span>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright-text">
            © 2026 SMKN 1 Boyolangu. All rights reserved.
        </div>
    </div>

</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    }

    const demoAccounts = {
        'admin': 'admin@smkn1boyolangu.sch.id',
        'wali_kelas': 'walikelas@smkn1boyolangu.sch.id',
        'guru_piket': 'gurupiket@smkn1boyolangu.sch.id',
        'guru_mapel': 'gurumapel@smkn1boyolangu.sch.id',
        'satpam': 'satpam@smkn1boyolangu.sch.id',
        'kepala_sekolah': 'kepsek@smkn1boyolangu.sch.id',
        'waka': 'waka@smkn1boyolangu.sch.id'
    };

    function quickFillDemoCredential() {
        const role = document.getElementById('role').value;
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        if (role && demoAccounts[role]) {
            usernameInput.value = demoAccounts[role];
            passwordInput.value = 'password';
        }
    }
</script>

</body>
</html>
