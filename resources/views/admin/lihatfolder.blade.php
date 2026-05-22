@extends('dashboard')

@section('content')
    {{-- ================= HEADER & TOOLBAR ================= --}}
    <div class="header-toolbar">

        {{-- BAGIAN KIRI: JUDUL --}}
        <div class="main-heading">
            <i class="fas fa-folder-open"></i>
            <span>Manajemen Folder</span>
        </div>

        {{-- BAGIAN KANAN: SEARCH + TOMBOL --}}
        <div class="action-wrapper">

            {{-- SEARCH BAR MODERN --}}
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" onkeyup="filterFolders()" placeholder="Cari folder..."
                    autocomplete="off">
            </div>

            {{-- TOMBOL TAMBAH --}}
            <button class="btn-add-neon" onclick="openTambah()">
                <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Folder Baru</span>
            </button>

        </div>

    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success small mb-4"
            style="background: rgba(173,255,47,0.1); border: 1px solid var(--accent-lime); color: var(--accent-lime);">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ================= GRID FOLDER ================= --}}
    <div class="folder-grid" id="folderGrid">
        @forelse($folders as $f)
            {{-- 'folder-item' class wajib ada untuk JS Search --}}
            <div class="folder-card folder-item">

                {{-- MENU TITIK TIGA --}}
                <div class="menu-btn" onclick="toggleMenu(this)">⋮</div>
                <div class="menu-popup">
                    <button onclick="openRename({{ $f->id }}, '{{ $f->nama_folder }}')">
                        <i class="fas fa-pen small mr-2"></i> Rename
                    </button>
                    <form method="POST" action="{{ route('admin.folder.delete', $f->id) }}">
                        @csrf
                        <button class="danger"><i class="fas fa-trash small mr-2"></i> Hapus</button>
                    </form>
                </div>

                {{-- ISI CARD --}}
                <a href="{{ route('admin.bukafolder', $f->id) }}" class="folder-link">
                    <div class="folder-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    {{-- 'folder-name' class wajib ada untuk JS Search --}}
                    <div class="folder-name">{{ $f->nama_folder }}</div>
                </a>

            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-folder-open mb-2" style="font-size: 30px; opacity: 0.3;"></i>
                <p>Belum ada folder</p>
            </div>
        @endforelse
    </div>

    {{-- PESAN JIKA SEARCH KOSONG --}}
    <div id="noDataFound" class="text-center mt-5" style="display: none; color: #888;">
        <i class="fas fa-search mb-2" style="font-size: 24px; opacity: 0.5;"></i>
        <p>Folder tidak ditemukan</p>
    </div>

    {{-- ================= MODALS ================= --}}

    {{-- Modal Tambah --}}
    <div class="modal-custom" id="modalTambah">
        <div class="modal-box">
            <h4><i class="fas fa-folder-plus mr-2"></i>Tambah Folder</h4>
            <form method="POST" action="{{ route('admin.simpanfolder') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="nama_folder" class="custom-input" placeholder="Nama Folder Baru..." required
                        autocomplete="off" autofocus>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Rename --}}
    <div class="modal-custom" id="modalRename">
        <div class="modal-box">
            <h4><i class="fas fa-edit mr-2"></i>Rename Folder</h4>
            <form method="POST" id="formRename">
                @csrf
                <div class="form-group">
                    <input type="text" name="nama" id="renameInput" class="custom-input" required autocomplete="off">
                </div>
                <div class="modal-action">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button class="btn-save">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= STYLE CSS KHUSUS ================= --}}
    <style>
        /* VARIABEL WARNA */
        :root {
            --accent-lime: #adff2f;
            --bg-card: #1a241b;
            --bg-dark: #0f1710;
            --border-glass: rgba(173, 255, 47, 0.2);
        }

        /* HEADER & TOOLBAR LAYOUT */
        .header-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.02);
            padding: 15px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .main-heading {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-heading i {
            color: var(--accent-lime);
        }

        .action-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* SEARCH BAR STYLE (MODERN) */
        .search-container {
            position: relative;
        }

        .search-container input {
            background: #000;
            border: 1px solid #333;
            border-radius: 50px;
            /* Bentuk Pill/Lonjong */
            padding: 10px 15px 10px 40px;
            color: white;
            width: 180px;
            transition: all 0.3s ease;
            font-size: 13px;
            outline: none;
        }

        .search-container input:focus {
            width: 250px;
            /* Melebar saat fokus */
            border-color: var(--accent-lime);
            box-shadow: 0 0 15px rgba(173, 255, 47, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 14px;
            pointer-events: none;
        }

        /* TOMBOL TAMBAH NEON */
        .btn-add-neon {
            background: var(--accent-lime);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-add-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(173, 255, 47, 0.4);
        }

        /* GRID FOLDER */
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 20px;
        }

        .folder-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            position: relative;
            transition: 0.3s;
            cursor: pointer;
        }

        .folder-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-lime);
            background: rgba(173, 255, 47, 0.05);
        }

        .folder-link {
            text-decoration: none;
            display: block;
        }

        .folder-icon {
            width: 60px;
            height: 60px;
            background: rgba(173, 255, 47, 0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: var(--accent-lime);
            transition: 0.3s;
        }

        .folder-card:hover .folder-icon {
            background: var(--accent-lime);
            color: #000;
            box-shadow: 0 0 15px rgba(173, 255, 47, 0.5);
        }

        .folder-name {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* MENU POPUP */
        .menu-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #666;
            cursor: pointer;
            padding: 5px;
            z-index: 5;
            font-size: 16px;
        }

        .menu-btn:hover {
            color: #fff;
        }

        .menu-popup {
            display: none;
            position: absolute;
            top: 35px;
            right: 10px;
            background: #000;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 5px;
            z-index: 10;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            min-width: 120px;
        }

        .menu-popup.show {
            display: block;
            animation: fadeIn 0.2s;
        }

        .menu-popup button {
            background: transparent;
            border: none;
            color: #ccc;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu-popup button:hover {
            background: #222;
            color: white;
        }

        .menu-popup .danger:hover {
            background: #300;
            color: #ff4d4d;
        }

        /* MODAL STYLES */
        .modal-custom {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-box {
            background: #111;
            border: 1px solid var(--accent-lime);
            padding: 30px;
            border-radius: 20px;
            width: 350px;
            box-shadow: 0 0 50px rgba(173, 255, 47, 0.1);
        }

        .modal-box h4 {
            color: var(--accent-lime);
            margin: 0 0 20px;
            font-weight: 700;
        }

        .custom-input {
            width: 100%;
            background: #000;
            border: 1px solid #444;
            padding: 12px;
            border-radius: 10px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--accent-lime);
        }

        .modal-action {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-cancel {
            background: transparent;
            border: none;
            color: #888;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cancel:hover {
            color: #fff;
        }

        .btn-save {
            background: var(--accent-lime);
            color: #000;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 800;
            cursor: pointer;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            color: #666;
            padding: 50px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .header-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-wrapper {
                width: 100%;
                justify-content: space-between;
            }

            .search-container input {
                width: 100%;
            }

            .search-container input:focus {
                width: 100%;
            }

            /* Full width on mobile */
        }
    </style>

    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        // 1. FILTER SEARCH (Real-time)
        function filterFolders() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let cards = document.getElementsByClassName('folder-item');
            let count = 0;

            for (let i = 0; i < cards.length; i++) {
                let name = cards[i].getElementsByClassName('folder-name')[0].innerText.toLowerCase();
                if (name.includes(input)) {
                    cards[i].style.display = "";
                    count++;
                } else {
                    cards[i].style.display = "none";
                }
            }

            // Tampilkan pesan jika kosong
            let msg = document.getElementById('noDataFound');
            msg.style.display = (count === 0 && cards.length > 0) ? "block" : "none";
        }

        // 2. MODAL & MENU
        function toggleMenu(el) {
            event.stopPropagation();
            document.querySelectorAll('.menu-popup').forEach(m => {
                if (m !== el.nextElementSibling) m.classList.remove('show');
            });
            el.nextElementSibling.classList.toggle('show');
        }

        function openTambah() {
            document.getElementById('modalTambah').style.display = 'flex';
            // Auto focus ke input saat modal muncul
            setTimeout(() => document.querySelector('#modalTambah input').focus(), 100);
        }

        function openRename(id, nama) {
            document.getElementById('renameInput').value = nama;
            document.getElementById('formRename').action = `/admin/folder/${id}/rename`;
            document.getElementById('modalRename').style.display = 'flex';
        }

        function closeModal() {
            document.querySelectorAll('.modal-custom').forEach(m => m.style.display = 'none');
        }

        // Close on click outside
        window.onclick = function(e) {
            if (e.target.classList.contains('modal-custom')) closeModal();
            if (!e.target.classList.contains('menu-btn')) {
                document.querySelectorAll('.menu-popup').forEach(m => m.classList.remove('show'));
            }
        }
    </script>
@endsection
