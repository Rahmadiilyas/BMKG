

@php
    function fileIcon($ext)
    {
        $ext = strtolower($ext);
        $color = '#fff';
        $icon = 'fa-file';

        switch ($ext) {
            case 'pdf':
                $icon = 'fa-file-pdf';
                $color = '#ff4d4d';
                break;
            case 'doc':
            case 'docx':
                $icon = 'fa-file-word';
                $color = '#4da3ff';
                break;
            case 'xls':
            case 'xlsx':
                $icon = 'fa-file-excel';
                $color = '#2ecc71';
                break;
            case 'ppt':
            case 'pptx':
                $icon = 'fa-file-powerpoint';
                $color = '#f1c40f';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $icon = 'fa-file-image';
                $color = '#adff2f';
                break;
            default:
                $icon = 'fa-file';
                $color = '#a0a0a0';
                break;
        }
        return ['icon' => $icon, 'color' => $color];
    }
@endphp

@extends('dashboard')

@section('content')
    {{-- Tambahkan SheetJS untuk Preview Excel --}}
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

    {{-- ================= HEADER & TOOLBAR ================= --}}
    <div class="header-toolbar">
        <div class="main-heading">
            <i class="fas fa-folder-open"></i>
            <span>{{ $folder->nama_folder }}</span>
        </div>

        <div class="action-wrapper">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" onkeyup="filterAllItems()" placeholder="Cari file/folder..."
                    autocomplete="off">
            </div>

            <a href="{{ route('admin.lihatfolder') }}" class="btn-secondary-neon" title="Kembali">
                <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Back</span>
            </a>

            <button class="btn-neon" onclick="openModal('modalSubfolder')">
                <i class="fas fa-folder-plus"></i> <span class="d-none d-md-inline">Folder</span>
            </button>
            <button class="btn-neon" onclick="openModal('modalUpload')">
                <i class="fas fa-cloud-upload-alt"></i> <span class="d-none d-md-inline">Upload</span>
            </button>
        </div>
    </div>

    {{-- ================= SUBFOLDER SECTION ================= --}}
    <div id="section-folder">
        <h5 class="section-title">FOLDER</h5>
        <div class="folder-grid" id="grid-folders">
            @forelse($folder->children as $sub)
                <div class="item-card search-item" ondblclick="window.location='{{ route('admin.bukafolder', $sub->id) }}'">
                    <button class="action-btn" onclick="toggleDropdown(event, 'drop-folder-{{ $sub->id }}')">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <div id="drop-folder-{{ $sub->id }}" class="custom-dropdown">
                        <button class="dropdown-item"
                            onclick="openRenameModal('folder', {{ $sub->id }}, '{{ $sub->nama_folder }}')">
                            <i class="fas fa-edit mr-2"></i> Rename
                        </button>
                        <form action="{{ route('admin.folder.delete', $sub->id) }}" method="POST"
                            onsubmit="return confirm('Hapus folder ini?')">
                            @csrf
                            <button class="dropdown-item delete"><i class="fas fa-trash mr-2"></i> Hapus</button>
                        </form>
                    </div>

                    <i class="fas fa-folder fa-3x mb-3" style="color: #f1c40f;"></i>
                    <div class="item-name">{{ $sub->nama_folder }}</div>
                </div>
            @empty
                <div class="empty-placeholder">
                    <i class="fas fa-folder-open mb-2" style="font-size: 24px; opacity: 0.5;"></i><br>
                    Tidak ada subfolder
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= FILE SECTION ================= --}}
    <div id="section-file" style="margin-top: 30px;">
        <h5 class="section-title">FILE</h5>
        <div class="folder-grid" id="grid-files">
            @forelse($folder->files as $file)
                @php $style = fileIcon($file->tipe_file); @endphp
                <div class="item-card search-item">
                    <button class="action-btn" onclick="toggleDropdown(event, 'drop-file-{{ $file->id }}')">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <div id="drop-file-{{ $file->id }}" class="custom-dropdown">
                        <button class="dropdown-item"
                            onclick="openRenameModal('file', {{ $file->id }}, '{{ $file->nama_file }}')">
                            <i class="fas fa-edit mr-2"></i> Rename
                        </button>
                        <form action="{{ route('admin.file.delete', $file->id) }}" method="POST"
                            onsubmit="return confirm('Hapus file ini?')">
                            @csrf
                            <button class="dropdown-item delete"><i class="fas fa-trash mr-2"></i> Hapus</button>
                        </form>
                    </div>

                    <i class="fas {{ $style['icon'] }} fa-3x mb-3" style="color: {{ $style['color'] }};"></i>

                    <div class="item-name" title="{{ $file->nama_file }}">
                        {{ Str::limit($file->nama_file, 15) }}
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button class="btn-secondary-neon btn-xs"
                            onclick="previewFile('{{ route('admin.file.view', $file->id) }}', '{{ $file->tipe_file }}', '{{ $file->nama_file }}')">
                            Preview
                        </button>
                        <a href="{{ route('admin.file.download1', $file->id) }}" class="btn-neon btn-xs">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-placeholder">
                    <i class="fas fa-file mb-2" style="font-size: 24px; opacity: 0.5;"></i><br>
                    Tidak ada file
                </div>
            @endforelse
        </div>
    </div>

    <div id="noDataFound" class="text-center mt-5" style="display: none; color: #888;">
        <i class="fas fa-search mb-2" style="font-size: 24px; opacity: 0.5;"></i>
        <p>Item tidak ditemukan</p>
    </div>

    {{-- ================= MODALS ================= --}}
    {{-- Tambah Folder --}}
    <div id="modalSubfolder" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('modalSubfolder')">&times;</button>
            <div class="modal-header">
                <h3>Tambah Subfolder</h3>
            </div>
            <form action="{{ route('admin.simpansubfolder', $folder->id) }}" method="POST">
                @csrf
                <label class="text-muted small">Nama Folder</label>
                <input type="text" name="nama_folder" class="form-input" required autocomplete="off">
                <div class="text-right">
                    <button type="button" class="btn-secondary-neon" onclick="closeModal('modalSubfolder')">Batal</button>
                    <button type="submit" class="btn-neon">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Rename --}}
    <div id="modalRename" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('modalRename')">&times;</button>
            <div class="modal-header">
                <h3>Rename Item</h3>
            </div>
            <form id="formRename" method="POST">
                @csrf
                <label class="text-muted small">Nama Baru</label>
                <input type="text" name="nama" id="renameInput" class="form-input" required autocomplete="off">
                <div class="text-right">
                    <button type="button" class="btn-secondary-neon" onclick="closeModal('modalRename')">Batal</button>
                    <button type="submit" class="btn-neon">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Upload --}}
    <div id="modalUpload" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('modalUpload')">&times;</button>
            <div class="modal-header">
                <h3>Upload File</h3>
            </div>
            <form id="uploadForm" action="{{ route('admin.simpanfile', $folder->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="upload-area">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                    <input type="file" name="file" class="form-input-file" required>
                </div>

                <div class="progress-container" id="uploadProgress">
                    <div class="progress-fill" id="progressBar"></div>
                </div>
                <div id="progressText" class="text-center small mt-2 text-lime"></div>

                <div class="text-right mt-3">
                    <button type="button" class="btn-secondary-neon" onclick="closeModal('modalUpload')">Batal</button>
                    <button type="submit" class="btn-neon">Upload</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview (UPDATED) --}}
    <div id="modalPreview" class="modal-overlay">
        <div class="modal-box preview-box">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 id="previewTitle" style="margin:0;">Preview</h3>
                <button type="button" onclick="closeModal('modalPreview')"
                    style="background:none;border:none;color:white;font-size:20px;cursor:pointer;">&times;</button>
            </div>
            <div id="previewBody" class="preview-content"></div>

            {{-- FOOTER KHUSUS PREVIEW (Download Button) --}}
            <div class="preview-footer"
                style="padding-top:15px; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center;">
                <span id="previewType" class="text-muted small"></span>
                <a id="downloadBtn" href="#" class="btn-neon btn-xs" download>
                    ⬇ Download
                </a>
            </div>
        </div>
    </div>

    {{-- ================= STYLE ================= --}}
    <style>
        :root {
            --accent-lime: #adff2f;
            --bg-card: #1a241b;
            --border-glass: rgba(173, 255, 47, 0.2);
        }

        /* Styling Table Excel dari Kode Kedua */
        .preview-content table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            font-family: sans-serif;
            color: #fff;
        }

        .preview-content th,
        .preview-content td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 8px;
            text-align: left;
            min-width: 60px;
        }

        .preview-content th {
            background-color: rgba(173, 255, 47, 0.2);
            color: var(--accent-lime);
            font-weight: 800;
        }

        .preview-content td {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .preview-content h3 {
            margin-bottom: 10px;
            color: var(--accent-lime);
        }

        /* --- Existing Styles --- */
        .header-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            background: rgba(255, 255, 255, 0.02);
            padding: 15px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 30px;
        }

        .main-heading {
            font-size: 20px;
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
            gap: 10px;
        }

        .search-container {
            position: relative;
            margin-right: 10px;
        }

        .search-container input {
            background: #000;
            border: 1px solid #333;
            border-radius: 50px;
            padding: 8px 15px 8px 35px;
            color: #fff;
            width: 160px;
            transition: 0.3s;
            font-size: 13px;
            outline: none;
        }

        .search-container input:focus {
            width: 220px;
            border-color: var(--accent-lime);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 13px;
        }

        .btn-neon {
            background: var(--accent-lime);
            color: #000;
            border: none;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-secondary-neon {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-xs {
            padding: 4px 10px;
            font-size: 10px;
        }

        .section-title {
            color: #a0a0a0;
            font-size: 11px;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }

        .item-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            position: relative;
            transition: 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 160px;
        }

        .item-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent-lime);
            background: rgba(173, 255, 47, 0.05);
        }

        .item-name {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #eee;
            word-break: break-word;
        }

        .action-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: transparent;
            border: none;
            color: #666;
            cursor: pointer;
        }

        .action-btn:hover {
            color: #fff;
        }

        .custom-dropdown {
            display: none;
            position: absolute;
            top: 30px;
            right: 5px;
            background: #000;
            border: 1px solid #333;
            border-radius: 8px;
            z-index: 10;
            width: 120px;
            flex-direction: column;
            overflow: hidden;
        }

        .custom-dropdown.show {
            display: flex;
        }

        .dropdown-item {
            padding: 8px;
            font-size: 11px;
            color: #ccc;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            width: 100%;
        }

        .dropdown-item:hover {
            background: #222;
            color: #fff;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #1a241b;
            border: 1px solid var(--accent-lime);
            width: 90%;
            max-width: 400px;
            border-radius: 15px;
            padding: 25px;
            position: relative;
            box-shadow: 0 0 30px rgba(173, 255, 47, 0.1);
            display: flex;
            flex-direction: column;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid #444;
            border-radius: 8px;
            color: #fff;
            margin-bottom: 20px;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent-lime);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .upload-area {
            border: 2px dashed #444;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }

        .form-input-file {
            margin-top: 10px;
            color: #fff;
            width: 100%;
        }

        .progress-container {
            width: 100%;
            background: #333;
            height: 6px;
            border-radius: 3px;
            margin-top: 15px;
            display: none;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent-lime);
            width: 0%;
        }

        .preview-box {
            max-width: 800px;
            height: 80vh;
        }

        .preview-content {
            flex: 1;
            background: #000;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: auto;
            /* Allow scrolling for excel/long content */
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .empty-placeholder {
            grid-column: 1/-1;
            text-align: center;
            color: #666;
            padding: 20px;
            border: 1px dashed #333;
            border-radius: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #888;
        }

        .text-lime {
            color: var(--accent-lime);
        }

        .small {
            font-size: 12px;
        }

        .gap-2 {
            gap: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        @media (max-width:576px) {
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
        }
    </style>

    {{-- ================= JAVASCRIPT ================= --}}
  {{-- ================= JAVASCRIPT ================= --}}
    <script>
        // ================= SEARCH / FILTER =================
        function filterAllItems() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let items = document.getElementsByClassName('search-item');
            let count = 0;

            for (let i = 0; i < items.length; i++) {
                let nameEl = items[i].getElementsByClassName('item-name')[0];
                let name = nameEl.innerText.toLowerCase();
                if (name.includes(input)) {
                    items[i].style.display = "";
                    count++;
                } else {
                    items[i].style.display = "none";
                }
            }

            let noData = document.getElementById('noDataFound');
            noData.style.display = (count === 0 && items.length > 0) ? "block" : "none";

            checkSectionEmpty('grid-folders', 'section-folder');
            checkSectionEmpty('grid-files', 'section-file');
        }

        function checkSectionEmpty(gridId, sectionId) {
            let grid = document.getElementById(gridId);
            let section = document.getElementById(sectionId);
            if (!grid || !section) return;
            let visibleChildren = Array.from(grid.children).filter(c => c.classList.contains('search-item') && c.style
                .display !== 'none').length;
            let inputVal = document.getElementById('searchInput').value;
            section.style.display = (inputVal !== '' && visibleChildren === 0) ? 'none' : 'block';
        }

        // ================= MODAL LOGIC =================
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            // Bersihkan konten preview saat tutup agar loading muncul lagi nanti
            if (id === 'modalPreview') {
                setTimeout(() => {
                    document.getElementById('previewBody').innerHTML = '';
                }, 300);
            }
        }

        function toggleDropdown(e, id) {
            e.stopPropagation();
            document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('show'));
            document.getElementById(id).classList.toggle('show');
        }
        window.onclick = function(e) {
            if (!e.target.matches('.action-btn') && !e.target.matches('.fa-ellipsis-v')) {
                document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('show'));
            }
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('active');
                if (e.target.id === 'modalPreview') {
                    document.getElementById('previewBody').innerHTML = '';
                }
            }
        }

        function openRenameModal(type, id, nama) {
            let form = document.getElementById('formRename');
            document.getElementById('renameInput').value = nama;
            form.action = type === 'folder' ? `/admin/folder/${id}/rename` : `/admin/file/${id}/rename`;
            openModal('modalRename');
        }

        // ================= PREVIEW FUNCTION (UPDATED) =================
        function previewFile(url, type, nama) {
            // Normalisasi tipe file
            type = (type || '').toLowerCase();

            // Set Judul & Tipe di Modal
            document.getElementById('previewTitle').innerText = nama;
            document.getElementById('previewType').innerText = type.toUpperCase();

            // Set Tombol Download
            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.href = url;
            downloadBtn.setAttribute('download', nama);

            // 1. TAMPILKAN LOADING (Spinner SVG)
            let body = document.getElementById('previewBody');
            body.innerHTML = `
                <div style="height:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; color:#fff; min-height:300px;">
                    <div style="color:var(--accent-lime); margin-bottom:15px;">
                        <svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <g fill="none" fill-rule="evenodd">
                                <g transform="translate(1 1)" stroke-width="4">
                                    <circle stroke-opacity=".5" cx="22" cy="22" r="22"/>
                                    <path d="M44 22c0-12.15-9.85-22-22-22">
                                        <animateTransform attributeName="transform" type="rotate" from="0 22 22" to="360 22 22" dur="1s" repeatCount="indefinite"/>
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span style="font-weight:600; letter-spacing:0.5px; animation: blink 1.5s infinite;">Memuat dokumen...</span>
                </div>
                <style>@keyframes blink { 0% {opacity: .5;} 50% {opacity: 1;} 100% {opacity: .5;} }</style>
            `;

            // Buka Modal
            openModal('modalPreview');

            // 2. PROSES RENDER (Dengan Delay Sedikit agar Loading Terlihat Halus)
            setTimeout(() => {
                // --- PDF ---
                if (type === 'pdf') {
                    body.innerHTML = `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`;
                }
                // --- GAMBAR ---
                else if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(type)) {
                    body.innerHTML =
                        `<img src="${url}" style="max-width:100%; max-height:100%; object-fit:contain; margin:auto; display:block;">`;
                }
                // --- VIDEO ---
                else if (['mp4', 'webm', 'ogg'].includes(type)) {
                    body.innerHTML = `
                        <video controls style="max-width:100%; max-height:100%; width:100%;">
                            <source src="${url}" type="video/${type}">
                            Browser tidak mendukung video.
                        </video>`;
                }
                // --- OFFICE (Word/PPT) via Google Docs Viewer ---
                else if (['doc', 'docx', 'ppt', 'pptx'].includes(type)) {
                    let viewer = `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
                    body.innerHTML = `<iframe src="${viewer}" width="100%" height="100%" style="border:none"></iframe>`;
                }
                // --- EXCEL (XLS/XLSX) via SheetJS + Office Online Button ---
                else if (['xls', 'xlsx'].includes(type)) {
                    fetch(url)
                        .then(res => res.arrayBuffer())
                        .then(data => {
                            const workbook = XLSX.read(data, {
                                type: "array"
                            });
                            let html = "";

                            // Logic URL untuk Office Online
                            let fullUrl = url.startsWith('http') ? url : window.location.origin + url;
                            const officeUrl = `https://view.officeapps.live.com/op/view.aspx?src=${encodeURIComponent(fullUrl)}`;

                            // Header + Tombol Buka Excel Online
                            html += `
                            <div style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; background:rgba(255,255,255,0.05); padding:10px; border-radius:8px;">
                                <div style="margin-bottom:5px;">
                                    <p style="color:var(--accent-lime); font-weight:800; margin:0; font-size:12px;">
                                        Preview SheetJS
                                    </p>
                                    <small style="color:#aaa; font-size:10px;">Jika tabel berantakan, gunakan tombol di kanan &rarr;</small>
                                </div>
                                <a href="${officeUrl}" target="_blank" style="
                                    background: var(--accent-lime); 
                                    color: #000; 
                                    padding: 8px 15px; 
                                    border-radius: 50px; 
                                    font-weight:800; 
                                    text-decoration:none;
                                    white-space: nowrap;
                                    font-size: 11px;
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 5px;
                                    transition: 0.3s;
                                " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    📂 Buka Excel Online
                                </a>
                            </div>`;

                            // Render Tabel
                            workbook.SheetNames.forEach(sheetName => {
                                html += `<h3 style="margin-top:20px; color:var(--accent-lime); font-size:14px; border-bottom:1px solid rgba(173, 255, 47, 0.3); padding-bottom:5px;">${sheetName}</h3>`;
                                html += `<div style="overflow-x:auto;">`;
                                html += XLSX.utils.sheet_to_html(workbook.Sheets[sheetName]);
                                html += `</div>`;
                            });

                            body.innerHTML = html;
                        })
                        .catch(err => {
                            // Fallback jika gagal load
                            let fullUrl = url.startsWith('http') ? url : window.location.origin + url;
                            const officeUrl = `https://view.officeapps.live.com/op/view.aspx?src=${encodeURIComponent(fullUrl)}`;

                            body.innerHTML = `
                                <div style="text-align:center;margin-top:40px">
                                    <p style="color:#aaa; margin-bottom:15px;">Gagal memuat preview tabel sederhana.</p>
                                    <a href="${officeUrl}" target="_blank" class="btn-neon" style="display:inline-flex;">
                                        📂 Coba Buka di Excel Online
                                    </a>
                                </div>`;
                        });
                }
                // --- FORMAT TIDAK DIDUKUNG ---
                else {
                    body.innerHTML = `
                        <div style="text-align:center;margin-top:40px">
                            <p class="text-muted">Preview tidak tersedia untuk format ini.</p>
                            <a href="${url}" class="btn-neon" download>Download File</a>
                        </div>`;
                }
            }, 500); // Delay 500ms agar loading terlihat
        }

        // ================= UPLOAD LOGIC =================
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let fd = new FormData(this);
            let box = document.getElementById('uploadProgress');
            let bar = document.getElementById('progressBar');
            let txt = document.getElementById('progressText');
            box.style.display = 'block';
            bar.style.width = '0%';
            txt.innerText = '';
            let xhr = new XMLHttpRequest();
            xhr.open('POST', this.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name=_token]').value);
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    let p = Math.round((e.loaded / e.total) * 100);
                    bar.style.width = p + '%';
                    txt.innerText = p + '% Mengunggah...';
                }
            };
            xhr.onload = function() {
                if (xhr.status === 200) {
                    txt.innerText = 'Selesai! Merefresh...';
                    bar.style.background = '#2ecc71';
                    setTimeout(() => location.reload(), 700);
                } else {
                    txt.innerText = 'Gagal upload.';
                    txt.style.color = 'red';
                    bar.style.background = 'red';
                }
            };
            xhr.send(fd);
        });
    </script>
@endsection