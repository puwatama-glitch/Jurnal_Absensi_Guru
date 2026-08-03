```
     ██╗ █████╗  ██████╗ 
     ██║██╔══██╗██╔════╝ 
     ██║███████║██║  ███╗
██   ██║██╔══██║██║   ██║
╚█████╔╝██║  ██║╚██████╔╝
 ╚════╝ ╚═╝  ╚═╝ ╚═════╝ 
```
# 📚 Jurnal Absensi Guru
Sistem digital untuk mencatat jurnal mengajar, jadwal pelajaran, dan absensi guru & siswa — nggak perlu lagi tulis manual di buku jurnal kelas.

`PHP` `Laravel` `MySQL` `Status: Development`

---

## 📋 Tentang Proyek

Jurnal Absensi Guru adalah aplikasi web berbasis Laravel yang membantu sekolah mencatat kegiatan belajar mengajar secara digital — mulai dari jadwal pelajaran, jurnal mengajar harian guru, sampai rekap ketidakhadiran siswa per sesi.

## ✨ Fitur

| Fitur | Deskripsi |
|---|---|
| 🔐 Login & Role | Autentikasi guru, wali kelas, dan admin/tata usaha |
| 🗓️ Jadwal Pelajaran | Kelola jadwal mengajar mingguan per kelas & guru |
| 📝 Jurnal Mengajar | Guru mengisi materi, jam ke-, dan status kehadiran per sesi |
| 🙋 Absensi Siswa | Catat siswa yang tidak hadir (sakit/izin/alpa) tiap sesi |
| 📊 Dashboard Rekap | Rekap kehadiran guru & siswa per kelas/periode |

## 🛠️ Tech Stack

```
Frontend  → Blade · Tailwind/Bootstrap · JavaScript
Backend   → PHP (Laravel)
Database  → MySQL
```

## 🚀 Cara Install (Pertama Kali)

### Prasyarat
- XAMPP / Laragon sudah terinstall
- PHP >= 8.1
- Composer
- Git

### Langkah-langkah

```bash
# 0. Verifikasi identitas Git kamu (sekali saja di laptop masing-masing)
git config --global user.name "nama_kamu"
git config --global user.email "email_kamu@gmail.com"

# 1. Clone repo
git clone https://github.com/puwatama-glitch/Jurnal_Absensi_Guru
cd jurnal_absensi_guru

# 2. Install dependencies Laravel
composer install

# 3. Buat file konfigurasi
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, sesuaikan bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jurnal_absensi_guru
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Buat database "jurnal_absensi_guru" di phpMyAdmin (http://localhost/phpmyadmin)

# 5. Jalankan migration (buat semua tabel otomatis)
php artisan migrate

# (opsional) isi data contoh guru/kelas/mapel
php artisan migrate --seed

# 6. Jalankan aplikasi
php artisan serve
```

Buka browser → `http://localhost:8000` 🎉

## 🔄 Workflow Sebelum Ngoding

Wajib dilakukan setiap kali mau mulai kerja!

```bash
# Cek apakah ada perubahan dari contributor lain
git status

# Kalau ada update, pull dulu sebelum mulai ngoding
git pull origin main
```

> Kalau ada migration baru dari teman setelah `git pull`, jalankan `php artisan migrate` lagi biar tabel barunya ikut dibuat.

## 📤 Upload Perubahan

```bash
# 1. Tambahkan semua file yang berubah
git add .

# 2. Tulis pesan commit yang jelas
git commit -m "tambah fitur jurnal mengajar"

# 3. Push ke repository
git push -u origin main
```

## 📁 Struktur Folder

```
$ tree
.
├── app
│   ├── Http
│   │   └── Controllers
│   │       ├── GuruController.php
│   │       ├── KelasController.php
│   │       ├── JurnalMengajarController.php
│   │       ├── JadwalPelajaranController.php
│   │       └── DetailKetidakhadiranController.php
│   └── Models
│       ├── Guru.php
│       ├── Kelas.php
│       ├── Siswa.php
│       ├── Mapel.php
│       ├── JurnalMengajar.php
│       ├── DetailKetidakhadiran.php
│       └── JadwalPelajaran.php
├── database
│   ├── migrations
│   │   ├── xxxx_create_guru_table.php
│   │   ├── xxxx_create_kelas_table.php
│   │   ├── xxxx_create_siswa_table.php
│   │   ├── xxxx_create_mapel_table.php
│   │   ├── xxxx_create_jurnal_mengajar_table.php
│   │   ├── xxxx_create_detail_ketidakhadiran_table.php
│   │   └── xxxx_create_jadwal_pelajaran_table.php
│   └── seeders
│       ├── GuruSeeder.php
│       ├── KelasSeeder.php
│       └── MapelSeeder.php
├── resources
│   └── views
│       ├── layouts
│       │   └── app.blade.php
│       ├── guru
│       ├── kelas
│       ├── jurnal_mengajar
│       └── jadwal_pelajaran
├── routes
│   └── web.php
├── .env.example
├── composer.json
└── README.md

10 directories, 20+ files
```

## 🗂️ Struktur Database

| Tabel | Isi |
|---|---|
| `guru` | Data guru: NIP, nama, mata pelajaran, no HP |
| `kelas` | Data kelas: nama kelas, wali kelas, jumlah siswa |
| `siswa` | Data siswa: NIS, nama, kelas, jenis kelamin |
| `mapel` | Daftar mata pelajaran (umum, dasar kejuruan, konsentrasi, pilihan) |
| `jurnal_mengajar` | Catatan harian kegiatan mengajar guru |
| `detail_ketidakhadiran` | Rincian siswa yang tidak hadir per sesi jurnal |
| `jadwal_pelajaran` | Jadwal mengajar rutin mingguan |

## 👥 Tim

**_(isi nama tim kamu)_** — _(isi tagline kelompok)_

| Nama | Role |
|---|---|
| _(nama kamu)_ | _(misal: Backend)_ |
| _(nama teman)_ | _(misal: Frontend)_ |
| _(nama teman)_ | _(misal: Database)_ |
