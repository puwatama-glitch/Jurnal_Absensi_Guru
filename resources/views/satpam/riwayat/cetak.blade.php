<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Pos Keamanan - {{ $todayFormatted }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 24px;
            font-size: 12px;
            line-height: 1.4;
        }

        .paper {
            background: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 36px 44px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2.5px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
            gap: 16px;
        }

        .header img {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text h3 {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-text h2 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin: 2px 0;
        }

        .header-text p {
            font-size: 10px;
            color: #475569;
        }

        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h1 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-decoration: underline;
        }

        .report-title .date-badge {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            margin-top: 4px;
        }

        .section-heading {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            background: #f1f5f9;
            padding: 5px 10px;
            border-left: 3px solid #2b43b9;
            margin: 16px 0 8px 0;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 11px;
        }

        table, th, td {
            border: 1px solid #cbd5e1;
        }

        th {
            background-color: #f8fafc;
            padding: 6px 8px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            color: #334155;
        }

        td {
            padding: 6px 8px;
            vertical-align: middle;
        }

        .signatures {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sign-box {
            text-align: center;
            width: 240px;
            font-size: 11.5px;
        }

        .sign-space {
            height: 60px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="paper">
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 8px 18px; background: #2b43b9; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 13px;">
                Cetak Laporan Pos Jaga (PDF)
            </button>
        </div>

        <div class="header">
            <img src="{{ asset('asset/logo_smea.png') }}" alt="Logo">
            <div class="header-text">
                <h3>Pemerintah Provinsi Jawa Timur - Dinas Pendidikan</h3>
                <h2>SMK NEGERI 1 BOYOLANGU</h2>
                <p>Jl. Ki Mangunsarkoro Gg. 1 No. 3, Beji, Boyolangu, Tulungagung, Jawa Timur 66233</p>
                <p>Website: smkn1boyolangu.sch.id | Email: info@smkn1boyolangu.sch.id</p>
            </div>
        </div>

        <div class="report-title">
            <h1>LAPORAN HARIAN POS KEAMANAN GERBANG</h1>
            <div class="date-badge">Hari / Tanggal: <strong>{{ $todayFormatted }}</strong></div>
        </div>

        <!-- 1. Data Dispensasi Siswa -->
        <div class="section-heading">I. Rekap Izin Dispensasi Siswa Keluar Gerbang (Total: {{ $dispensasiList->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Jam Keluar</th>
                    <th>Jam Kembali</th>
                    <th>Nama Siswa & NISN</th>
                    <th>Kelas</th>
                    <th>Alasan / Keperluan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensasiList as $idx => $d)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ Carbon\Carbon::parse($d->jam_keluar)->format('H:i') }} WIB</strong></td>
                    <td>{{ $d->jam_kembali ? Carbon\Carbon::parse($d->jam_kembali)->format('H:i').' WIB' : 'Belum Kembali' }}</td>
                    <td>
                        <strong>{{ $d->siswa->nama_lengkap ?? 'Siswa' }}</strong>
                        <div style="font-size: 10px; color: #64748b;">NISN: {{ $d->siswa->nisn ?? '-' }}</div>
                    </td>
                    <td>{{ $d->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $d->alasan }}</td>
                    <td>{{ $d->jam_kembali || $d->status === 'Selesai' ? 'Kembali' : 'Di Luar' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b;">Nihil (Tidak ada siswa keluar gerbang)</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 2. Data Buku Tamu -->
        <div class="section-heading">II. Rekap Kunjungan Tamu Sekolah (Total: {{ $tamuList->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Nama Tamu & Instansi</th>
                    <th>Keperluan Kunjungan</th>
                    <th>Bertemu</th>
                    <th>Kendaraan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tamuList as $idx => $t)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ Carbon\Carbon::parse($t->jam_masuk)->format('H:i') }} WIB</strong></td>
                    <td>{{ $t->jam_keluar ? Carbon\Carbon::parse($t->jam_keluar)->format('H:i').' WIB' : 'Di Dalam' }}</td>
                    <td>
                        <strong>{{ $t->nama_tamu }}</strong>
                        <div style="font-size: 10px; color: #64748b;">{{ $t->instansi ?? 'Pribadi' }}</div>
                    </td>
                    <td>{{ $t->keperluan }}</td>
                    <td><strong>{{ $t->bertemu_dengan }}</strong></td>
                    <td>{{ $t->no_kendaraan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b;">Nihil (Tidak ada kunjungan tamu)</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan -->
        <div class="signatures">
            <div class="sign-box">
                <div>Mengetahui,</div>
                <div>Kepala SMKN 1 Boyolangu</div>
                <div class="sign-space"></div>
                <div><strong>{{ $namaKepalaSekolah }}</strong></div>
                <div>NIP. {{ $nipKepalaSekolah }}</div>
            </div>

            <div class="sign-box">
                <div>Tulungagung, {{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
                <div>Petugas Keamanan / Satpam Gate</div>
                <div class="sign-space"></div>
                <div><strong>{{ $satpam->nama_lengkap ?? (Auth::user()->name ?? 'Petugas Satpam') }}</strong></div>
                <div>NIP. {{ $satpam->nip ?? '-' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
