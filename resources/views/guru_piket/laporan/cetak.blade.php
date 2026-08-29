<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Piket Harian - SMKN 1 BOYOLANGU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif; }
        body { background: #f8fafc; padding: 30px; display: flex; justify-content: center; }
        .paper {
            background: white;
            width: 800px;
            padding: 40px 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 20px;
            gap: 20px;
        }
        .header img { width: 75px; height: 75px; object-fit: contain; }
        .header-text { text-align: center; flex: 1; }
        .header-text h3 { font-size: 15px; text-transform: uppercase; font-weight: bold; }
        .header-text h2 { font-size: 18px; text-transform: uppercase; font-weight: bold; margin: 2px 0; }
        .header-text p { font-size: 12px; }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .report-meta {
            text-align: center;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .section-heading {
            font-size: 13.5px;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-bottom: 14px;
        }
        .data-table th, .data-table td {
            border: 1px solid #0f172a;
            padding: 6px 10px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        .notes-box {
            border: 1px solid #0f172a;
            padding: 12px 14px;
            font-size: 12.5px;
            line-height: 1.6;
            min-height: 100px;
            white-space: pre-line;
            margin-bottom: 24px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 13px;
            text-align: center;
            page-break-inside: avoid;
        }
        .sign-box { width: 220px; }
        .sign-space { height: 65px; }

        @media print {
            body { background: white; padding: 0; }
            .paper { box-shadow: none; width: 100%; padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="paper">
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 8px 18px; background: #2b43b9; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 13px;">
                Cetak Laporan Piket (PDF)
            </button>
        </div>

        <div class="header">
            <img src="{{ asset('asset/logo_smea.png') }}" alt="Logo">
            <div class="header-text">
                <h3>Pemerintah Provinsi Jawa Timur - Dinas Pendidikan</h3>
                <h2>SMK NEGERI 1 BOYOLANGU</h2>
                <p>Jl. Ki Mangunsarkoro VI/3, Boyolangu, Tulungagung &bull; Telp. (0355) 323354 &bull; Web: smkn1boyolangu.sch.id</p>
            </div>
        </div>

        <div class="report-title">LAPORAN PIKET HARIAN</div>
        <div class="report-meta">Hari / Tanggal: <strong>{{ $todayFormatted }}</strong></div>

        <!-- 1. Petugas Piket -->
        <div class="section-heading">I. Petugas Piket Bertugas</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Petugas Guru Piket</th>
                    <th>NIP</th>
                    <th>Waktu Tugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($piketStaff as $idx => $ps)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $ps->nama_lengkap }}</strong></td>
                    <td>{{ $ps->nip }}</td>
                    <td>{{ $laporan->jam_mulai_piket ?? '06:45' }} - {{ $laporan->jam_selesai_piket ?? '15:30' }} WIB</td>
                </tr>
                @empty
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>{{ $laporan->user->name ?? 'Guru Piket' }}</strong></td>
                    <td>-</td>
                    <td>06:45 - 15:30 WIB</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 2. Rekapitulasi Presensi Siswa -->
        <div class="section-heading">II. Rekapitulasi Kehadiran Siswa Hari Ini</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpha</th>
                    <th>Dispensasi</th>
                    <th>Total Presensi Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: center; font-weight: bold;">
                    <td>{{ $presensiSummary['Hadir'] ?? 0 }}</td>
                    <td>{{ $presensiSummary['Sakit'] ?? 0 }}</td>
                    <td>{{ $presensiSummary['Izin'] ?? 0 }}</td>
                    <td>{{ $presensiSummary['Alpha'] ?? 0 }}</td>
                    <td>{{ $presensiSummary['Dispensasi'] ?? 0 }}</td>
                    <td>{{ array_sum($presensiSummary) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- 3. Guru Berhalangan / Izin -->
        <div class="section-heading">III. Guru Berhalangan Hadir / Izin</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Guru</th>
                    <th>Jenis Izin</th>
                    <th>Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guruIzinList as $idx => $gi)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $gi->guru->nama_lengkap ?? 'Guru' }}</strong></td>
                    <td>{{ $gi->jenis_izin }}</td>
                    <td>{{ $gi->alasan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b;">Nihil (Seluruh guru terjadwal hadir)</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 4. Siswa Izin Keluar (Dispensasi) -->
        <div class="section-heading">IV. Rekapitulasi Dispensasi Siswa Keluar</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jam Keluar - Kembali</th>
                    <th>Keperluan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensasiList as $idx => $dl)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ $dl->siswa->nama_lengkap ?? '-' }}</td>
                    <td>{{ $dl->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td style="text-align: center;">{{ Carbon\Carbon::parse($dl->jam_keluar)->format('H:i') }} - {{ $dl->jam_kembali ? Carbon\Carbon::parse($dl->jam_kembali)->format('H:i') : 'Selesai' }}</td>
                    <td>{{ $dl->alasan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b;">Nihil (Tidak ada dispensasi siswa keluar)</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 5. Uraian Catatan Khusus Piket -->
        <div class="section-heading">V. Catatan Kejadian Penting Selama Piket</div>
        <div class="notes-box">
            {{ $laporan->catatan_kejadian ?? 'Kegiatan Belajar Mengajar (KBM) hari ini berlangsung dengan aman, tertib, dan lancar tanpa kendala operasional.' }}
        </div>

        <!-- Tanda Tangan -->
        <div class="signatures">
            <div class="sign-box">
                <div>Mengetahui,</div>
                <div>Kepala SMKN 1 Boyolangu</div>
                <div class="sign-space"></div>
                <div><strong>{{ $namaKepalaSekolah ?? ($kepalaSekolah->nama_lengkap ?? ($kepalaSekolah->name ?? 'Kepala Sekolah')) }}</strong></div>
                <div>NIP. {{ $nipKepalaSekolah ?? ($kepalaSekolah->nip ?? '-') }}</div>
            </div>

            <div class="sign-box">
                <div>Tulungagung, {{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
                <div>Koordinator / Petugas Guru Piket</div>
                <div class="sign-space"></div>
                <div><strong>{{ $laporan->guruPiket->nama_lengkap ?? ($laporan->user->name ?? ($piketStaff->first()->nama_lengkap ?? 'Guru Piket')) }}</strong></div>
                <div>NIP. {{ $laporan->guruPiket->nip ?? ($piketStaff->first()->nip ?? '-') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
