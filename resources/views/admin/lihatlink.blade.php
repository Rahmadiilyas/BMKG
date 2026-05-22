@extends('dashboard')

@section('content')

{{-- ================= CSS KHUSUS ================= --}}
<style>
    /* Variabel Warna */
    :root {
        --accent-lime: #adff2f;
        --bg-card: #1a241b;
        --text-muted: #a0a0a0;
        --danger: #ff4d4d;
        --primary: #2d7fff;
    }

    /* Heading */
    .main-heading {
        font-size: 24px; margin-bottom: 25px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 10px;
    }
    .main-heading span { color: var(--accent-lime); }

    /* Container Tombol Atas */
    .top-actions {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;
    }

    /* STYLE MODERN UNTUK DROPDOWN KATEGORI */
    .select-wrapper {
        position: relative;
        display: inline-block;
    }
    .modern-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: #000;
        border: 1px solid #333;
        color: #fff;
        padding: 10px 40px 10px 15px; /* Kanan lebih lebar untuk panah */
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        outline: none;
        min-width: 200px;
    }
    .modern-select:focus, .modern-select:hover {
        border-color: var(--accent-lime);
        box-shadow: 0 0 10px rgba(173, 255, 47, 0.1);
    }
    /* Panah Custom Menggunakan CSS */
    .select-wrapper::after {
        content: '▼';
        font-size: 10px;
        color: var(--accent-lime);
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    /* Tombol Tambah Neon */
    .btn-neon-add {
        background: var(--accent-lime); color: #000; border: none; padding: 10px 20px;
        border-radius: 10px; font-weight: 800; cursor: pointer; transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 0 15px rgba(173, 255, 47, 0.2);
    }
    .btn-neon-add:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(173, 255, 47, 0.5); }

    /* STYLE MODERN UNTUK LABEL KATEGORI DI TABEL */
    .badge-category {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(173, 255, 47, 0.1); /* Background transparan lime */
        color: var(--accent-lime);
        border: 1px solid rgba(173, 255, 47, 0.2);
    }

    /* Table Styles */
    .table-link { color: #5aa0ff; text-decoration: none; font-weight: 500; transition: .2s; }
    .table-link:hover { color: var(--accent-lime); text-decoration: underline; }
    
    .action-group { display: flex; gap: 8px; }
    .btn-action {
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
        border: 1px solid transparent; cursor: pointer; transition: 0.3s;
        text-transform: uppercase; flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    }
    .btn-edit { background: rgba(45, 127, 255, 0.15); color: #5aa0ff; border-color: rgba(45, 127, 255, 0.3); }
    .btn-edit:hover { background: #2d7fff; color: #fff; }
    .btn-delete { background: rgba(255, 77, 77, 0.15); color: #ff6b6b; border-color: rgba(255, 77, 77, 0.3); }
    .btn-delete:hover { background: var(--danger); color: #fff; }

    /* Modal Styles */
    .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; opacity: 0; transition: opacity 0.3s ease; }
    .modal-active { display: flex; opacity: 1; }
    .modal-box { background: #0f1710; width: 500px; border-radius: 20px; padding: 25px; border: 1px solid rgba(173, 255, 47, 0.3); transform: scale(0.8); transition: transform 0.3s ease; }
    .modal-active .modal-box { transform: scale(1); }
    .modal-header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; }
    .modal-header h4 { margin: 0; color: var(--accent-lime); font-weight: 800; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; color: #ccc; font-size: 12px; margin-bottom: 8px; font-weight: 600; }
    .custom-input { width: 100%; background: #050a06; border: 1px solid #333; padding: 12px; border-radius: 10px; color: #fff; outline: none; transition: 0.3s; }
    .custom-input:focus { border-color: var(--accent-lime); box-shadow: 0 0 10px rgba(173, 255, 47, 0.1); }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
    .btn-cancel { background: transparent; color: #aaa; border: none; padding: 10px 20px; cursor: pointer; font-weight: 600; }
    .btn-save { background: var(--accent-lime); color: #000; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; transition:0.3s; }
    .btn-save:hover { box-shadow: 0 0 15px rgba(173, 255, 47, 0.4); transform: translateY(-2px); }

</style>

<div class="main-heading">
    <i class="fas fa-link"></i>
    Manajemen <span>Link</span>
</div>

{{-- ALERT --}}
@if(session('success'))
<div style="background:rgba(173, 255, 47, 0.1); border:1px solid var(--accent-lime); color: var(--accent-lime); padding:15px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(255, 77, 77, 0.1); border:1px solid var(--danger); color: var(--danger); padding:15px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<div class="card-modern">

    <div class="top-actions">
        {{-- Filter Kategori Modern --}}
        <form method="GET" style="margin:0;">
            <div class="select-wrapper">
                <select name="kategori" class="modern-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Tombol Tambah --}}
        <button onclick="openModal('modalTambahLink')" class="btn-neon-add">
            <i class="fas fa-plus"></i> Tambah Link
        </button>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th width="30%">URL</th>
                    <th class="d-none d-md-table-cell">Keterangan</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($link as $linku)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    
                    {{-- Badge Kategori Modern --}}
                    <td style="vertical-align: middle;">
                        <span class="badge-category">
                            {{ $linku->category->nama_kategori ?? '-' }}
                        </span>
                    </td>
                    
                    <td style="vertical-align: middle; font-weight:600;">{{ $linku->judul_link }}</td>
                    
                    <td style="vertical-align: middle;">
                        <a href="{{ $linku->url }}" target="_blank" class="table-link">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            {{ \Illuminate\Support\Str::limit($linku->url, 35) }}
                        </a>
                    </td>

                    <td class="d-none d-md-table-cell" style="vertical-align: middle; color:#ccc; font-size:13px;">
                        {{ \Illuminate\Support\Str::limit($linku->keterangan, 40) }}
                    </td>

                    <td style="vertical-align: middle;">
                        <div class="action-group">
                            <button onclick="openModal('edit{{ $linku->id }}')" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.deletelink', $linku->id) }}" method="POST" onsubmit="return confirm('Hapus link ini?')" style="margin:0; flex:1; display:flex;">
                                @csrf
                                <button type="submit" class="btn-action btn-delete" style="width:100%;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- MODAL EDIT --}}
                <div class="modal-backdrop" id="edit{{ $linku->id }}">
                    <div class="modal-box">
                        <div class="modal-header">
                            <h4>Edit Link</h4>
                            <button onclick="closeModal('edit{{ $linku->id }}')" style="background:none; border:none; color:#fff; cursor:pointer;"><i class="fas fa-times"></i></button>
                        </div>
                        <form action="{{ route('admin.updatelink', $linku->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori_id" class="custom-input" required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $linku->kategori_id == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Judul Link</label>
                                <input type="text" name="judul_link" class="custom-input" value="{{ $linku->judul_link }}" required>
                            </div>
                            <div class="form-group">
                                <label>URL Tujuan</label>
                                <input type="url" name="url" class="custom-input" value="{{ $linku->url }}" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="custom-input" rows="3">{{ $linku->keterangan }}</textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cancel" onclick="closeModal('edit{{ $linku->id }}')">Batal</button>
                                <button type="submit" class="btn-save">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-link fa-3x mb-3" style="opacity:0.5;"></i><br>Belum ada data link
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-backdrop" id="modalTambahLink">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Tambah Link Baru</h4>
            <button onclick="closeModal('modalTambahLink')" style="background:none; border:none; color:#fff; cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.simpanlink') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="custom-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Judul Link</label>
                <input type="text" name="judul_link" class="custom-input" placeholder="Contoh: Website BMKG Pusat" required>
            </div>
            <div class="form-group">
                <label>URL Tujuan</label>
                <input type="url" name="url" class="custom-input" placeholder="https://..." required>
            </div>
            <div class="form-group">
                <label>Keterangan (Opsional)</label>
                <textarea name="keterangan" class="custom-input" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambahLink')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        let el = document.getElementById(id);
        el.style.display = 'flex';
        setTimeout(() => { el.classList.add('modal-active'); }, 10);
    }
    function closeModal(id) {
        let el = document.getElementById(id);
        el.classList.remove('modal-active');
        setTimeout(() => { el.style.display = 'none'; }, 300);
    }
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            event.target.classList.remove('modal-active');
            setTimeout(() => { event.target.style.display = 'none'; }, 300);
        }
    }
</script>

@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        openModal('modalTambahLink');
    });
</script>
@endif

@endsection