<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan — SMKN 1 BOYOLANGU</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 15px;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }

        /* Judul Laporan */
        .laporan-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .laporan-header h4 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .laporan-header p {
            margin: 4px 0 0;
            font-size: 11pt;
        }

        /* Meta Filter Table */
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .meta-info td {
            padding: 3px 6px;
        }

        /* Data Table */
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }

        .print-table th, .print-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .print-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }

        /* Signature Block */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            text-align: center;
            width: 220px;
        }

        .sig-space {
            height: 65px;
        }

        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-radius: 8px;
            font-family: sans-serif;
        }

        .btn-print {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        @media print {
            .no-print-bar { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="no-print-bar">
    <span>📌 Dokumen Siap Cetak / Export PDF — SMKN 1 BOYOLANGU</span>
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Sekarang</button>
</div>

<!-- Kop Surat Resmi -->
<div class="kop-surat">
    <img src="{{ asset('asset/logo_smea.png') }}" alt="Logo SMKN 1 Boyolangu" class="kop-logo">
    <div class="kop-text">
        <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
        <h2>DINAS PENDIDIKAN</h2>
        <h2>SMK NEGERI 1 BOYOLANGU</h2>
        <p>Jl. Ki Mangunsarkoro No. 1, Boyolangu, Kabupaten Tulungagung, Jawa Timur 66232</p>
        <p>Website: www.smkn1boyolangu.sch.id | Email: info@smkn1boyolangu.sch.id</p>
    </div>
</div>

<!-- Judul Laporan -->
<div class="laporan-header">
    <h4>
        @if($activeTab === 'absensi_siswa')
            LAPORAN REKAPITULASI KEHADIRAN SISWA
        @elseif($activeTab === 'jurnal_mengajar')
            LAPORAN REKAPITULASI JURNAL MENGAJAR GURU
        @elseif($activeTab === 'dispensasi_siswa')
            LAPORAN RIWAYAT DISPENSASI SISWA
        @elseif($activeTab === 'izin_guru')
            LAPORAN KEHADIRAN &amp; IZIN GURU
        @else
            LAPORAN RINGKASAN SEMESTER
        @endif
    </h4>
    <p>Periode: {{ $formattedStart }} s/d {{ $formattedEnd }}</p>
</div>

<!-- Meta Filter -->
<table class="meta-info">
    <tr>
        <td style="width:120px;"><strong>Kelas/Rombel</strong></td>
        <td style="width:10px;">:</td>
        <td>{{ $kelasNama }}</td>
        <td style="width:120px;"><strong>Tanggal Cetak</strong></td>
        <td style="width:10px;">:</td>
        <td>{{ $todayPrintDate }}</td>
    </tr>
    <tr>
        <td><strong>Guru Pengajar</strong></td>
        <td>:</td>
        <td>{{ $guruNama }}</td>
        <td><strong>Satuan Pend.</strong></td>
        <td>:</td>
        <td>SMKN 1 BOYOLANGU</td>
    </tr>
</table>

<!-- Data Table -->
<table class="print-table">
    @if($activeTab === 'absensi_siswa')
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:90px;">NIS</th>
                <th>Nama Siswa</th>
                <th style="width:70px;">Kelas</th>
                <th style="width:45px;">Hadir</th>
                <th style="width:45px;">Sakit</th>
                <th style="width:45px;">Izin</th>
                <th style="width:45px;">Alpa</th>
                <th style="width:45px;">Dispen</th>
                <th style="width:65px;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataList as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $r['nis'] }}</td>
                    <td>{{ $r['nama'] }}</td>
                    <td class="text-center">{{ $r['kelas'] }}</td>
                    <td class="text-center">{{ $r['hadir'] }}</td>
                    <td class="text-center">{{ $r['sakit'] }}</td>
                    <td class="text-center">{{ $r['izin'] }}</td>
                    <td class="text-center">{{ $r['alpa'] }}</td>
                    <td class="text-center">{{ $r['dispen'] }}</td>
                    <td class="text-center"><strong>{{ $r['pct'] }}%</strong></td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">Tidak ada data rekapitulasi.</td></tr>
            @endforelse
        </tbody>

    @elseif($activeTab === 'jurnal_mengajar')
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:75px;">Tanggal</th>
                <th style="width:65px;">Jam</th>
                <th style="width:70px;">Kelas</th>
                <th>Guru Pengajar</th>
                <th>Mata Pelajaran</th>
                <th>Materi Diajarkan</th>
                <th style="width:60px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataList as $i => $j)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $j->tanggal->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $j->jam_ke }}</td>
                    <td class="text-center">{{ $j->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $j->guru->nama_lengkap ?? '-' }}</td>
                    <td>{{ $j->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $j->materi }}</td>
                    <td class="text-center">{{ $j->status_guru }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Tidak ada data jurnal.</td></tr>
            @endforelse
        </tbody>
    @endif
</table>

<!-- Signature Section -->
<div class="signature-section">
    <div class="sig-box">
        <p>Mengetahui,<br>Waka Kurikulum</p>
        <div class="sig-space"></div>
        <p><strong>Drs. H. M. Supriyadi, M.Pd</strong><br>NIP. 19680315 199403 1 004</p>
    </div>

    <div class="sig-box">
        <p>Boyolangu, {{ $todayPrintDate }}<br>Kepala SMKN 1 Boyolangu</p>
        <div class="sig-space"></div>
        <p><strong>Triris Wiharti, M.Pd</strong><br>NIP. 19700512 199702 2 001</p>
    </div>
</div>

</body>
</html>
