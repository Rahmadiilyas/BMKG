@php
function fileIcon($ext)
{
    $ext = strtolower($ext);

    $icon = asset('img/folderku.png'); // default

    switch ($ext) {
        case 'pdf':
            $icon = asset('img/pdfku.png');
            break;

        case 'doc':
        case 'docx':
            $icon = asset('img/wordku.png');
            break;

        case 'xls':
        case 'xlsx':
        case 'csv':
            $icon = asset('img/xlku.png');
            break;

        case 'ppt':
        case 'pptx':
            $icon = asset('img/pptku.png');
            break;

        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'svg':
        case 'heic':
            $icon = asset('img/camera.jpg');
            break;

        case 'mp4':
        case 'mkv':
        case 'avi':
        case 'mov':
            $icon = asset('img/vidio.jpg');
            break;

        case 'zip':
        case 'rar':
        case '7z':
            $icon = asset('img/zip.png');
            break;

        default:
            $icon = asset('img/folderku.png');
            break;
    }

    return $icon;
}
@endphp


@extends('dashboard')

@section('content')
    {{-- Script API OnlyOffice (Wajib ada) --}}
    <script src="https://teknisi.blinklab.com/office/web-apps/apps/api/documents/api.js"></script>

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
               @php $icon = fileIcon($file->tipe_file); @endphp
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

                    {{-- <i class="fas {{ $style['icon'] }} fa-3x mb-3" style="color: {{ $style['color'] }};"></i> --}}
<img src="{{ $icon }}" 
     width="60" height="60"
     style="object-fit:contain;">
               @php 
    $cleanName = preg_replace('/^\d+_/', '', $file->nama_file); 
@endphp

<div class="item-name" title="{{ $cleanName }}">
    {{-- Ubah 15 jadi 50 agar nama file tampil lebih panjang --}}
    {{ Str::limit($cleanName, 50) }}
</div>

                    <div class="mt-3 d-flex gap-2">
                        <button class="btn-secondary-neon btn-xs"
                            onclick="previewFile(
                                '{{ route('admin.file.view', $file->id) }}',
                                '{{ $file->tipe_file }}',
                                '{{ $file->nama_file }}',
                                {{ $file->id }}
                            )">
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
                    <button type="button" class="btn-secondary-neon"
                        onclick="closeModal('modalSubfolder')">Batal</button>
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
{{-- Cari bagian modalUpload di file blade Anda --}}
<div id="modalUpload" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modalUpload')">&times;</button>
        <div class="modal-header"><h3>Upload File</h3></div>
        {{-- Hapus atribut 'required' pada input di bawah ini --}}
        <form id="uploadForm" action="{{ route('admin.simpanfile', $folder->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-area">
                <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                <input type="file" id="fileInput" name="files[]" class="form-input-file" multiple>
                <p class="small text-muted mt-2">Pilih satu atau banyak file sekaligus</p>
            </div>

            <div id="fileListContainer" class="mt-3" style="max-height: 180px; overflow-y: auto; border: 1px solid #333; border-radius: 8px; padding: 5px; background: #0a0a0a;">
                <p class="text-muted small text-center m-0">Belum ada file dipilih</p>
            </div>

            <div class="progress-container" id="uploadProgress" style="display:none;">
                <div class="progress-fill" id="progressBar"></div>
            </div>
            <div id="progressText" class="text-center small mt-2 text-lime"></div>

            <div class="text-right mt-3">
                <button type="button" class="btn-secondary-neon" onclick="closeModal('modalUpload')">Batal</button>
                <button type="submit" class="btn-neon" id="btnSubmitUpload">Mulai Upload</button>
            </div>
        </form>
    </div>
</div>

    {{-- Preview Modal LENGKAP --}}
    <div id="modalPreview" class="modal-overlay">
        <div class="modal-box preview-box">
            
            {{-- HEADER: Judul Kiri, Aksi Kanan --}}
            <div class="modal-header-custom">
                <h3 id="previewTitle" class="modal-title">Preview</h3>
                
                <div class="modal-actions">
                    {{-- Container Tombol Edit --}}
                    <div id="editButtonContainer"></div>

                    {{-- Tombol Close --}}
                    <button type="button" class="btn-close-custom" onclick="closeModal('modalPreview')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div id="previewBody" class="preview-content"></div>
            
            <div class="preview-footer">
                <span id="previewType" class="text-muted"></span>
                <a id="downloadBtn" href="#" download>⬇ Download</a>
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

        /* Styling Modal Preview Baru */
        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #333;
            margin-bottom: 15px;
        }

        .modal-title {
            margin: 0;
            color: #fff;
            font-size: 18px;
        }

        .modal-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-close-custom {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            transition: 0.2s;
        }

        .btn-close-custom:hover {
            color: #ff4d4d;
            transform: scale(1.1);
        }

        /* Tombol Edit Lime */
        .btn-edit-modal {
            background-color: var(--accent-lime);
            color: #000;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 0 10px rgba(173, 255, 47, 0.3);
            transition: transform 0.2s;
        }

        .btn-edit-modal:hover {
            transform: scale(1.05);
            background-color: #9ce62a;
            color: #000;
            text-decoration: none;
        }

        /* Layout Umum */
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
            background: rgba(0, 0, 0, 0.85);
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
        }

        /* KHUSUS PREVIEW BOX UKURAN BESAR */
        .preview-box {
            max-width: 900px;
            height: 85vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            background: #0b120c; /* Latar Gelap */
            border: 1px solid rgba(173, 255, 47, 0.2);
        }

        .preview-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--border-glass);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.02);
        }

        .preview-footer a {
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            transition: 0.3s;
        }
        .preview-footer a:hover {
            background: var(--accent-lime);
            color: #000;
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

        .preview-content {
            flex: 1;
            background: #000;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #333;
        }

        .empty-placeholder {
            grid-column: 1/-1;
            text-align: center;
            color: #666;
            padding: 20px;
            border: 1px dashed #333;
            border-radius: 10px;
        }

        .text-right { text-align: right; }
        .text-muted { color: #888; }
        .text-lime { color: var(--accent-lime); }
        .small { font-size: 12px; }
        .gap-2 { gap: 10px; }
        .mt-3 { margin-top: 15px; }

        @media (max-width:576px) {
            .header-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .action-wrapper {
                width: 100%;
                justify-content: space-between;
            }
            .search-container input { width: 100%; }
            .search-container input:focus { width: 100%; }
        }
    </style>

    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        
let selectedFiles = []; 
    const fileInput = document.getElementById('fileInput');
    const fileListContainer = document.getElementById('fileListContainer');

    // 1. Tangkap pilihan file
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);
            selectedFiles = [...selectedFiles, ...newFiles];
            renderFileList();
            fileInput.value = ""; // Input asli dikosongkan agar required tidak mengganggu
        });
    }

    // 2. Tampilkan daftar file
    function renderFileList() {
        fileListContainer.innerHTML = "";
        if (selectedFiles.length === 0) {
            fileListContainer.innerHTML = '<p class="text-muted small text-center m-0">Belum ada file dipilih</p>';
            return;
        }
        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = "selected-file-item";
            fileItem.style = "display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.05); padding: 6px 10px; border-radius: 6px; margin-bottom: 4px; border: 1px solid #222;";
            fileItem.innerHTML = `
                <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                    <i class="fas fa-file-alt" style="color: #adff2f; font-size: 12px;"></i>
                    <span class="small" style="color: #ddd; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                        ${file.name}
                    </span>
                </div>
                <button type="button" onclick="removeFile(${index})" style="background:transparent; border:none; color:#ff4d4d; cursor:pointer;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileListContainer.appendChild(fileItem);
        });
    }

    // 3. Hapus file dari daftar
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderFileList();
    }

    // 4. Proses Upload via AJAX (HANYA SATU HANDLER)
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Cek manual jika kosong
        if (selectedFiles.length === 0) {
            alert("Silakan pilih file terlebih dahulu!");
            return;
        }

        let fd = new FormData();
        // Masukkan file dari array JavaScript ke FormData
        selectedFiles.forEach(file => fd.append('files[]', file));

        let box = document.getElementById('uploadProgress');
        let bar = document.getElementById('progressBar');
        let txt = document.getElementById('progressText');
        let btn = document.getElementById('btnSubmitUpload');
        const csrf = document.querySelector('input[name=_token]').value;

        btn.disabled = true; // Cegah klik ganda
        box.style.display = 'block';
        bar.style.width = '0%';
        
        let xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                let p = Math.round((e.loaded / e.total) * 100);
                bar.style.width = p + '%';
                txt.innerText = p + '% Mengunggah ' + selectedFiles.length + ' file...';
            }
        };

    xhr.onload = function() {
    // Jika status 200 (OK), baru boleh reload
    if (xhr.status === 200) {
        txt.innerText = 'Upload Berhasil!';
        bar.style.background = '#2ecc71';
        setTimeout(() => location.reload(), 1000);
    } 
    // Jika status 302 atau lainnya, berarti ada error validasi
    else {
        console.error("Detail Error:", xhr.responseText);
        txt.innerText = 'Gagal: Cek ukuran file atau format dilarang server.';
        txt.style.color = 'red';
        bar.style.background = 'red';
        btn.disabled = false;
    }
};
        
        xhr.onerror = function() {
            txt.innerText = 'Terjadi kesalahan jaringan.';
            bar.style.background = 'red';
            btn.disabled = false;
        };

        xhr.send(fd);
    });
        // --- 1. FILTER / SEARCH ---
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

        // --- 2. MODAL & DROPDOWN ---
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            // Jika menutup modal preview, bersihkan OnlyOffice
            if(id === 'modalPreview' && window.docEditor) {
                window.docEditor.destroyEditor();
                document.getElementById('previewBody').innerHTML = '';
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
                // Matikan fitur klik luar tutup modal untuk preview agar tidak sengaja tertutup
                if(e.target.id !== 'modalPreview') {
                    e.target.classList.remove('active');
                }
            }
        }

        function openRenameModal(type, id, nama) {
            let form = document.getElementById('formRename');
            document.getElementById('renameInput').value = nama;
            form.action = type === 'folder' ? `/admin/folder/${id}/rename` : `/admin/file/${id}/rename`;
            openModal('modalRename');
        }

        // --- 3. PREVIEW LOGIC (GABUNGAN SEMUA FORMAT) ---
// --- 3. PREVIEW LOGIC (GABUNGAN SEMUA FORMAT) ---
        function previewFile(url, type, nama, fileId) {
            let body = document.getElementById('previewBody');
            let title = document.getElementById('previewTitle');
            let editContainer = document.getElementById('editButtonContainer');
            let previewType = document.getElementById('previewType');
            let downloadBtn = document.getElementById('downloadBtn');
            
            type = type.toLowerCase();

            // Set Info Header
            title.innerText = nama;
            if(previewType) previewType.innerText = type.toUpperCase();
            
            // Set Download Link
            if(url.includes('storage')) {
                 downloadBtn.href = url;
            } else {
                 downloadBtn.href = `/adminku/file/${fileId}/download`; 
            }
            downloadBtn.setAttribute('download', nama);

            // Reset
            editContainer.innerHTML = '';

            // Tentukan tipe yang bisa diedit OnlyOffice
            let editableTypes = ['docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt', 'csv', 'txt'];

            // Tampilkan Loading Awal
            body.innerHTML = `
                <div style="color:#adff2f;text-align:center;">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>
                    <span style="font-weight:600">Memuat dokumen...</span>
                </div>
            `;

            // Buka Modal
            openModal('modalPreview');

            setTimeout(() => {
                
                // --- PDF ---
                if (type === 'pdf') {
                    body.innerHTML = `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`;
                } 
                
                // --- GAMBAR ---
                else if (['jpg','jpeg','png','gif','webp', 'heic'].includes(type)) {
                    body.innerHTML = `<img src="${url}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
                } 
                
                // --- VIDEO ---
                else if (['mp4','webm','ogg'].includes(type)) {
                    body.innerHTML = `<video controls style="max-width:100%; max-height:100%;"><source src="${url}" type="video/${type}"></video>`;
                } 
                
                // --- ONLYOFFICE (Office + CSV) ---
                else if (editableTypes.includes(type)) {
                    
                    // ============================================================
                    // PERBAIKAN DI SINI: Tambahkan onclick="closeModal(...)"
                    // ============================================================
                    if(type !== 'csv' && type !== 'txt') { 
                        let editorUrl = `/admin/editor/${fileId}`;
                        editContainer.innerHTML = `
                            <a href="${editorUrl}" target="_blank" class="btn-edit-modal" onclick="closeModal('modalPreview')">
                                <i class="fas fa-edit"></i> Edit Dokumen
                            </a>
                        `;
                    } else if (type === 'csv') {
                         let editorUrl = `/admin/editor/${fileId}`;
                         editContainer.innerHTML = `
                            <a href="${editorUrl}" target="_blank" class="btn-edit-modal" onclick="closeModal('modalPreview')">
                                <i class="fas fa-edit"></i> Edit CSV
                            </a>
                        `;
                    }

                    body.innerHTML = `<div id="onlyoffice-preview" style="width:100%; height:100%;"></div>`;
                    
                    // URL Absolute Logic
                    let finalUrl = url;
                    if (!url.startsWith('http')) {
                        finalUrl = window.location.origin + url;
                    }

                    initOnlyOfficePreview(fileId, nama, finalUrl, type);
                } 

                // --- ZIP / RAR ---
                else if (['zip', 'rar'].includes(type)) {
                    body.innerHTML = '<div style="text-align:center;color:white;">Membaca isi arsip...</div>';
                    
                    fetch(`/admin/zip/${fileId}/content`)
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'error') {
                                body.innerHTML = `<div style="text-align:center;color:red;">${data.message}<br><small>${data.debug_path || ''}</small></div>`;
                                return;
                            }

                            // ... (Bagian render tabel ZIP tetap sama) ...
                            let html = `
                                <div style="padding:20px; overflow-y:auto; height:100%; width:100%;">
                                    <table style="width:100%; border-collapse:collapse; color:#fff; font-size:13px;">
                                        <thead style="border-bottom:1px solid #333; text-align:left;">
                                            <tr>
                                                <th style="padding:10px;">Nama File</th>
                                                <th style="padding:10px;">Ukuran</th>
                                                <th style="padding:10px; text-align:right;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;

                            data.files.forEach(item => {
                                let ext = item.name.split('.').pop().toLowerCase();
                                let isDoc = ['docx','xlsx','pptx','csv','pdf','txt'].includes(ext);
                                
                                let actionBtn = '';
                                if(item.is_dir) {
                                    actionBtn = '<span style="opacity:0.5">Folder</span>';
                                } else {
                                    actionBtn = `
                                        <button onclick="openZipItem('${fileId}', '${item.name}')" 
                                            class="btn-edit-modal" style="font-size:10px; padding:4px 8px;">
                                            ${isDoc ? '<i class="fas fa-eye"></i> Lihat' : '<i class="fas fa-download"></i> Unduh'}
                                        </button>
                                    `;
                                }

                                html += `
                                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                        <td style="padding:12px 10px;">${item.name}</td>
                                        <td style="padding:12px 10px; color:#aaa;">${item.size}</td>
                                        <td style="padding:12px 10px; text-align:right;">${actionBtn}</td>
                                    </tr>
                                `;
                            });

                            html += '</tbody></table></div>';
                            body.innerHTML = html;
                        })
                        .catch(err => {
                            body.innerHTML = '<div style="text-align:center;color:red;">Gagal memuat isi ZIP</div>';
                        });
                }

                // --- FORMAT LAIN ---
                else {
                    body.innerHTML = `<div class="text-muted" style="padding-top:40px;">Preview tidak tersedia untuk format ini.</div>`;
                }
            }, 300);
        }
        // --- 4. ONLYOFFICE INITIALIZER ---
// --- 4. ONLYOFFICE INITIALIZER (DIPERBAIKI TOKENNYA) ---
        async function initOnlyOfficePreview(fileId, nama, url, type) {
            let docType = 'text'; 
            if (['xls', 'xlsx', 'csv', 'ods'].includes(type)) {
                docType = 'spreadsheet';
            } else if (['ppt', 'pptx', 'odp'].includes(type)) {
                docType = 'presentation';
            }

            let key = "preview_" + fileId + "_" + Date.now();

            // --- LOGIKA PENCARIAN TOKEN CSRF YANG LEBIH KUAT ---
            let csrfToken = null;
            
            // Coba 1: Cari di Meta Tag (Standar Laravel)
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                csrfToken = metaTag.getAttribute('content');
            } 
            // Coba 2: Cari di Input Form (Backup jika meta tag hilang)
            else {
                const inputToken = document.querySelector('input[name="_token"]');
                if (inputToken) {
                    csrfToken = inputToken.value;
                }
            }

            if (!csrfToken) {
                alert("Error Kritis: CSRF Token tidak ditemukan. Pastikan ada tag <meta name='csrf-token'> di layout utama.");
                return;
            }
            // ----------------------------------------------------

            try {
                const response = await fetch("{{ route('admin.get-token') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // <--- Gunakan token yang sudah dicari
                    },
                    body: JSON.stringify({
                        fileId: fileId,
                        fileType: type,
                        key: key,
                        title: nama,
                        url: url,
                        documentType: docType,
                        mode: 'view'
                    })
                });

                // Cek status HTTP (404/500)
                if (!response.ok) {
                    throw new Error(`Server Error (${response.status})`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                if (window.docEditor) window.docEditor.destroyEditor();

                window.docEditor = new DocsAPI.DocEditor("onlyoffice-preview", {
                    document: data.config.document,
                    documentType: data.config.documentType,
                    editorConfig: {
                        ...data.config.editorConfig,
                        mode: 'view',
                    },
                    token: data.token
                });

            } catch (error) {
                console.error("OnlyOffice Error:", error);
                document.getElementById('previewBody').innerHTML = 
                    `<div style="color:red; text-align:center; padding:20px;">
                        <h3>Gagal memuat dokumen</h3>
                        <p>Detail: ${error.message}</p>
                    </div>`;
            }
        }
        // --- 5. ZIP ITEM OPENER ---
        function openZipItem(zipId, filePath) {
            let ext = filePath.split('.').pop().toLowerCase();
            let fileName = filePath.split('/').pop();

            // URL Ekstraksi Absolute
            let extractUrl = window.location.origin + `/admin/zip/${zipId}/extract?path=` + encodeURIComponent(filePath);

            let officeTypes = ['docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt', 'csv', 'txt'];
            
            if (officeTypes.includes(ext)) {
                // Manipulasi Judul Modal
                document.getElementById('previewTitle').innerText = fileName;
                
                // Loading Ulang
                let body = document.getElementById('previewBody');
                body.innerHTML = `<div id="onlyoffice-preview" style="width:100%; height:100%;"></div>`;
                
                initOnlyOfficePreview(zipId, fileName, extractUrl, ext);
            } 
            else if (['jpg', 'png', 'pdf'].includes(ext)) {
                 window.open(extractUrl, '_blank');
            }
            else {
                window.location.href = extractUrl;
            }
        }

//         // --- 6. UPLOAD HANDLER ---
//  document.getElementById('uploadForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     if (selectedFiles.length === 0) {
//         alert("Silakan pilih file terlebih dahulu!");
//         return;
//     }

//     let fd = new FormData();
//     selectedFiles.forEach(file => fd.append('files[]', file));

//     let box = document.getElementById('uploadProgress');
//     let bar = document.getElementById('progressBar');
//     let txt = document.getElementById('progressText');
//     let btn = document.getElementById('btnSubmitUpload');
    
//     // Ambil CSRF dari meta tag atau input
//     const csrf = document.querySelector('input[name=_token]').value;

//     btn.disabled = true; 
//     box.style.display = 'block';
//     bar.style.width = '0%';
//     bar.style.background = '#adff2f';
    
//     let xhr = new XMLHttpRequest();
//     // Gunakan URL absolut untuk menghindari masalah redirect rute
//     xhr.open('POST', this.action, true);
//     xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
//     // Header tambahan agar Laravel tahu ini request AJAX
//     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); 

//     xhr.upload.onprogress = function(e) {
//         if (e.lengthComputable) {
//             let p = Math.round((e.loaded / e.total) * 100);
//             bar.style.width = p + '%';
//             txt.innerText = p + '% Mengunggah...';
//         }
//     };

//     xhr.onload = function() {
//         if (xhr.status === 200) {
//             let response = JSON.parse(xhr.responseText);
//             txt.innerText = 'Upload Berhasil! Merefresh...';
//             bar.style.background = '#2ecc71';
//             setTimeout(() => location.reload(), 1000);
//         } else {
//             // Tampilkan error asli jika bukan status 200
//             console.error("Server Error:", xhr.responseText);
//             txt.innerText = 'Gagal upload (Error ' + xhr.status + '). Cek ukuran file di server.';
//             txt.style.color = 'red';
//             bar.style.background = 'red';
//             btn.disabled = false;
//         }
//     };

//     xhr.onerror = function() {
//         txt.innerText = 'Koneksi terputus atau server menolak request.';
//         bar.style.background = 'red';
//         btn.disabled = false;
//     };

//     xhr.send(fd);
// });
    </script>
@endsection