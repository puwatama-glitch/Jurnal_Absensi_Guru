@extends('layouts.wali_kelas')

@section('title', 'Data Siswa Kelas Binaan - SMKN 1 BOYOLANGU')
@section('header_title', 'Data Siswa Kelas Binaan')
@section('header_subtitle', 'Daftar lengkap siswa, NISN, dan kontak orang tua')

@section('header_extra')
    @if($kelas)
    <div class="header-kelas-pill">
        <i class="fa-solid fa-users"></i>
        <div>
            <div class="header-kelas-title">Kelas Binaan</div>
            <div class="header-kelas-name">{{ $kelas->nama_kelas }} ({{ $siswaList->total() ?? count($siswaList) }} Siswa)</div>
        </div>
    </div>
    @endif
@endsection

@section('styles')
<style>
    .page-action-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filter-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }

    .filter-input {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        min-width: 260px;
    }
    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
    .search-input-wrap input { padding-left: 36px; }

    .table-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
    }
    .table-hdr {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-title { font-size: 17px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
    .count-badge { font-size: 12px; font-weight: 700; color: #2b43b9; background: #eaeff8; padding: 5px 14px; border-radius: 20px; }

    .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .data-table th {
        font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.6px;
        padding: 12px 16px; text-align: left; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .data-table th:first-child { border-radius: 10px 0 0 10px; }
    .data-table th:last-child  { border-radius: 0 10px 10px 0; }
    .data-table td {
        padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .data-table tbody tr:hover td { background: #f8fafc; }

    .siswa-cell { display: flex; align-items: center; gap: 12px; }
    .siswa-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c7d2fe, #818cf8);
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 900;
        flex-shrink: 0;
    }
    .siswa-avatar.female { background: linear-gradient(135deg, #fce7f3, #f9a8d4); color: #9d174d; }
    .siswa-name { font-weight: 700; color: #0f172a; font-size: 14px; }
    .siswa-nisn { font-size: 11.5px; color: #64748b; font-weight: 600; }

    .btn-wa-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        color: #166534;
        font-weight: 700;
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-wa-link:hover { background: #22c55e; color: #ffffff; }

    /* Pagination Styling matching System Design */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }
    .pag-text { font-size: 13px; color: #64748b; font-weight: 600; }
    .pag-pills { display: flex; gap: 6px; }
    .pag-pills a, .pag-pills span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .pag-pills a { background: #f1f5f9; color: #475569; }
    .pag-pills a:hover { background: #eaeff8; color: #2b43b9; }
    .pag-pills span.active { background: #2b43b9; color: #ffffff; box-shadow: 0 4px 12px rgba(43,67,185,0.25); }
    .pag-pills span.disabled { background: #f8fafc; color: #cbd5e1; cursor: default; }
</style>
@endsection

@section('content')

@if(!$kelas)
    <div style="background:#ffffff;border-radius:24px;padding:60px 24px;text-align:center;border:1px solid #e2e8f0;">
        <i class="fa-solid fa-users-slash" style="font-size:56px;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
        <h3 style="font-size:20px;font-weight:800;color:#0f172a;margin-bottom:8px;">Belum Ada Kelas Binaan</h3>
        <p style="font-size:14px;color:#64748b;">Akun ini belum memiliki kelas binaan yang ditugaskan.</p>
    </div>
@else

    {{-- Filter Bar --}}
    <form action="{{ route('wali-kelas.siswa') }}" method="GET">
    <div class="page-action-card">
        <div class="filter-group">
            <div class="filter-item">
                <label class="filter-label">Pencarian Siswa</label>
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ $search }}" class="filter-input" placeholder="Cari Nama Siswa, NISN, atau NIS…">
                </div>
            </div>
            @if($search)
                <a href="{{ route('wali-kelas.siswa') }}" style="align-self:flex-end;padding:10px 16px;background:#f1f5f9;border-radius:12px;font-size:13px;font-weight:700;color:#475569;text-decoration:none;">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </div>

        @if(Auth::user()->role === 'admin' && count($allKelasList) > 1)
        <div>
            <label style="font-size:11px;font-weight:800;color:#64748b;display:block;margin-bottom:4px;text-transform:uppercase;">Pilih Kelas:</label>
            <select name="kelas_id" onchange="this.form.submit()" style="background:#f8fafc;border:1.5px solid #cbd5e1;border-radius:12px;padding:9px 14px;font-size:13px;font-weight:700;color:#0f172a;">
                @foreach($allKelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $k->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    </form>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-hdr">
            <div class="table-title">
                <i class="fa-solid fa-address-book"></i>
                <span>Daftar Siswa Kelas {{ $kelas->nama_kelas }}</span>
            </div>
            <span class="count-badge"><i class="fa-solid fa-users"></i> {{ $siswaList->total() }} Siswa</span>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Siswa &amp; NISN</th>
                    <th>L/P</th>
                    <th>NIS</th>
                    <th>Kontak Orang Tua</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $i => $s)
                <tr>
                    <td style="color:#94a3b8;font-weight:700;font-size:12px;">{{ $siswaList->firstItem() + $i }}</td>
                    <td>
                        <div class="siswa-cell">
                            <div class="siswa-avatar {{ $s->jenis_kelamin === 'P' ? 'female' : '' }}">
                                {{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <div class="siswa-name">{{ $s->nama_lengkap }}</div>
                                <div class="siswa-nisn">NISN: {{ $s->nisn }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($s->jenis_kelamin === 'L')
                            <span style="color:#1d4ed8;font-weight:700;"><i class="fa-solid fa-mars"></i> Laki-laki</span>
                        @else
                            <span style="color:#be185d;font-weight:700;"><i class="fa-solid fa-venus"></i> Perempuan</span>
                        @endif
                    </td>
                    <td style="font-weight:600;color:#334155;">{{ $s->nis ?? '-' }}</td>
                    <td>
                        @if(!empty($s->no_hp_ortu))
                            @php
                                $phone = preg_replace('/[^0-9]/', '', $s->no_hp_ortu);
                                if (str_starts_with($phone, '0')) {
                                    $phone = '62' . substr($phone, 1);
                                }
                                $msg = urlencode("Halo Bapak/Ibu Wali Murid dari ananda {$s->nama_lengkap} (Kelas {$kelas->nama_kelas} SMKN 1 Boyolangu).");
                            @endphp
                            <a href="https://wa.me/{{ $phone }}?text={{ $msg }}" target="_blank" class="btn-wa-link">
                                <i class="fa-brands fa-whatsapp"></i> {{ $s->no_hp_ortu }}
                            </a>
                        @else
                            <span style="color:#94a3b8;font-size:12px;">— Belum ada kontak</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px;color:#64748b;max-width:220px;">{{ $s->alamat ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:54px 20px;color:#94a3b8;">
                        <i class="fa-solid fa-user-xmark" style="font-size:46px;margin-bottom:14px;color:#cbd5e1;display:block;"></i>
                        <p style="font-size:16px;font-weight:800;color:#0f172a;">Tidak ada data siswa ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Custom Pagination Pills --}}
        @if($siswaList->hasPages())
        <div class="pagination-container">
            <span class="pag-text">
                Menampilkan {{ $siswaList->firstItem() ?? 0 }}–{{ $siswaList->lastItem() ?? 0 }} dari {{ $siswaList->total() }} siswa
            </span>
            <div class="pag-pills">
                @if($siswaList->onFirstPage())
                    <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $siswaList->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @foreach($siswaList->getUrlRange(max(1, $siswaList->currentPage() - 2), min($siswaList->lastPage(), $siswaList->currentPage() + 2)) as $page => $url)
                    @if($page == $siswaList->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($siswaList->hasMorePages())
                    <a href="{{ $siswaList->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

@endif

@endsection
