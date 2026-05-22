@extends('dashboard')

@section('content')

{{-- === CSS KHUSUS HALAMAN INI (ADAPTASI DARI MANAJEMEN KATEGORI) === --}}
<style>
    /* Variabel Warna */
    :root {
        --accent-lime: #adff2f;
        --bg-card: #1a241b;
        --text-muted: #a0a0a0;
        --danger: #ff4d4d;
        --primary: #2d7fff;
    }

    /* Heading Style */
    .main-heading {
        font-size: 24px;
        margin-bottom: 25px;
        font-weight: 800;
        color: #fff;
        display: flex; align-items: center; gap: 10px;
    }
    .main-heading span { color: var(--accent-lime); }

    /* Card Container */
    .card-modern {
        background: var(--bg-card);
        border: 1px solid rgba(173, 255, 47, 0.15);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    /* Tombol Aksi di Tabel */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
    }
    
    .btn-action {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-edit {
        background: rgba(45, 127, 255, 0.15);
        color: #5aa0ff;
        border-color: rgba(45, 127, 255, 0.3);
    }
    .btn-edit:hover {
        background: #2d7fff;
        color: #fff;
        box-shadow: 0 0 10px rgba(45, 127, 255, 0.5);
    }

    .btn-delete {
        background: rgba(255, 77, 77, 0.15);
        color: #ff6b6b;
        border-color: rgba(255, 77, 77, 0.3);
    }
    .btn-delete:hover {
        background: var(--danger);
        color: #fff;
        box-shadow: 0 0 10px rgba(255, 77, 77, 0.5);
    }

    /* Tombol Tambah Neon */
    .btn-neon-add {
        background: var(--accent-lime);
        color: #000;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 0 15px rgba(173, 255, 47, 0.2);
    }
    .btn-neon-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(173, 255, 47, 0.5);
    }

    /* === MODERN MODAL STYLE (FIXED) === */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        display: none; /* PENTING: Default Hidden */
        align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-active { display: flex; opacity: 1; }

    .modal-box {
        background: #0f1710;
        width: 450px;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(173, 255, 47, 0.3);
        box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        transform: scale(0.8);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 90vh; 
        overflow-y: auto;
    }

    .modal-active .modal-box { transform: scale(1); }

    .modal-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;
    }
    .modal-header h4 { margin: 0; color: var(--accent-lime); font-weight: 800; letter-spacing: 1px; }

    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; color: #ccc; font-size: 12px; margin-bottom: 8px; font-weight: 600; }
    
    .custom-input {
        width: 100%;
        background: #050a06;
        border: 1px solid #333;
        padding: 12px;
        border-radius: 10px;
        color: #fff;
        outline: none;
        transition: 0.3s;
    }
    .custom-input:focus {
        border-color: var(--accent-lime);
        box-shadow: 0 0 10px rgba(173, 255, 47, 0.1);
    }

    .custom-file-upload {
        border: 2px dashed #333;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        background: rgba(255,255,255,0.02);
        transition: 0.3s;
    }
    .custom-file-upload:hover { border-color: var(--accent-lime); background: rgba(173, 255, 47, 0.05); }

    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
    .btn-cancel { background: transparent; color: #aaa; border: none; padding: 10px 20px; cursor: pointer; font-weight: 600; }
    .btn-cancel:hover { color: #fff; }
    .btn-save { background: var(--accent-lime); color: #000; border: none; padding: 10px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; transition:0.3s; }
    .btn-save:hover { box-shadow: 0 0 15px rgba(173, 255, 47, 0.4); transform: translateY(-2px); }

    /* Custom Table Override (Sesuai Permintaan) */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    /* Header Abu-abu, Uppercase, Spacing */
    .custom-table th { 
    text-align: left; 
    padding: 15px; 
    color: var(--accent-lime); /* HIJAU */
    font-size: 12px; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    font-weight: 700;
}

    .custom-table td { background: #111; padding: 15px; border-top: 1px solid #222; border-bottom: 1px solid #222; color: #eee; }
    .custom-table td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; border-left: 1px solid #222; }
    .custom-table td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-right: 1px solid #222; }

</style>

<div class="main-heading">
    <i class="fas fa-users-cog"></i>
    Manajemen <span>Teknisi</span>
</div>

{{-- ================= ALERT MESSAGES ================= --}}
@if(session('success'))
<div style="background:rgba(173, 255, 47, 0.1); border:1px solid var(--accent-lime); color: var(--accent-lime); padding:15px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div style="background:rgba(255, 77, 77, 0.1); border:1px solid var(--danger); color: var(--danger); padding:15px; border-radius:12px; margin-bottom:20px;">
    <ul class="mb-0 small" style="margin:0; padding-left:20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ================= CARD CONTENT ================= --}}
<div class="card-modern">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h4 style="margin:0; font-size:16px; color:var(--accent-lime); letter-spacing:0.5px;">
            <i class="fas fa-user-tie mr-2"></i> DATA TEKNISI
        </h4>

        <button onclick="openModal('modalTambah')" class="btn-neon-add">
            <i class="fas fa-plus"></i> Tambah Teknisi
        </button>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">NO</th>
                    <th width="30%">NAMA & KONTAK</th>
                    <th width="20%">NO. HP</th>
                    <th width="15%" class="text-center">FOTO</th>
                    <th width="20%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teknisi as $tk)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    
                    {{-- Nama & Email --}}
                    <td style="vertical-align: middle;">
                        <div style="font-weight:700; font-size:14px; color:#fff;">{{ $tk->nama_teknisi }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">{{ $tk->email ?? '-' }}</div>
                    </td>

                    {{-- No HP --}}
                    <td style="vertical-align: middle; color:var(--accent-lime); font-family:monospace; font-size:13px;">
                        {{ $tk->no_hp }}
                    </td>

                    {{-- Foto --}}
                    <td class="text-center" style="vertical-align: middle;">
                        <img src="{{ asset('uploads/teknisi/'.$tk->gambar) }}"
                             class="img-avatar"
                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border:1px solid var(--accent-lime);"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($tk->nama_teknisi) }}&background=adff2f&color=000'">
                    </td>

                    {{-- Aksi --}}
                    <td style="vertical-align: middle;">
                        <div class="action-group">
                            {{-- Tombol Edit --}}
                            <button onclick="openModal('edit{{ $tk->id }}')" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.deleteteknisi', $tk->id) }}" method="POST" onsubmit="return confirm('Hapus teknisi {{ $tk->nama_teknisi }}?')" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- ================= MODAL EDIT (Hidden by default) ================= --}}
                <div class="modal-backdrop" id="edit{{ $tk->id }}">
                    <div class="modal-box">
                        <div class="modal-header">
                            <h4>Edit Teknisi</h4>
                            <button type="button" onclick="closeModal('edit{{ $tk->id }}')" style="background:none; border:none; color:#fff; cursor:pointer;"><i class="fas fa-times"></i></button>
                        </div>

                        <form action="{{ route('admin.updateteknisi', $tk->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group">
                                <label>Nama Teknisi</label>
                                <input type="text" name="nama_teknisi" class="custom-input" value="{{ $tk->nama_teknisi }}" required>
                            </div>

                            <div class="form-group">
                                <label>No HP (WhatsApp)</label>
                                <input type="text" name="no_hp" class="custom-input" value="{{ $tk->no_hp }}" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="custom-input" value="{{ $tk->email }}">
                            </div>

                            <div class="form-group">
                                <label>Update Foto (Opsional)</label>
                                <div class="custom-file-upload">
                                    <input type="file" name="gambar" style="color: white; font-size:12px;">
                                </div>
                                <div style="margin-top:10px; display:flex; align-items:center; gap:10px;">
                                    <small style="color:#aaa;">Foto Saat Ini:</small>
                                    <img src="{{ asset('uploads/teknisi/'.$tk->gambar) }}" width="40" height="40" style="border-radius:50%; object-fit:cover; border:1px solid #555;" onerror="this.style.display='none'">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn-cancel" onclick="closeModal('edit{{ $tk->id }}')">Batal</button>
                                <button type="submit" class="btn-save">Simpan Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-user-slash fa-3x mb-3" style="opacity:0.5;"></i><br>
                        Belum ada data teknisi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH (GLOBAL) ================= --}}
<div class="modal-backdrop" id="modalTambah">
    <div class="modal-box">
        <div class="modal-header">
            <h4>Tambah Teknisi Baru</h4>
            <button type="button" onclick="closeModal('modalTambah')" style="background:none; border:none; color:#fff; cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>

        <form action="{{ route('admin.simpanteknisi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Nama Teknisi</label>
                <input type="text" name="nama_teknisi" class="custom-input" placeholder="Nama Lengkap..." required>
            </div>

            <div class="form-group">
                <label>No HP (WhatsApp)</label>
                <input type="text" name="no_hp" class="custom-input" placeholder="08..." required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="custom-input" placeholder="alamat@email.com">
            </div>

            <div class="form-group">
                <label>Foto Profil</label>
                <div class="custom-file-upload">
                    <input type="file" name="gambar" required style="color: white; font-size:12px;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= SCRIPT MODAL ================= --}}
<script>
    function openModal(id) {
        let el = document.getElementById(id);
        if(el) {
            el.style.display = 'flex';
            setTimeout(() => {
                el.classList.add('modal-active');
            }, 10);
        }
    }

    function closeModal(id) {
        let el = document.getElementById(id);
        if(el) {
            el.classList.remove('modal-active');
            setTimeout(() => {
                el.style.display = 'none';
            }, 300);
        }
    }

    // Tutup modal kalau klik di luar box (backdrop)
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            event.target.classList.remove('modal-active');
            setTimeout(() => {
                event.target.style.display = 'none';
            }, 300);
        }
    }
</script>

@endsection