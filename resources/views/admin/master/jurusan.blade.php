@extends('layouts.admin')

@section('title', 'Manajemen Jurusan — SMKN 1 BOYOLANGU')
@section('header_title', 'Manajemen Jurusan')
@section('header_subtitle', 'Kelola data induk jurusan — tambah, ubah, dan hapus data')

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
    .kpi-icon.rose    { background: linear-gradient(135deg, #ffe4e6, #fecdd3); color: #9f1239; }
    .kpi-icon.amber   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
    .kpi-val { font-size: 30px; font-weight: 900; color: #0f172a; line-height: 1.1; letter-spacing: -0.5px; }
    .kpi-lbl { font-size: 12px; font-weight: 700; color: #64748b; margin-top: 3px; }
    .kpi-sub { font-size: 11px; font-weight: 600; color: #94a3b8; margin-top: 2px; }

    .filter-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1; }
    .filter-item { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: .5px; }
    .filter-input, .filter-select {
        background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 10px 14px;
        font-size: 13px; font-weight: 600; color: #0f172a; outline: none; transition: all 0.2s ease; min-width: 200px;
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

    .jurusan-cell { display: flex; align-items: center; gap: 14px; }
    .jurusan-avatar {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, #c7d2fe, #818cf8); color: #3730a3;
        display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 900; flex-shrink: 0;
    }
    .jurusan-kode { font-weight: 800; color: #0f172a; font-size: 14px; }
    .jurusan-nama { font-size: 11.5px; color: #64748b; font-weight: 600; margin-top: 1px; }

    .kode-pill { background: #e0e7ff; color: #3730a3; font-weight: 800; font-size: 12px; padding: 5px 12px; border-radius: 8px; display: inline-block; }
    .count-pill { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 8px; }

    .badge-status-aktif { display: inline-flex; align-items: center; gap: 6px; background: #d1fae5; color: #065f46; font-weight: 800; font-size: 11.5px; padding: 5px 12px; border-radius: 20px; }
    .badge-status-nonaktif { display: inline-flex; align-items: center; gap: 6px; background: #fee2e2; color: #991b1b; font-weight: 800; font-size: 11.5px; padding: 5px 12px; border-radius: 20px; }
    .status-dot { width: 7px; height: 7px; border-radius: 50%; }
    .status-dot.green { background: #10b981; box-shadow: 0 0 6px #10b981; }
    .status-dot.red   { background: #ef4444; box-shadow: 0 0 6px #ef4444; }

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
        <div class="kpi-icon indigo"><i class="fa-solid fa-layer-group"></i></div>
        <div>
            <div class="kpi-val">{{ number_format($totalJurusan, 0, ',', '.') }}</div>
            <div class="kpi-lbl">Total Jurusan</div>
            <div class="kpi-sub">Program keahlian SMK</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon emerald"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            <div class="kpi-val">{{ $aktif }}</div>
            <div class="kpi-lbl">Jurusan Aktif</div>
            <div class="kpi-sub">Sedang beroperasi</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon rose"><i class="fa-solid fa-circle-xmark"></i></div>
        <div>
            <div class="kpi-val">{{ $nonAktif }}</div>
            <div class="kpi-lbl">Jurusan Nonaktif</div>
            <div class="kpi-sub">Tidak beroperasi</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon amber"><i class="fa-solid fa-school"></i></div>
        <div>
            <div class="kpi-val">{{ $totalKelas }}</div>
            <div class="kpi-lbl">Total Rombel</div>
            <div class="kpi-sub">Terhubung ke jurusan</div>
        </div>
    </div>
</div>

<form action="{{ route('admin.master.jurusan') }}" method="GET" id="filterForm">
<div class="page-action-card">
    <div class="filter-group">
        <div class="filter-item">
            <label class="filter-label">Pencarian Jurusan</label>
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ $search }}" class="filter-input" placeholder="Cari Kode atau Nama Jurusan…" id="searchInput">
            </div>
        </div>
        <div class="filter-item">
            <label class="filter-label">Status Jurusan</label>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>
        @if($search || ($status !== null && $status !== ''))
            <a href="{{ route('admin.master.jurusan') }}" class="btn-filter-action" style="align-self:flex-end;">
                <i class="fa-solid fa-xmark"></i> Reset Filter
            </a>
        @endif
    </div>
    <div>
        <button type="button" class="btn-add" onclick="openAddModal()">
            <i class="fa-solid fa-plus"></i> Tambah Jurusan Baru
        </button>
    </div>
</div>
</form>

<div class="table-card">
    <div class="table-hdr">
        <div class="table-title">
            <i class="fa-solid fa-layer-group"></i>
            <span>Daftar Data Induk Jurusan</span>
        </div>
        <span class="count-badge"><i class="fa-solid fa-graduation-cap"></i> {{ $jurusanList->total() }} Jurusan Ditemukan</span>
    </div>

    <div class="table-responsive-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:48px;">#</th>
                <th>Kode &amp; Nama Jurusan</th>
                <th>Deskripsi</th>
                <th>Jumlah Kelas</th>
                <th>Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurusanList as $i => $j)
            <tr>
                <td style="color:#94a3b8;font-weight:700;font-size:12px;">{{ $jurusanList->firstItem() + $i }}</td>
                <td>
                    <div class="jurusan-cell">
                        <div class="jurusan-avatar">{{ $j->kode_jurusan }}</div>
                        <div>
                            <div class="jurusan-kode">{{ $j->kode_jurusan }}</div>
                            <div class="jurusan-nama">{{ $j->nama_jurusan }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#64748b;font-weight:500;max-width:280px;">{{ $j->deskripsi ? Str::limit($j->deskripsi, 60) : '-' }}</td>
                <td><span class="count-pill"><i class="fa-solid fa-door-open"></i> {{ $j->kelas_count }} Kelas</span></td>
                <td>
                    @if($j->status_aktif)
                        <span class="badge-status-aktif"><span class="status-dot green"></span> Aktif</span>
                    @else
                        <span class="badge-status-nonaktif"><span class="status-dot red"></span> Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns-group" style="justify-content:center;">
                        <button class="btn-table-edit" title="Edit Data" onclick="openEditModal({{ $j->id_jurusan }}, '{{ addslashes($j->kode_jurusan) }}', '{{ addslashes($j->nama_jurusan) }}', '{{ addslashes($j->deskripsi ?? '') }}', {{ $j->status_aktif ? 'true' : 'false' }})">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-table-delete" title="Hapus Data" onclick="openDeleteModal({{ $j->id_jurusan }}, '{{ addslashes($j->kode_jurusan) }}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:54px 20px;color:#94a3b8;">
                    <i class="fa-solid fa-layer-group" style="font-size:46px;margin-bottom:14px;color:#cbd5e1;"></i>
                    <p style="font-size:16px;font-weight:800;color:#0f172a;">Tidak ada data jurusan ditemukan</p>
                    <p style="font-size:13px;margin-top:4px;color:#64748b;">Coba ubah kata kunci pencarian atau tambah jurusan baru.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>{{-- end table-responsive-wrap --}}

    <div class="pagination-container">
        <span class="pag-text">
            Menampilkan {{ $jurusanList->firstItem() ?? 0 }}–{{ $jurusanList->lastItem() ?? 0 }} dari {{ $jurusanList->total() }} jurusan
        </span>
        <div class="pag-pills">
            @if($jurusanList->onFirstPage())
                <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $jurusanList->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @endif
            @foreach($jurusanList->getUrlRange(max(1,$jurusanList->currentPage()-2), min($jurusanList->lastPage(),$jurusanList->currentPage()+2)) as $page => $url)
                @if($page == $jurusanList->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
            @if($jurusanList->hasMorePages())
                <a href="{{ $jurusanList->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

<div class="modal-bd" id="jurusanModal">
    <div class="modal-bx">
        <div class="modal-hdr">
            <h4 class="modal-ttl" id="modalTitle">Tambah Jurusan Baru</h4>
            <button class="btn-modal-close" onclick="closeModal('jurusanModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="jurusanForm" method="POST" action="{{ route('admin.master.jurusan.store') }}">
            @csrf
            <span id="methodField"></span>
            <div class="form-grid-layout">
                <div class="form-group-item">
                    <label class="form-field-lbl">Kode Jurusan <span style="color:#ef4444">*</span></label>
                    <input type="text" name="kode_jurusan" id="f_kode" class="form-field-ctrl" placeholder="Contoh: RPL" required maxlength="20">
                </div>
                <div class="form-group-item">
                    <label class="form-field-lbl">Status Jurusan</label>
                    <select name="status_aktif" id="f_status" class="form-field-ctrl">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Nama Jurusan <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_jurusan" id="f_nama" class="form-field-ctrl" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                </div>
                <div class="form-group-item full-width">
                    <label class="form-field-lbl">Deskripsi</label>
                    <textarea name="deskripsi" id="f_deskripsi" class="form-field-ctrl" rows="3" placeholder="Deskripsi singkat jurusan (opsional)"></textarea>
                </div>
            </div>
            <div class="form-btn-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('jurusanModal')">Batal</button>
                <button type="submit" class="btn-modal-submit" id="submitBtn"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-bd" id="deleteModal">
    <div class="modal-bx" style="max-width:440px;">
        <div class="modal-hdr">
            <h4 class="modal-ttl">Hapus Data Jurusan</h4>
            <button class="btn-modal-close" onclick="closeModal('deleteModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="delete-confirm-box">
            <div class="delete-icon-wrap"><i class="fa-solid fa-trash-can"></i></div>
            <div class="delete-target-name" id="deleteName"></div>
            <div style="font-size:13px;color:#64748b;line-height:1.5;">Jurusan yang masih memiliki kelas terdaftar tidak dapat dihapus.</div>
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
        document.getElementById('modalTitle').innerText = 'Tambah Jurusan Baru';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-plus"></i> Simpan Data';
        const form = document.getElementById('jurusanForm');
        form.action = '{{ route("admin.master.jurusan.store") }}';
        document.getElementById('methodField').innerHTML = '';
        form.reset();
        document.getElementById('jurusanModal').classList.add('show');
    }

    function openEditModal(id, kode, nama, deskripsi, aktif) {
        document.getElementById('modalTitle').innerText = 'Edit Data Jurusan';
        document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Perbarui Data';
        const form = document.getElementById('jurusanForm');
        form.action = '/admin/master/jurusan/' + id;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('f_kode').value = kode;
        document.getElementById('f_nama').value = nama;
        document.getElementById('f_deskripsi').value = deskripsi;
        document.getElementById('f_status').value = aktif ? '1' : '0';
        document.getElementById('jurusanModal').classList.add('show');
    }

    function openDeleteModal(id, kode) {
        document.getElementById('deleteName').innerText = kode;
        document.getElementById('deleteForm').action = '/admin/master/jurusan/' + id;
        document.getElementById('deleteModal').classList.add('show');
    }

    ['jurusanModal','deleteModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if(e.target === this) closeModal(id);
        });
    });

    document.getElementById('searchInput').addEventListener('keydown', function(e) {
        if(e.key === 'Enter') document.getElementById('filterForm').submit();
    });
</script>
@endsection
