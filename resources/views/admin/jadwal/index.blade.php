@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran — SMKN 1 BOYOLANGU')
@section('header_title', 'Jadwal Pelajaran')
@section('header_subtitle', 'Kelola jadwal mata pelajaran per kelas, hari, jam, dan tahun ajaran')

@section('styles')
<style>
    /* Toast Notification */
    .toast-container { position: fixed; top: 24px; right: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
    .toast {
        display: flex; align-items: center; gap: 12px; background: #ffffff; border-radius: 14px; padding: 14px 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12); border-left: 4px solid #10b981; font-size: 14px; font-weight: 600; color: #0f172a;
        min-width: 320px; animation: toastSlideIn .3s ease;
    }
    .toast.error { border-left-color: #ef4444; }
    .toast i { font-size: 18px; color: #10b981; }
    .toast.error i { color: #ef4444; }
    @keyframes toastSlideIn { from { opacity:0; transform:translateX(40px);} to { opacity:1; transform:translateX(0);} }

    /* Page Action Card */
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

    .btn-add-jadwal {
        background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 11px 22px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(43,67,185,0.25);
    }
    .btn-add-jadwal:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43,67,185,0.35);
        color: #ffffff;
    }

    /* KPI Summary Cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
    .kpi-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.25s ease;
    }
    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.07); border-color: #cbd5e1; }

    .kpi-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .kpi-icon.indigo  { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .kpi-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
    .kpi-icon.sky     { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }
    .kpi-icon.violet  { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #5b21b6; }

    .kpi-val { font-size: 30px; font-weight: 900; color: #0f172a; line-height: 1.1; letter-spacing: -0.5px; }
    .kpi-lbl { font-size: 12px; font-weight: 700; color: #64748b; margin-top: 3px; }
    .kpi-sub { font-size: 11px; font-weight: 600; color: #94a3b8; margin-top: 2px; }

    /* Filter Bar Inputs */
    .filter-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }

    .filter-input, .filter-select {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
        min-width: 150px;
    }
    .filter-input:focus, .filter-select:focus {
        border-color: #2b43b9;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(43,67,185,0.1);
    }

    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
    .search-input-wrap input { padding-left: 36px; }

    .btn-filter-action {
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-filter-action:hover { background: #e2e8f0; color: #0f172a; }

    /* Interactive Tahun Ajaran Pill */
    .btn-ta-manage {
        background: #ffffff;
        border: 1.5px solid #c7d2fe;
        border-radius: 14px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(43,67,185,0.06);
    }
    .btn-ta-manage:hover {
        border-color: #2b43b9;
        background: #f8faff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(43,67,185,0.15);
    }
    .ta-pill-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #3730a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .ta-pill-text {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    .ta-pill-label { font-size: 10.5px; font-weight: 800; color: #6366f1; text-transform: uppercase; letter-spacing: 0.5px; }
    .ta-pill-val { font-size: 13px; font-weight: 800; color: #0f172a; }

    /* Table Section */
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
    .table-title i { color: #2b43b9; }
    .count-badge { font-size: 12px; font-weight: 700; color: #2b43b9; background: #eaeff8; padding: 5px 14px; border-radius: 20px; }

    .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .data-table th {
        font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.6px;
        padding: 12px 16px; text-align: left; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .data-table th:first-child { border-radius: 10px 0 0 10px; }
    .data-table th:last-child  { border-radius: 0 10px 10px 0; }

    .data-table td {
        padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; transition: background 0.15s ease;
    }
    .data-table tbody tr:hover td { background: #f8fafc; }

    /* Hari Badge */
    .badge-hari {
        display: inline-flex; align-items: center; gap: 6px;
        font-weight: 800; font-size: 12px; padding: 5px 14px; border-radius: 20px;
    }
    .badge-hari.senin   { background: #dbeafe; color: #1e40af; }
    .badge-hari.selasa  { background: #d1fae5; color: #065f46; }
    .badge-hari.rabu    { background: #fef3c7; color: #92400e; }
    .badge-hari.kamis   { background: #ede9fe; color: #5b21b6; }
    .badge-hari.jumat   { background: #fce7f3; color: #9d174d; }
    .badge-hari.sabtu   { background: #ffedd5; color: #9a3412; }

    /* Jam Badge */
    .badge-jam {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f1f5f9; color: #334155;
        font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 10px;
    }
    .badge-jam i { font-size: 11px; color: #64748b; }

    /* Tahun Ajaran Badge in Table */
    .badge-ta {
        display: inline-flex; align-items: center; gap: 6px;
        background: #eef2ff; color: #3730a3;
        font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 8px;
    }

    /* Mapel cell */
    .mapel-cell { display: flex; align-items: center; gap: 12px; }
    .mapel-icon {
        width: 38px; height: 38px; border-radius: 12px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3;
        display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 900; flex-shrink: 0;
    }
    .mapel-name { font-weight: 700; color: #0f172a; font-size: 13.5px; }
    .mapel-code { font-size: 11px; color: #64748b; font-weight: 600; margin-top: 1px; }

    /* Guru cell */
    .guru-cell { display: flex; align-items: center; gap: 10px; }
    .guru-avatar-sm {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #c7d2fe, #818cf8); color: #3730a3;
        display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 900; flex-shrink: 0;
    }
    .guru-name-sm { font-weight: 700; color: #0f172a; font-size: 13px; }

    /* Kelas Badge */
    .badge-kelas {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ecfdf5; color: #065f46;
        font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 10px;
    }

    /* Action Buttons */
    .action-btns-group { display: flex; align-items: center; gap: 8px; }
    .btn-table-edit {
        width: 34px; height: 34px; border-radius: 10px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 13px;
        background: #eaeff8; color: #2b43b9; transition: all 0.2s ease;
    }
    .btn-table-edit:hover { background: #2b43b9; color: #ffffff; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(43,67,185,0.25); }

    .btn-table-delete {
        width: 34px; height: 34px; border-radius: 10px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 13px;
        background: #fee2e2; color: #991b1b; transition: all 0.2s ease;
    }
    .btn-table-delete:hover { background: #ef4444; color: #ffffff; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(239,68,68,0.25); }

    /* Pagination */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    .pag-text { font-size: 13px; color: #64748b; font-weight: 600; }
    .pag-pills { display: flex; gap: 6px; }
    .pag-pills a, .pag-pills span {
        display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px;
        font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s ease;
    }
    .pag-pills a { background: #f1f5f9; color: #475569; }
    .pag-pills a:hover { background: #eaeff8; color: #2b43b9; }
    .pag-pills span.active { background: #2b43b9; color: #ffffff; box-shadow: 0 4px 12px rgba(43,67,185,0.25); }
    .pag-pills span.disabled { background: #f8fafc; color: #cbd5e1; cursor: default; }

    /* Modal Form Styles */
    .modal-bd { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 9990; }
    .modal-bd.show { display: flex; }
    .modal-bx {
        background: #ffffff; border-radius: 24px; width: 92%; max-width: 620px; max-height: 90vh; overflow-y: auto; padding: 28px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2); animation: modalSlideUp 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes modalSlideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

    .modal-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; }
    .modal-ttl { font-size: 19px; font-weight: 800; color: #0f172a; }
    .btn-modal-close { background: #f1f5f9; border: none; border-radius: 10px; width: 34px; height: 34px; cursor: pointer; font-size: 16px; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease; }
    .btn-modal-close:hover { background: #fee2e2; color: #ef4444; }

    .form-grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group-item { display: flex; flex-direction: column; gap: 6px; }
    .form-group-item.full-width { grid-column: 1 / -1; }
    .form-field-lbl { font-size: 12px; font-weight: 700; color: #475569; }
    .form-field-ctrl {
        background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px;
        font-size: 13.5px; font-weight: 600; color: #0f172a; outline: none; width: 100%; transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .form-field-ctrl:focus { border-color: #2b43b9; background: #ffffff; box-shadow: 0 0 0 3px rgba(43,67,185,0.1); }

    .form-btn-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
    .btn-modal-cancel { background: #f1f5f9; border: none; border-radius: 12px; padding: 11px 22px; font-size: 13.5px; font-weight: 700; color: #475569; cursor: pointer; }
    .btn-modal-cancel:hover { background: #e2e8f0; }
    .btn-modal-submit { background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%); color: #ffffff; border: none; border-radius: 12px; padding: 11px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(43,67,185,0.25); }

    .delete-confirm-box { text-align: center; padding: 12px 0 20px; }
    .delete-icon-wrap { font-size: 46px; color: #ef4444; margin-bottom: 14px; }
    .delete-target-name { font-weight: 800; color: #0f172a; font-size: 18px; margin-bottom: 8px; }
    .btn-modal-delete-confirm { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; border: none; border-radius: 12px; padding: 11px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; }

    .form-divider { grid-column: 1/-1; border: none; border-top: 1px solid #f1f5f9; margin: 4px 0; }
    .form-section-title { grid-column: 1/-1; font-size: 12px; font-weight: 800; color: #2b43b9; text-transform: uppercase; letter-spacing: .5px; }
    .form-field-hint { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* Custom Mode Toggle inside Set TA Modal */
    .ta-mode-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        gap: 4px;
        margin-bottom: 18px;
    }
    .ta-mode-btn {
        border: none;
        background: none;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }
    .ta-mode-btn.active {
        background: #ffffff;
        color: #2b43b9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    /* Jam Range Pill Selector */
    .jam-pill {
        width: 40px; height: 40px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s ease;
        user-select: none;
    }
    .jam-pill:hover { border-color: #2b43b9; color: #2b43b9; background: #eef2ff; }
    .jam-pill.selected { background: #2b43b9; color: #ffffff; border-color: #2b43b9; box-shadow: 0 2px 8px rgba(43,67,185,0.3); }
    .jam-pill.in-range { background: #e0e7ff; color: #3730a3; border-color: #a5b4fc; }
    .jam-pill.endpoint { background: #2b43b9; color: #ffffff; border-color: #1e40af; box-shadow: 0 2px 8px rgba(43,67,185,0.35); }
</style>
@endsection

@section('content')

{{-- Toast Notification --}}
<div class="toast-container">
    @if(session('success'))
        <div class="toast"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('error') || $errors->any())
        <div class="toast error"><i class="fa-solid fa-circle-xmark"></i><span>{{ session('error') ?? $errors->first() }}</span></div>
    @endif
</div>

{{-- KPI Summary Cards --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon indigo"><i class="fa-solid fa-calendar-days"></i></div>
        <div>
            <div class="kpi-val">{{ number_format($totalJadwal, 0, ',', '.') }}</div>
            <div class="kpi-lbl">Total Jadwal Ditampilkan</div>
            <div class="kpi-sub">{{ $selectedTahunAjaran === 'all' ? 'Seluruh Tahun Ajaran' : ($tahunAjaranAktif ? $tahunAjaranAktif->nama : 'Tahun Ajaran Terpilih') }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon emerald"><i class="fa-solid fa-chalkboard-user"></i></div>
        <div>
            <div class="kpi-val">{{ $totalGuruAktif }}</div>
            <div class="kpi-lbl">Guru Terjadwal</div>
            <div class="kpi-sub">Guru memiliki slot mengajar</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon sky"><i class="fa-solid fa-door-open"></i></div>
        <div>
            <div class="kpi-val">{{ $totalKelasAktif }}</div>
            <div class="kpi-lbl">Kelas Terjadwal</div>
            <div class="kpi-sub">Kelas dengan jadwal aktif</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon violet"><i class="fa-solid fa-book-open"></i></div>
        <div>
            <div class="kpi-val">{{ $totalMapelAktif }}</div>
            <div class="kpi-lbl">Mapel Terjadwal</div>
            <div class="kpi-sub">Mata pelajaran aktif</div>
        </div>
    </div>
</div>

{{-- Filter & Action Bar Card --}}
<form action="{{ route('admin.jadwal') }}" method="GET" id="filterForm">
<div class="page-action-card">
    <div class="filter-group">
        <div class="filter-item">
            <label class="filter-label">Tahun Ajaran</label>
            <select name="tahun_ajaran" class="filter-select" onchange="this.form.submit()">
                <option value="all" {{ $selectedTahunAjaran === 'all' ? 'selected' : '' }}>Semua Tahun Ajaran</option>
                @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta->id }}" {{ $selectedTahunAjaran == $ta->id ? 'selected' : '' }}>
                        {{ $ta->nama }} — {{ $ta->semester }} {{ $ta->is_aktif ? '★ (Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Hari</label>
            <select name="hari" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Hari</option>
                @foreach($hariList as $h)
                    <option value="{{ $h }}" {{ $hari === $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Kelas</label>
            <select name="kelas" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $kelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Pencarian</label>
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ $search }}" class="filter-input" placeholder="Cari mapel, guru, kelas…" id="searchInput">
            </div>
        </div>
        @if($search || $hari || $kelas || $guru || ($selectedTahunAjaran && $selectedTahunAjaran !== ($tahunAjaranAktif->id ?? null)))
            <a href="{{ route('admin.jadwal') }}" class="btn-filter-action" style="align-self:flex-end;">
                <i class="fa-solid fa-xmark"></i> Reset Filter
            </a>
        @endif
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        {{-- Interactive Active Tahun Ajaran Pill --}}
        <button type="button" class="btn-ta-manage" onclick="openSetTAModal()" title="Klik untuk atur atau ganti Tahun Ajaran Aktif">
            <div class="ta-pill-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="ta-pill-text">
                <span class="ta-pill-label">Tahun Ajaran Aktif</span>
                <span class="ta-pill-val">
                    {{ $tahunAjaranAktif ? $tahunAjaranAktif->nama . ' (' . $tahunAjaranAktif->semester . ')' : 'Belum Diatur' }}
                    <i class="fa-solid fa-pen" style="font-size:10px;margin-left:4px;color:#6366f1;"></i>
                </span>
            </div>
        </button>

        <button type="button" class="btn-add-jadwal" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i> Tambah Jadwal
        </button>
    </div>
</div>
</form>

{{-- Table Card --}}
<div class="table-card">
    <div class="table-hdr">
        <div class="table-title">
            <i class="fa-solid fa-table-list"></i>
            <span>Daftar Jadwal Pelajaran</span>
        </div>
        <span class="count-badge"><i class="fa-solid fa-calendar-check"></i> {{ $jadwalList->total() }} Jadwal</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:44px;">#</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Tahun Ajaran</th>
                <th>Mata Pelajaran</th>
                <th>Guru Pengajar</th>
                <th>Kelas</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwalList as $i => $j)
            <tr>
                <td style="color:#94a3b8;font-weight:700;font-size:12px;">{{ $jadwalList->firstItem() + $i }}</td>
                <td>
                    <span class="badge-hari {{ strtolower($j->hari) }}">
                        <i class="fa-solid fa-calendar-day"></i> {{ $j->hari }}
                    </span>
                </td>
                <td>
                    <span class="badge-jam">
                        <i class="fa-regular fa-clock"></i> Jam {{ $j->jam_ke }}
                    </span>
                    <div style="font-size:11px;color:#94a3b8;margin-top:3px;font-weight:600;">
                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                    </div>
                </td>
                <td>
                    <span class="badge-ta">
                        <i class="fa-solid fa-graduation-cap"></i>
                        {{ $j->tahunAjaran->nama ?? '-' }} ({{ $j->tahunAjaran->semester ?? '-' }})
                    </span>
                </td>
                <td>
                    <div class="mapel-cell">
                        <div class="mapel-icon">
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <div>
                            <div class="mapel-name">{{ $j->mapel->nama_mapel ?? '-' }}</div>
                            <div class="mapel-code">{{ $j->mapel->kode_mapel ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($j->guru)
                    <div class="guru-cell">
                        <div class="guru-avatar-sm">{{ strtoupper(substr($j->guru->nama_lengkap, 0, 1)) }}</div>
                        <span class="guru-name-sm">{{ $j->guru->nama_lengkap }}</span>
                    </div>
                    @else
                        <span style="color:#94a3b8;font-size:12px;">— Belum ditentukan</span>
                    @endif
                </td>
                <td>
                    <span class="badge-kelas">
                        <i class="fa-solid fa-door-open"></i>
                        {{ $j->kelas->nama_kelas ?? '-' }}
                    </span>
                </td>
                <td>
                    <div class="action-btns-group" style="justify-content:center;">
                        <button class="btn-table-edit" title="Edit Jadwal" onclick="openEditModal({{ $j->id_jadwal }}, '{{ addslashes($j->tahunAjaran->nama ?? '') }}', '{{ $j->tahunAjaran->semester ?? 'Ganjil' }}', '{{ $j->hari }}', {{ $j->jam_ke }}, {{ $j->jam_ke }}, '{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}', '{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}', {{ $j->id_mapel }}, {{ $j->id_guru }}, {{ $j->id_kelas }})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-table-delete" title="Hapus Jadwal" onclick="openDeleteModal({{ $j->id_jadwal }}, '{{ addslashes($j->mapel->nama_mapel ?? '') }} - {{ addslashes($j->kelas->nama_kelas ?? '') }} ({{ $j->hari }}, Jam ke-{{ $j->jam_ke }})')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:54px 20px;color:#94a3b8;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size:46px;margin-bottom:14px;color:#cbd5e1;display:block;"></i>
                    <p style="font-size:16px;font-weight:800;color:#0f172a;">Tidak ada jadwal pelajaran ditemukan</p>
                    <p style="font-size:13px;margin-top:4px;color:#64748b;">Coba ubah filter tahun ajaran / pencarian atau tambah jadwal baru.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pagination-container">
        <span class="pag-text">
            Menampilkan {{ $jadwalList->firstItem() ?? 0 }}–{{ $jadwalList->lastItem() ?? 0 }} dari {{ $jadwalList->total() }} jadwal
        </span>
        <div class="pag-pills">
            @if($jadwalList->onFirstPage())
                <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $jadwalList->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            @foreach($jadwalList->getUrlRange(max(1,$jadwalList->currentPage()-2), min($jadwalList->lastPage(),$jadwalList->currentPage()+2)) as $page => $url)
                @if($page == $jadwalList->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($jadwalList->hasMorePages())
                <a href="{{ $jadwalList->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

{{-- Add / Edit Jadwal Modal --}}
<div class="modal-bd" id="jadwalModal">
    <div class="modal-bx">
        <div class="modal-hdr">
            <h4 class="modal-ttl" id="modalTitle">Tambah Jadwal Pelajaran</h4>
            <button class="btn-modal-close" onclick="closeModal('jadwalModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="jadwalForm" method="POST" action="{{ route('admin.jadwal.store') }}">
            @csrf
            <span id="methodField"></span>

            <div class="form-grid-layout">
                <div class="form-section-title">Tahun Ajaran &amp; Waktu Pelajaran</div>

                <div class="form-group-item">
                    <label class="form-field-lbl">Tahun Ajaran <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_tahun_ajaran" id="f_nama_ta" class="form-field-ctrl" list="taList" placeholder="Contoh: 2024/2025" required>
                    <datalist id="taList">
                        @foreach($existingTahunList as $t)
                            <option value="{{ $t }}"></option>
                        @endforeach
                    </datalist>
                    <span class="form-field-hint">Ketik manual tahun ajaran baru atau pilih saran.</span>
                </div>

                <div class="form-group-item">
                    <label class="form-field-lbl">Semester <span style="color:#ef4444">*</span></label>
                    <select name="semester" id="f_semester" class="form-field-ctrl" required>
                        <option value="Ganjil">Semester Ganjil</option>
                        <option value="Genap">Semester Genap</option>
                    </select>
                </div>

                <div class="form-group-item">
                    <label class="form-field-lbl">Hari Mengajar <span style="color:#ef4444">*</span></label>
                    <select name="hari" id="f_hari" class="form-field-ctrl" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach($hariList as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Jam Ke- <span style="color:#ef4444">*</span> <span style="color:#64748b;font-weight:600;font-size:11px;">(Klik satu jam, atau klik dua jam berbeda untuk memilih rentang — maks. 4 jam berturut-turut)</span></label>
                    {{-- Hidden inputs untuk jam_dari & jam_sampai --}}
                    <input type="hidden" name="jam_dari" id="f_jam_dari">
                    <input type="hidden" name="jam_sampai" id="f_jam_sampai">
                    <div id="jamSelectorWrap" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                        @for($jNum = 1; $jNum <= 12; $jNum++)
                            <button type="button" class="jam-pill" data-jam="{{ $jNum }}" onclick="toggleJamPill({{ $jNum }})">
                                {{ $jNum }}
                            </button>
                        @endfor
                    </div>
                    <div id="jamRangeLabel" style="font-size:11.5px;color:#2b43b9;font-weight:700;margin-top:6px;"></div>
                    <span class="form-field-hint">Contoh: klik jam 1 lalu klik jam 4 → otomatis pilih jam 1, 2, 3, 4 (4 slot).</span>
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Jam Mulai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_mulai" id="f_jam_mulai" class="form-field-ctrl" required>
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Jam Selesai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_selesai" id="f_jam_selesai" class="form-field-ctrl" required>
                </div>

                <hr class="form-divider">
                <div class="form-section-title">Informasi Pengajar &amp; Kelas</div>

                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Mata Pelajaran <span style="color:#ef4444">*</span></label>
                    <select name="id_mapel" id="f_mapel" class="form-field-ctrl" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id_mapel }}">{{ $m->kode_mapel }} — {{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Guru Pengajar <span style="color:#ef4444">*</span></label>
                    <select name="id_guru" id="f_guru" class="form-field-ctrl" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Kelas <span style="color:#ef4444">*</span></label>
                    <select name="id_kelas" id="f_kelas" class="form-field-ctrl" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-btn-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('jadwalModal')">Batal</button>
                <button type="submit" class="btn-modal-submit" id="submitBtn"><i class="fa-solid fa-floppy-disk"></i> Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- Quick Manage / Set Tahun Ajaran Aktif Modal --}}
<div class="modal-bd" id="setTAModal">
    <div class="modal-bx" style="max-width:480px;">
        <div class="modal-hdr">
            <h4 class="modal-ttl">Atur Tahun Ajaran Aktif</h4>
            <button class="btn-modal-close" onclick="closeModal('setTAModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form method="POST" action="{{ route('admin.jadwal.set-ta') }}">
            @csrf
            <input type="hidden" name="mode" id="ta_mode" value="manual">

            <div class="ta-mode-toggle">
                <button type="button" class="ta-mode-btn active" id="btnModeManual" onclick="switchTAMode('manual')">
                    <i class="fa-solid fa-pen"></i> Input Manual
                </button>
                <button type="button" class="ta-mode-btn" id="btnModeSelect" onclick="switchTAMode('select')">
                    <i class="fa-solid fa-list-check"></i> Pilih yang Ada
                </button>
            </div>

            <div id="taManualSection">
                <div class="form-group-item" style="margin-bottom:14px;">
                    <label class="form-field-lbl">Tahun Ajaran <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_tahun_ajaran" id="quick_ta_nama" class="form-field-ctrl" list="taList" placeholder="Contoh: 2024/2025" value="{{ $tahunAjaranAktif->nama ?? date('Y') . '/' . (date('Y') + 1) }}">
                    <span class="form-field-hint">Ketik manual tahun ajaran baru (otomatis tersimpan ke sistem).</span>
                </div>
                <div class="form-group-item" style="margin-bottom:14px;">
                    <label class="form-field-lbl">Semester <span style="color:#ef4444">*</span></label>
                    <select name="semester" id="quick_ta_semester" class="form-field-ctrl">
                        <option value="Ganjil" {{ ($tahunAjaranAktif->semester ?? '') === 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                        <option value="Genap" {{ ($tahunAjaranAktif->semester ?? '') === 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                    </select>
                </div>
            </div>

            <div id="taSelectSection" style="display:none;">
                <div class="form-group-item" style="margin-bottom:14px;">
                    <label class="form-field-lbl">Pilih dari Daftar Tahun Ajaran</label>
                    <select name="id_tahun_ajaran" id="quick_ta_select" class="form-field-ctrl">
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->is_aktif ? 'selected' : '' }}>
                                {{ $ta->nama }} — {{ $ta->semester }} {{ $ta->is_aktif ? '(Saat Ini Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="background:#f8fafc;border-radius:12px;padding:12px 14px;border:1px solid #e2e8f0;margin-top:14px;font-size:12px;color:#64748b;line-height:1.5;">
                <i class="fa-solid fa-circle-info" style="color:#2b43b9;margin-right:4px;"></i>
                Tahun Ajaran aktif akan menjadi acuan utama jadwal dan jurnal absensi mengajar di seluruh sistem.
            </div>

            <div class="form-btn-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('setTAModal')">Batal</button>
                <button type="submit" class="btn-modal-submit"><i class="fa-solid fa-check"></i> Terapkan Sebagai Aktif</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-bd" id="deleteModal">
    <div class="modal-bx" style="max-width:440px;">
        <div class="modal-hdr">
            <h4 class="modal-ttl">Hapus Jadwal Pelajaran</h4>
            <button class="btn-modal-close" onclick="closeModal('deleteModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="delete-confirm-box">
            <div class="delete-icon-wrap"><i class="fa-solid fa-trash-can"></i></div>
            <div class="delete-target-name" id="deleteName"></div>
            <div style="font-size:13px;color:#64748b;line-height:1.5;">Data jadwal pelajaran ini akan dihapus. Pastikan tidak ada jurnal mengajar yang terkait dengan jadwal ini.</div>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-btn-actions" style="justify-content:center;">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteModal')">Batal</button>
                <button type="submit" class="btn-modal-delete-confirm"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.toast').forEach(t => {
        setTimeout(() => t.style.opacity = '0', 3500);
        setTimeout(() => t.remove(), 3800);
    });

    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    function openSetTAModal() {
        document.getElementById('setTAModal').classList.add('show');
    }

    function switchTAMode(mode) {
        document.getElementById('ta_mode').value = mode;
        const btnManual = document.getElementById('btnModeManual');
        const btnSelect = document.getElementById('btnModeSelect');
        const secManual = document.getElementById('taManualSection');
        const secSelect = document.getElementById('taSelectSection');

        if (mode === 'manual') {
            btnManual.classList.add('active');
            btnSelect.classList.remove('active');
            secManual.style.display = 'block';
            secSelect.style.display = 'none';
        } else {
            btnSelect.classList.add('active');
            btnManual.classList.remove('active');
            secManual.style.display = 'none';
            secSelect.style.display = 'block';
        }
    }

    // ===== JAM RANGE PICKER =====
    let jamDari = null;
    let jamSampai = null;

    function toggleJamPill(jam) {
        if (jamDari === null) {
            // First click — set start
            jamDari = jam;
            jamSampai = jam;
        } else if (jamDari === jam && jamSampai === jam) {
            // Same pill clicked again — deselect
            jamDari = null;
            jamSampai = null;
        } else {
            // Second click — set range
            const lo = Math.min(jamDari, jam);
            const hi = Math.max(jamDari, jam);
            // Limit to max 4 jam
            if (hi - lo + 1 > 4) {
                alert('Maksimal pemilihan adalah 4 jam berturut-turut.');
                return;
            }
            jamDari = lo;
            jamSampai = hi;
        }
        syncJamPills();
    }

    function syncJamPills() {
        document.querySelectorAll('.jam-pill').forEach(btn => {
            const j = parseInt(btn.dataset.jam);
            btn.classList.remove('selected', 'in-range', 'endpoint');
            if (jamDari !== null) {
                if (j === jamDari || j === jamSampai) {
                    btn.classList.add('endpoint');
                } else if (j > jamDari && j < jamSampai) {
                    btn.classList.add('in-range');
                }
            }
        });

        const dari = document.getElementById('f_jam_dari');
        const sampai = document.getElementById('f_jam_sampai');
        const label = document.getElementById('jamRangeLabel');

        if (jamDari !== null) {
            dari.value = jamDari;
            sampai.value = jamSampai;
            if (jamDari === jamSampai) {
                label.textContent = '✔ Dipilih: Jam ke-' + jamDari;
            } else {
                label.textContent = '✔ Dipilih: Jam ke-' + jamDari + ' sampai Jam ke-' + jamSampai + ' (' + (jamSampai - jamDari + 1) + ' jam)';
            }
        } else {
            dari.value = '';
            sampai.value = '';
            label.textContent = '';
        }
    }

    function resetJamPicker() {
        jamDari = null;
        jamSampai = null;
        syncJamPills();
    }

    function setJamPickerRange(dari, sampai) {
        jamDari = parseInt(dari);
        jamSampai = parseInt(sampai);
        syncJamPills();
    }
    // ===========================

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Jadwal Pelajaran';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-plus"></i> Simpan Jadwal';
        const form = document.getElementById('jadwalForm');
        form.action = '{{ route("admin.jadwal.store") }}';
        document.getElementById('methodField').innerHTML = '';
        form.reset();
        resetJamPicker();

        // Pre-fill active tahun ajaran and semester
        @if($tahunAjaranAktif)
        document.getElementById('f_nama_ta').value = '{{ $tahunAjaranAktif->nama }}';
        document.getElementById('f_semester').value = '{{ $tahunAjaranAktif->semester }}';
        @endif

        document.getElementById('jadwalModal').classList.add('show');
    }

    function openEditModal(id, namaTA, semester, hari, jamDariVal, jamSampaiVal, jamMulai, jamSelesai, mapel, guru, kelas) {
        document.getElementById('modalTitle').innerText = 'Edit Jadwal Pelajaran';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Perbarui Jadwal';
        const form = document.getElementById('jadwalForm');
        form.action = '/admin/jadwal/' + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';

        document.getElementById('f_nama_ta').value = namaTA;
        document.getElementById('f_semester').value = semester;
        document.getElementById('f_hari').value = hari;
        document.getElementById('f_jam_mulai').value = jamMulai;
        document.getElementById('f_jam_selesai').value = jamSelesai;
        document.getElementById('f_mapel').value = mapel;
        document.getElementById('f_guru').value = guru;
        document.getElementById('f_kelas').value = kelas;

        setJamPickerRange(jamDariVal, jamSampaiVal);

        document.getElementById('jadwalModal').classList.add('show');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteName').innerText = nama;
        document.getElementById('deleteForm').action = '/admin/jadwal/' + id;
        document.getElementById('deleteModal').classList.add('show');
    }

    ['jadwalModal', 'setTAModal', 'deleteModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if(e.target === this) closeModal(id);
        });
    });

    document.getElementById('searchInput').addEventListener('keydown', function(e) {
        if(e.key === 'Enter') document.getElementById('filterForm').submit();
    });

    // Pre-submit validation: pastikan jam sudah dipilih sebelum form dikirim
    document.getElementById('jadwalForm').addEventListener('submit', function(e) {
        const dari   = document.getElementById('f_jam_dari').value;
        const sampai = document.getElementById('f_jam_sampai').value;
        const label  = document.getElementById('jamRangeLabel');

        if (!dari || !sampai) {
            e.preventDefault();
            label.style.color = '#ef4444';
            label.textContent = '⚠ Wajib memilih setidaknya 1 jam pelajaran dari picker di atas!';
            document.getElementById('jamSelectorWrap').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
        label.style.color = '#2b43b9';
    });
</script>
@endsection
