@extends('layouts.admin')

@section('title', 'Manajemen Kelas — SMKN 1 BOYOLANGU')
@section('header_title', 'Manajemen Kelas')
@section('header_subtitle', 'Kelola data induk rombongan belajar — tambah, ubah, dan hapus data')

@section('styles')
<style>
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

    .page-action-card {
        background: #ffffff; border-radius: 18px; padding: 18px 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;
        margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }

    .btn-add {
        background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%); color: #ffffff; border: none;
        border-radius: 12px; padding: 11px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(43,67,185,0.25);
    }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(43,67,185,0.35); color: #ffffff; }

    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
    .kpi-card {
        background: #ffffff; border-radius: 18px; padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 16px; transition: all 0.25s ease;
    }
    .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.07); border-color: #cbd5e1; }
    .kpi-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .kpi-icon.indigo  { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .kpi-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
    .kpi-icon.sky     { background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; }
    .kpi-icon.violet  { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #5b21b6; }
    .kpi-val { font-size: 30px; font-weight: 900; color: #0f172a; line-height: 1.1; letter-spacing: -0.5px; }
    .kpi-lbl { font-size: 12px; font-weight: 700; color: #64748b; margin-top: 3px; }
    .kpi-sub { font-size: 11px; font-weight: 600; color: #94a3b8; margin-top: 2px; }

    .filter-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }
    .filter-input, .filter-select {
        background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px;
        font-size: 13px; font-weight: 600; color: #0f172a; outline: none; transition: all 0.2s ease; min-width: 170px;
    }
    .filter-input:focus, .filter-select:focus { border-color: #2b43b9; background: #ffffff; box-shadow: 0 0 0 3px rgba(43,67,185,0.1); }
    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
    .search-input-wrap input { padding-left: 36px; }
    .btn-filter-action {
        background: #f1f5f9; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 18px;
        font-size: 13px; font-weight: 700; color: #475569; cursor: pointer; transition: all 0.2s ease; text-decoration: none;
    }
    .btn-filter-action:hover { background: #e2e8f0; color: #0f172a; }

    .table-card { background: #ffffff; border-radius: 20px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; }
    .table-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9; }
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
    .data-table td { padding: 16px; font-size: 13.5px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; transition: background 0.15s ease; }
    .data-table tbody tr:hover td { background: #f8fafc; }

    .kelas-cell { display: flex; align-items: center; gap: 14px; }
    .kelas-avatar {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, #bae6fd, #7dd3fc); color: #0369a1;
        display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 900; flex-shrink: 0;
    }
    .kelas-nama { font-weight: 700; color: #0f172a; font-size: 14px; }
    .kelas-tingkat { font-size: 11.5px; color: #64748b; font-weight: 600; margin-top: 1px; }

    .jurusan-pill { background: #e0e7ff; color: #3730a3; font-weight: 800; font-size: 12px; padding: 5px 12px; border-radius: 8px; display: inline-block; }
    .tingkat-pill { background: #fef3c7; color: #92400e; font-weight: 800; font-size: 12px; padding: 5px 12px; border-radius: 8px; display: inline-block; }
    .count-pill { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 8px; }

    .action-btns-group { display: flex; align-items: center; gap: 8px; }
    .btn-table-edit { width: 36px; height: 36px; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; background: #eaeff8; color: #2b43b9; transition: all 0.2s ease; }
    .btn-table-edit:hover { background: #2b43b9; color: #ffffff; transform: translateY(-1px); }
    .btn-table-delete { width: 36px; height: 36px; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; background: #fee2e2; color: #991b1b; transition: all 0.2s ease; }
    .btn-table-delete:hover { background: #ef4444; color: #ffffff; transform: translateY(-1px); }

    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    .pag-text { font-size: 13px; color: #64748b; font-weight: 600; }
    .pag-pills { display: flex; gap: 6px; }
    .pag-pills a, .pag-pills span { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s ease; }
    .pag-pills a { background: #f1f5f9; color: #475569; }
    .pag-pills a:hover { background: #eaeff8; color: #2b43b9; }
    .pag-pills span.active { background: #2b43b9; color: #ffffff; box-shadow: 0 4px 12px rgba(43,67,185,0.25); }
    .pag-pills span.disabled { background: #f8fafc; color: #cbd5e1; cursor: default; }

    .modal-bd { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 9990; }
    .modal-bd.show { display: flex; }
    .modal-bx { background: #ffffff; border-radius: 24px; width: 92%; max-width: 580px; max-height: 90vh; overflow-y: auto; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); animation: modalSlideUp 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes modalSlideUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    .modal-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; }
    .modal-ttl { font-size: 19px; font-weight: 800; color: #0f172a; }
    .btn-modal-close { background: #f1f5f9; border: none; border-radius: 10px; width: 34px; height: 34px; cursor: pointer; font-size: 16px; color: #64748b; display: flex; align-items: center; justify-content: center; }
    .btn-modal-close:hover { background: #fee2e2; color: #ef4444; }
    .form-grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group-item { display: flex; flex-direction: column; gap: 6px; }
    .form-group-item.full-width { grid-column: 1 / -1; }
    .form-field-lbl { font-size: 12px; font-weight: 700; color: #475569; }
    .form-field-ctrl { background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px; font-size: 13.5px; font-weight: 600; color: #0f172a; outline: none; width: 100%; transition: all 0.2s ease; }
    .form-field-ctrl:focus { border-color: #2b43b9; background: #ffffff; box-shadow: 0 0 0 3px rgba(43,67,185,0.1); }
    .form-btn-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
    .btn-modal-cancel { background: #f1f5f9; border: none; border-radius: 12px; padding: 11px 22px; font-size: 13.5px; font-weight: 700; color: #475569; cursor: pointer; }
    .btn-modal-submit { background: linear-gradient(135deg, #2b43b9 0%, #1e293b 100%); color: #ffffff; border: none; border-radius: 12px; padding: 11px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(43,67,185,0.25); }
    .delete-confirm-box { text-align: center; padding: 12px 0 20px; }
    .delete-icon-wrap { font-size: 46px; color: #ef4444; margin-bottom: 14px; }
    .delete-target-name { font-weight: 800; color: #0f172a; font-size: 18px; margin-bottom: 8px; }
    .btn-modal-delete-confirm { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; border: none; border-radius: 12px; padding: 11px 24px; font-size: 13.5px; font-weight: 700; cursor: pointer; }
</style>
@endsection

@section('content')

<div class="toast-container">
    @if(session('success'))
        <div class="toast"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('error') || $errors->any())
        <div class="toast error"><i class="fa-solid fa-circle-xmark"></i><span>{{ session('error') ?? $errors->first() }}</span></div>
    @endif
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon indigo"><i class="fa-solid fa-door-open"></i></div>
        <div>
            <div class="kpi-val">{{ number_format($totalKelas, 0, ',', '.') }}</div>
            <div class="kpi-lbl">Total Rombel</div>
            <div class="kpi-sub">Seluruh kelas sekolah</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon emerald"><i class="fa-solid fa-1"></i></div>
        <div>
            <div class="kpi-val">{{ $tingkatX }}</div>
            <div class="kpi-lbl">Kelas X</div>
            <div class="kpi-sub">Tingkat pertama</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon sky"><i class="fa-solid fa-2"></i></div>
        <div>
            <div class="kpi-val">{{ $tingkatXI }}</div>
            <div class="kpi-lbl">Kelas XI</div>
            <div class="kpi-sub">Tingkat kedua</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon violet"><i class="fa-solid fa-3"></i></div>
        <div>
            <div class="kpi-val">{{ $tingkatXII }}</div>
            <div class="kpi-lbl">Kelas XII</div>
            <div class="kpi-sub">Tingkat ketiga</div>
        </div>
    </div>
</div>

<form action="{{ route('admin.master.kelas') }}" method="GET" id="filterForm">
<div class="page-action-card">
    <div class="filter-group">
        <div class="filter-item">
            <label class="filter-label">Pencarian Kelas</label>
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ $search }}" class="filter-input" placeholder="Cari Nama Kelas…" id="searchInput">
            </div>
        </div>
        <div class="filter-item">
            <label class="filter-label">Tingkat</label>
            <select name="tingkat" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Tingkat</option>
                <option value="X" {{ $tingkat === 'X' ? 'selected' : '' }}>Kelas X</option>
                <option value="XI" {{ $tingkat === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                <option value="XII" {{ $tingkat === 'XII' ? 'selected' : '' }}>Kelas XII</option>
            </select>
        </div>
        <div class="filter-item">
            <label class="filter-label">Jurusan</label>
            <select name="id_jurusan" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Jurusan</option>
                @foreach($jurusanList as $j)
                    <option value="{{ $j->id_jurusan }}" {{ $jurusanId == $j->id_jurusan ? 'selected' : '' }}>{{ $j->kode_jurusan }} — {{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>
        @if($search || $tingkat || $jurusanId)
            <a href="{{ route('admin.master.kelas') }}" class="btn-filter-action" style="align-self:flex-end;">
                <i class="fa-solid fa-xmark"></i> Reset Filter
            </a>
        @endif
    </div>
    <div>
        <button type="button" class="btn-add" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
        </button>
    </div>
</div>
</form>

<div class="table-card">
    <div class="table-hdr">
        <div class="table-title">
            <i class="fa-solid fa-door-open"></i>
            <span>Daftar Data Induk Rombongan Belajar</span>
        </div>
        <span class="count-badge"><i class="fa-solid fa-school"></i> {{ $kelasList->total() }} Kelas Ditemukan</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:48px;">#</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Wali Kelas</th>
                <th>Jumlah Siswa</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kelasList as $i => $k)
            <tr>
                <td style="color:#94a3b8;font-weight:700;font-size:12px;">{{ $kelasList->firstItem() + $i }}</td>
                <td>
                    <div class="kelas-cell">
                        <div class="kelas-avatar">{{ $k->tingkat }}</div>
                        <div>
                            <div class="kelas-nama">{{ $k->nama_kelas }}</div>
                            <div class="kelas-tingkat">Rombongan Belajar</div>
                        </div>
                    </div>
                </td>
                <td><span class="tingkat-pill">{{ $k->tingkat }}</span></td>
                <td>
                    <span class="jurusan-pill">{{ $k->jurusanRelation->kode_jurusan ?? $k->jurusan }}</span>
                    @if($k->jurusanRelation)
                        <div style="font-size:11px;color:#64748b;margin-top:4px;font-weight:600;">{{ $k->jurusanRelation->nama_jurusan }}</div>
                    @endif
                </td>
                <td style="font-weight:600;color:#334155;">
                    @if($k->waliKelas)
                        <span style="display:inline-flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-user-tie" style="color:#2b43b9;"></i>
                            {{ $k->waliKelas->nama_lengkap }}
                        </span>
                    @else
                        <span style="color:#94a3b8;"><i class="fa-solid fa-circle-minus"></i> Belum Ditentukan</span>
                    @endif
                </td>
                <td><span class="count-pill"><i class="fa-solid fa-users"></i> {{ $k->siswa_count }} Siswa</span></td>
                <td>
                    <div class="action-btns-group" style="justify-content:center;">
                        <button class="btn-table-edit" title="Edit Data" onclick="openEditModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}', '{{ $k->tingkat }}', '{{ $k->id_jurusan }}', '{{ $k->wali_kelas_id }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-table-delete" title="Hapus Data" onclick="openDeleteModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:54px 20px;color:#94a3b8;">
                    <i class="fa-solid fa-door-open" style="font-size:46px;margin-bottom:14px;color:#cbd5e1;"></i>
                    <p style="font-size:16px;font-weight:800;color:#0f172a;">Tidak ada data kelas ditemukan</p>
                    <p style="font-size:13px;margin-top:4px;color:#64748b;">Coba ubah kata kunci pencarian atau tambah kelas baru.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        <span class="pag-text">
            Menampilkan {{ $kelasList->firstItem() ?? 0 }}–{{ $kelasList->lastItem() ?? 0 }} dari {{ $kelasList->total() }} kelas
        </span>
        <div class="pag-pills">
            @if($kelasList->onFirstPage())
                <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $kelasList->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @endif
            @foreach($kelasList->getUrlRange(max(1,$kelasList->currentPage()-2), min($kelasList->lastPage(),$kelasList->currentPage()+2)) as $page => $url)
                @if($page == $kelasList->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
            @if($kelasList->hasMorePages())
                <a href="{{ $kelasList->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

<div class="modal-bd" id="kelasModal">
    <div class="modal-bx">
        <div class="modal-hdr">
            <h4 class="modal-ttl" id="modalTitle">Tambah Kelas Baru</h4>
            <button class="btn-modal-close" onclick="closeModal('kelasModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="kelasForm" method="POST" action="{{ route('admin.master.kelas.store') }}">
            @csrf
            <span id="methodField"></span>
            <div class="form-grid-layout">
                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Nama Kelas / Rombel <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_kelas" id="f_nama" class="form-field-ctrl" placeholder="Contoh: XII RPL 1" required maxlength="30">
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Tingkat <span style="color:#ef4444">*</span></label>
                    <select name="tingkat" id="f_tingkat" class="form-field-ctrl" required>
                        <option value="">-- Pilih --</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Jurusan <span style="color:#ef4444">*</span></label>
                    <select name="id_jurusan" id="f_jurusan" class="form-field-ctrl" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusanList as $j)
                            <option value="{{ $j->id_jurusan }}">{{ $j->kode_jurusan }} — {{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Wali Kelas</label>
                    <select name="wali_kelas_id" id="f_wali" class="form-field-ctrl">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach($waliList as $w)
                            <option value="{{ $w->id }}">{{ $w->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-btn-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('kelasModal')">Batal</button>
                <button type="submit" class="btn-modal-submit" id="submitBtn"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-bd" id="deleteModal">
    <div class="modal-bx" style="max-width:440px;">
        <div class="modal-hdr">
            <h4 class="modal-ttl">Hapus Data Kelas</h4>
            <button class="btn-modal-close" onclick="closeModal('deleteModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="delete-confirm-box">
            <div class="delete-icon-wrap"><i class="fa-solid fa-trash-can"></i></div>
            <div class="delete-target-name" id="deleteName"></div>
            <div style="font-size:13px;color:#64748b;line-height:1.5;">Kelas yang masih memiliki siswa terdaftar tidak dapat dihapus.</div>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="form-btn-actions" style="justify-content:center;">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('deleteModal')">Batal</button>
                <button type="submit" class="btn-modal-delete-confirm"><i class="fa-solid fa-trash"></i> Ya, Hapus Data</button>
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

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Kelas Baru';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-plus"></i> Simpan Data';
        const form = document.getElementById('kelasForm');
        form.action = '{{ route("admin.master.kelas.store") }}';
        document.getElementById('methodField').innerHTML = '';
        form.reset();
        document.getElementById('kelasModal').classList.add('show');
    }

    function openEditModal(id, nama, tingkat, jurusanId, waliId) {
        document.getElementById('modalTitle').innerText = 'Edit Data Kelas';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Perbarui Data';
        const form = document.getElementById('kelasForm');
        form.action = '/admin/master/kelas/' + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('f_nama').value = nama;
        document.getElementById('f_tingkat').value = tingkat;
        document.getElementById('f_jurusan').value = jurusanId || '';
        document.getElementById('f_wali').value = waliId || '';
        document.getElementById('kelasModal').classList.add('show');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteName').innerText = nama;
        document.getElementById('deleteForm').action = '/admin/master/kelas/' + id;
        document.getElementById('deleteModal').classList.add('show');
    }

    ['kelasModal','deleteModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if(e.target === this) closeModal(id);
        });
    });

    document.getElementById('searchInput').addEventListener('keydown', function(e) {
        if(e.key === 'Enter') document.getElementById('filterForm').submit();
    });
</script>
@endsection
