<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMKG MAMUJU - Digital Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
      <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    {{-- Meta Token CSRF (Wajib untuk request Ajax) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Script API OnlyOffice --}}
    <script src="https://teknisi.blinklab.com/office/web-apps/apps/api/documents/api.js"></script>

    <style>
        :root {
            --bg-dark: #0f1710;
            --bg-card: #1a241b;
            --accent-lime: #adff2f;
            --header-dark: #162117;
            --text-main: #fff;
            --text-muted: #a0a0a0;
            --border-glass: rgba(173, 255, 47, .15)
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(15, 23, 16, .95), rgba(15, 23, 16, .95)),
                url('https://www.transparenttextures.com/patterns/dark-matter.png');
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none
        }

        header {
            background: var(--header-dark);
            height: 90px;
            display: flex;
            align-items: center;
            padding: 0 40px;
            border-bottom: 1px solid var(--border-glass);
            justify-content: space-between;
            flex-shrink: 0;
            z-index: 100;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 22px;
            font-weight: 800
        }

        .logo-area span {
            color: var(--accent-lime)
        }

        .search-box {
            width: 380px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            padding: 10px 22px;
            border-radius: 30px;
            display: flex;
            align-items: center
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            width: 100%;
            margin-left: 10px
        }

        .dashboard-btn {
            background: transparent;
            border: 1px solid var(--accent-lime);
            color: var(--accent-lime);
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: .3s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .dashboard-btn:hover {
            background: var(--accent-lime);
            color: #000;
            box-shadow: 0 0 15px rgba(173, 255, 47, .3)
        }

       .container {
    display: grid;
    grid-template-columns: 280px 1fr 320px;
    gap: 20px;
    padding: 20px;
    flex: 1;
    height: calc(100vh - 90px);
    overflow: hidden; /* Container luar tetap hidden, tapi isinya yang scroll */
}

        .card-modern {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 22px;
            border: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 12px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: .2s
        }

        .sidebar-item:hover {
            background: rgba(173, 255, 47, .1);
            color: var(--accent-lime)
        }

      .content-area {
    overflow-y: auto;
    padding-right: 10px;
    padding-bottom: 100px; /* Tambahkan ruang kosong di bawah agar file terakhir bisa di-scroll ke atas */
}

        .main-heading {
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 800
        }

        .main-heading span {
            color: var(--accent-lime)
        }

     .folder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 60px; /* Perbesar margin bawah */
}

       .folder-card {
            background: #000;
            border-radius: 0 15px 15px 15px;
            min-height: 110px; /* Pakai min-height biar fleksibel */
            height: auto;      /* Biar bisa memanjang ke bawah */
            position: relative;
            padding: 15px 20px; /* Sesuaikan padding */
            display: flex;
            align-items: center;
            transition: .3s;
            border: 1px solid #222;
            cursor: pointer
        }

     .folder-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-lime)
        }

        .folder-tab {
            position: absolute;
            top: -12px;
            left: 0;
            width: 75px;
            height: 12px;
            border-radius: 8px 8px 0 0
        }

        .icon-box {
            width: 48px;
            height: 48px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .03);
            border-radius: 10px;
            flex-shrink: 0;
        }

        .icon-box img {
            width: 30px;
            height: 30px;
            object-fit: contain
        }

      .file-info {
            flex: 1;
            overflow: hidden;
            /* Tambahan padding biar rapi */
            padding-right: 10px; 
        }

       .file-info strong {
            display: -webkit-box;       /* Mode box untuk multiline */
            -webkit-line-clamp: 3;      /* Maksimal 3 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: normal;        /* Izinkan turun baris */
            font-size: 13px;            /* Ukuran font pas */
            line-height: 1.4;           /* Jarak antar baris */
            color: #fff;
            word-break: break-word;     /* Potong kata jika kepanjangan */
        }
       .file-info small {
            display: block;             /* Turunkan tipe file ke bawah */
            margin-top: 4px;
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px


        }

      /* Cari bagian ini di kode Anda dan sesuaikan */
.right-panel {
    display: flex;
    flex-direction: column;
    gap: 10px; /* Gunakan 10px saja agar antar card lebih rapat */
    height: 100%; 
    overflow-y: auto;
    padding-bottom: 20px;
}
.right-panel::-webkit-scrollbar {
    display: none; /* Untuk Chrome/Edge agar tetap bersih */
}

        .history-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            font-size: 12px;
            cursor: pointer
        }

        .history-icon {
            width: 20px;
            height: 20px;
            object-fit: contain
        }
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px; /* Perkecil gap dari 4px ke 2px */
    text-align: center;
    font-size: 10px;
    margin-top: 10px; /* Perkecil margin atas */
}

       .calendar-day {
    padding: 4px 0; /* Perkecil padding dari 6px ke 4px */
    border-radius: 6px;
    color: var(--text-muted);
}

        .cal-today {
            background: var(--accent-lime) !important;
            color: #000 !important;
            font-weight: 800
        }

        /* --- MODAL CSS --- */
        .modal-preview {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 20000;
            backdrop-filter: blur(5px);
        }

        .modal-preview.active {
            display: flex
        }

        .preview-box {
            background: #0b120c;
            width: 90%;
            height: 90%;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-glass);
            box-shadow: 0 0 50px rgba(0,0,0,0.8);
        }

        .preview-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-glass);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.02);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .sidebar-folder-container {
    height: calc(100vh - 150px); /* Beri jarak agar tidak mentok bawah */
    overflow-y: auto !important; /* Paksa agar scroll muncul */
    display: flex;
    flex-direction: column;
    padding-right: 5px;
}

        /* Tombol Edit Hijau */
        .btn-edit-action {
            background-color: var(--accent-lime);
            color: #000;
            text-decoration: none;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .btn-edit-action:hover {
            transform: scale(1.05);
            background-color: #fff;
        }

        .preview-body {
            flex: 1;
            overflow: hidden;
            position: relative;
            background: #000;
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

        /* Responsive */
        @media (max-width: 1024px) {
            header { height: auto; flex-wrap: wrap; gap: 15px; padding: 15px 20px; }
            .logo-area { order: 1; }
            .dashboard-btn { order: 2; margin-left: auto; }
            .search-box { order: 3; width: 100%; margin-top: 5px; }
            body { height: auto; overflow-y: auto; }
            .container { 
        display: flex; 
        flex-direction: column; 
        height: auto; 
        padding: 10px; 
        gap: 15px; 
    }
    .sidebar-folder-container {
        height: auto !important; 
        overflow-y: hidden !important;
        overflow-x: auto !important;
        flex-direction: row !important; /* Kembali berjejer ke samping di HP */
        white-space: nowrap;
    }
           .card-modern.hide-scrollbar { 
        display: flex; 
        flex-direction: row; /* Folder berjejer ke samping di HP */
        overflow-x: auto !important; 
        overflow-y: hidden !important; 
        white-space: nowrap; 
        padding: 10px;
        height: auto !important; /* Reset tinggi untuk mobile */
    }
           .sidebar-item { 
        flex-shrink: 0; 
        margin-bottom: 0; 
        background: rgba(255, 255, 255, 0.05);
    }
            .content-area { overflow: visible; padding-right: 0; }
          .folder-grid { 
        grid-template-columns: repeat(2, 1fr); /* Tampilkan 2 kolom di HP agar tidak terlalu kecil */
        gap: 10px; 
    }
            .folder-card { padding: 0 12px; }
            .right-panel { order: 3; height: auto; overflow: visible; }
        }
    </style>
</head>

<body>

    <header>
    <div class="logo-area">
        <img src="{{ asset('img/logo.png') }}" height="40"> BMKG <span>MAMUJU</span>
    </div>

    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari dokumen atau folder...">
    </div>
        <a href="{{ route('user.dashboard') }}" class="dashboard-btn">DASHBOARD</a>
    </header>

    <div class="container">

      <div class="card-modern hide-scrollbar sidebar-folder-container">
    <div style="font-size:10px;font-weight:800;color:var(--accent-lime);margin-bottom:10px;">FOLDER:</div>
    @foreach ($sidebarFolders as $sf)
        <div class="sidebar-item" onclick="scrollToFolder('folder-{{ $sf->id }}')">📂 {{ $sf->nama_folder }}</div>
    @endforeach
</div>

      <div class="content-area hide-scrollbar" id="contentArea">

            {{-- ================= KONDISI 1: SEARCH ================= --}}
            @if (($mode ?? '') === 'search')
                <h2 class="main-heading">
                    Hasil pencarian: <span>"{{ $keyword }}"</span>
                </h2>
                <h3 style="margin-bottom:10px">📁 Folder</h3>
                <div class="folder-grid">
                    @forelse($folders as $f)
                        <div class="folder-card" onclick="openSubfolder({{ $f->id }}, '{{ $f->nama_folder }}')">
                            <div class="icon-box"><img src="{{ asset('img/folderku.png') }}"></div>
                            <div class="file-info">
                                <strong>{{ $f->nama_folder }}</strong><small>FOLDER</small>
                            </div>
                        </div>
                    @empty
                        <p style="opacity:.6">Tidak ada folder ditemukan</p>
                    @endforelse
                </div>
                <h3 style="margin:20px 0 10px">📄 File</h3>
                <div class="folder-grid">
                    @forelse($files as $f)
                        {{-- BERSIHKAN NAMA FILE (SEARCH) --}}
                        @php $cleanName = preg_replace('/^\d+_/', '', $f->nama_file); @endphp
                         <div class="folder-card"
                            onclick="previewFile('{{ route('admin.file.view', $f->id) }}', '{{ $f->tipe_file }}', '{{ $cleanName }}', {{ $f->id }})">
                            <div class="folder-tab" style="background:{{ $f->color }}"></div>
                            <div class="icon-box"><img src="{{ $f->icon }}"></div>
                            <div class="file-info">
                                <strong>{{ Str::limit($cleanName, 50) }}</strong>
                                <small>{{ strtoupper($f->tipe_file) }}</small>
                            </div>
                        </div>
                    @empty
                        <p style="opacity:.6">Tidak ada file ditemukan</p>
                    @endforelse
                </div>
                <a href="{{ route('user.folder') }}" class="dashboard-btn" style="margin-top:20px; display:inline-flex;">⬅ Kembali</a>

            {{-- ================= KONDISI 2: DETAIL SUBFOLDER ================= --}}
            @elseif (($mode ?? '') === 'detail')
                
                <h2 class="main-heading">
                    <span style="color:var(--text-muted); cursor:pointer;" onclick="window.location.href='{{ route('user.folder') }}'">DOKUMENTASI</span> 
                    <span style="color:var(--accent-lime)">/</span> 
                    <span>{{ $currentFolder->nama_folder }}</span>
                </h2>

                <a href="{{ route('user.folder') }}" 
                   class="dashboard-btn" style="display:inline-flex; margin-bottom:25px;">
                   ⬅ Kembali
                </a>

                <div class="folder-grid">
                    @foreach ($currentFolder->children as $sub)
                        <div class="folder-card" onclick="openSubfolder({{ $sub->id }}, '{{ $sub->nama_folder }}')">
                            <div class="folder-tab" style="background:var(--accent-lime)"></div>
                            <div class="icon-box"><img src="{{ asset('img/folderku.png') }}"></div>
                            <div class="file-info">
                                <strong>{{ $sub->nama_folder }}</strong>
                                <small>SUBFOLDER</small>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($currentFolder->files as $file)
                        {{-- BERSIHKAN NAMA FILE (DETAIL) --}}
                        @php $cleanName = preg_replace('/^\d+_/', '', $file->nama_file); @endphp
                        <div class="folder-card"
                            onclick="previewFile('{{ route('admin.file.view', $file->id) }}', '{{ $file->tipe_file }}', '{{ $cleanName }}', {{ $file->id }})">
                            <div class="folder-tab" style="background:{{ $file->color }}"></div>
                            <div class="icon-box"><img src="{{ $file->icon }}"></div>
                            <div class="file-info">
                                <strong>{{ Str::limit($cleanName, 50) }}</strong>
                                <small>{{ strtoupper($file->tipe_file) }}</small>
                            </div>
                        </div>
                    @endforeach

                    @if($currentFolder->children->isEmpty() && $currentFolder->files->isEmpty())
                        <div style="grid-column: 1 / -1; text-align:center; padding: 40px; color:var(--text-muted);">
                            Folder ini kosong
                        </div>
                    @endif
                </div>
            {{-- ================= KONDISI 3: ROOT (HALAMAN AWAL) ================= --}}
            @else
                <h2 class="main-heading">DOKUMENTASI <span>TEKNIS</span></h2>

                @foreach ($sidebarFolders as $folder)
                    <div id="folder-{{ $folder->id }}"></div>
                    <h2 style="font-size:16px;margin-bottom:20px;text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px; margin-top:30px;">
                        {{ $folder->nama_folder }}
                    </h2>

                    <div class="folder-grid">
                        @foreach ($folder->children as $sub)
                            <div class="folder-card" onclick="openSubfolder({{ $sub->id }}, '{{ $sub->nama_folder }}')">
                                <div class="folder-tab" style="background:var(--accent-lime)"></div>
                                <div class="icon-box"><img src="{{ asset('img/folderku.png') }}"></div>
                                <div class="file-info">
                                    <strong>{{ $sub->nama_folder }}</strong>
                                    <small>SUBFOLDER</small>
                                </div>
                            </div>
                        @endforeach

                        @foreach ($folder->files as $file)
                            {{-- BERSIHKAN NAMA FILE (ROOT) --}}
                            @php $cleanName = preg_replace('/^\d+_/', '', $file->nama_file); @endphp
                            <div class="folder-card"
                                onclick="previewFile('{{ route('admin.file.view', $file->id) }}', '{{ $file->tipe_file }}', '{{ $cleanName }}', {{ $file->id }})">
                                <div class="folder-tab" style="background:{{ $file->color }}"></div>
                                <div class="icon-box"><img src="{{ $file->icon }}"></div>
                                <div class="file-info">
                                    <strong>{{ Str::limit($cleanName, 50) }}</strong>
                                    <small>{{ strtoupper($file->tipe_file) }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>

        <div class="right-panel">
 <div class="card-modern" style="height: 250px; flex: none;"> 
    <h4 style="margin:0;font-weight:800;color:var(--accent-lime);border-bottom:1px solid var(--border-glass);padding-bottom:12px;font-size:13px;">
        RIWAYAT TERBARU
    </h4>
   <div style="margin-top:10px;" class="hide-scrollbar">
        {{-- Kita ambil 6 data saja dari koleksi $recentFiles --}}
        @foreach ($recentFiles->take(6) as $rf)
            @php $cleanName = preg_replace('/^\d+_/', '', $rf->nama_file); @endphp
            <div class="history-row"
                onclick="previewFile('{{ route('admin.file.view', $rf->id) }}','{{ $rf->tipe_file }}','{{ $cleanName }}', {{ $rf->id }})">
                <img src="{{ $rf->icon }}" class="history-icon">
                <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $cleanName }}
                </div>
            </div>
        @endforeach
    </div>
            </div>

         <div class="card-modern" style="padding: 12px;"> <h4 style="margin:0;font-weight:800;text-align:center;color:var(--accent-lime);font-size:11px;">
        KALENDER
    </h4>
    <div id="calendar-grid" class="calendar-grid" style="margin-top: 5px;"></div>
</div>
        </div>

    </div>

    <div class="modal-preview" id="previewModal" onclick="closePreview()">
        <div class="preview-box" onclick="event.stopPropagation()">
            
            <div class="preview-header">
                <strong id="previewTitle" style="font-size:16px;">Preview</strong>
                
                <div class="header-actions">
                    <div id="editButtonContainer"></div>

                    <button type="button" onclick="closePreview()"
                        style="background:none;border:none;color:white;font-size:24px;cursor:pointer;line-height:1;">&times;</button>
                </div>
            </div>

            <div class="preview-body" id="previewBody"></div>

            <div class="preview-footer">
                <span id="previewType" class="text-muted"></span>
                <a id="downloadBtn" href="#" download>⬇ Download</a>
            </div>
        </div>
    </div>

  <script>
    // ================= SCROLL =================
    function scrollToFolder(id) {
        const target = document.getElementById(id);
        if (window.innerWidth <= 1024) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            const container = document.getElementById('contentArea');
            if (!target || !container) return;
            container.scrollTo({ top: target.offsetTop - 10, behavior: 'smooth' });
        }
    }

    // ================= PREVIEW LOGIC (INTEGRASI ONLYOFFICE) =================
    function previewFile(url, ext, name, fileId) {
        ext = (ext || '').toLowerCase();
        
        // 1. Set Info
        document.getElementById('previewTitle').innerText = name;
        document.getElementById('previewType').innerText = ext.toUpperCase();
        
        // 2. Set Download Link
        const downloadBtn = document.getElementById('downloadBtn');
        if(url.includes('storage')) {
             downloadBtn.href = url;
        } else {
             downloadBtn.href = `/adminku/file/${fileId}/download`; 
        }
        downloadBtn.setAttribute('download', name);

        // 3. Reset Container Edit
        const editContainer = document.getElementById('editButtonContainer');
        editContainer.innerHTML = '';

        // 4. Cek apakah bisa diedit (Office)
        let editableTypes = ['docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt', 'csv', 'txt'];
        
        if (editableTypes.includes(ext)) {
            // FIX: Tambahkan onclick="closePreview()" agar modal tertutup saat edit diklik
            let editorUrl = `/admin/editor/${fileId}`;
            editContainer.innerHTML = `
                <a href="${editorUrl}" target="_blank" class="btn-edit-action" onclick="closePreview()">
                    ✎ Edit Dokumen
                </a>
            `;
        }

        // 5. TAMPILKAN LOADING
        let body = document.getElementById('previewBody');
        body.innerHTML = `
            <div style="height:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; color:#fff;">
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
                <span style="font-weight:600; letter-spacing:0.5px;">Memuat dokumen...</span>
            </div>
        `;

        // 6. Buka Modal
        document.getElementById('previewModal').classList.add('active');

        // 7. Render Preview
        setTimeout(() => {
            
            // --- PDF ---
            if (ext === 'pdf') {
                body.innerHTML = `<iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>`;
            }
            // --- GAMBAR ---
            else if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp','heic'].includes(ext)) {
                body.innerHTML = `<img src="${url}" style="max-width:100%; max-height:100%; object-fit:contain; margin:auto; display:block;">`;
            }
            // --- VIDEO ---
            else if (['mp4', 'webm', 'ogg'].includes(ext)) {
                body.innerHTML = `
                    <video controls style="width:100%; max-height:100%;">
                        <source src="${url}" type="video/${ext}">
                        Browser tidak mendukung video.
                    </video>`;
            }
            // --- ONLYOFFICE (VIEW MODE) ---
            else if (editableTypes.includes(ext)) {

                body.innerHTML = `<div id="onlyoffice-preview" style="width:100%; height:100%;"></div>`;

                if (window.docEditor) window.docEditor.destroyEditor();

                // Dapatkan URL Origin (Absolute) untuk stabilitas CSV
                let finalUrl = url;
                if (!url.startsWith('http')) {
                    finalUrl = window.location.origin + url;
                }

                initOnlyOfficePreview(fileId, name, finalUrl, ext);
            }
            // --- ZIP/RAR ---
            else if (['zip', 'rar'].includes(ext)) {
                 body.innerHTML = '<div style="text-align:center;color:white;margin-top:20px;">Membaca isi arsip...</div>';
                 
                 // Fetch ZIP Content
                 fetch(`/admin/zip/${fileId}/content`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'error') {
                            body.innerHTML = `<div style="text-align:center;color:red;">${data.message}</div>`;
                            return;
                        }

                        let html = `
                            <div style="padding:20px; overflow-y:auto; height:100%;">
                                <table style="width:100%; border-collapse:collapse; color:#fff; font-size:13px;">
                                    <thead style="border-bottom:1px solid #333; text-align:left;">
                                        <tr>
                                            <th style="padding:10px;">Nama File</th>
                                            <th style="padding:10px;">Ukuran</th>
                                            <th style="padding:10px;">Aksi</th>
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
                                        style="background:var(--accent-lime); border:none; border-radius:4px; 
                                        padding:4px 10px; cursor:pointer; font-weight:bold; font-size:11px;">
                                        ${isDoc ? '👁 Lihat' : '⬇ Unduh'}
                                    </button>
                                `;
                            }

                            html += `
                                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                    <td style="padding:12px 10px;">${item.name}</td>
                                    <td style="padding:12px 10px; color:#aaa;">${item.size}</td>
                                    <td style="padding:12px 10px;">${actionBtn}</td>
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
            else {
                body.innerHTML = `
                    <div style="text-align:center; margin-top:50px;">
                        <p style="color:#aaa;">Preview tidak tersedia untuk format ini</p>
                    </div>`;
            }

        }, 300);
    }

    // Helper OnlyOffice Preview
    async function initOnlyOfficePreview(fileId, name, url, type) {
        
        let docType = 'text'; // Default Word
        if (['xls', 'xlsx', 'csv', 'ods'].includes(type)) {
            docType = 'spreadsheet';
        } else if (['ppt', 'pptx', 'odp'].includes(type)) {
            docType = 'presentation';
        }

        let key = "preview_" + fileId + "_" + Date.now();

        // Cari CSRF Token (Meta / Form)
        let csrfToken = null;
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) csrfToken = metaTag.getAttribute('content');
        
        if(!csrfToken) {
            console.error("CSRF Token tidak ditemukan!");
            return;
        }

        try {
            // Gunakan Route Helper
            const response = await fetch("{{ route('admin.get-token') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    fileId: fileId,
                    fileType: type,
                    key: key,
                    title: name,
                    url: url,
                    documentType: docType,
                    mode: 'view'
                })
            });

            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();
            if (data.error) throw new Error(data.error);

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
                `<div style="color:red; text-align:center; padding:20px;">Gagal memuat dokumen: ${error.message}</div>`;
        }
    }

    // Fungsi Khusus Membuka Item di dalam ZIP
    function openZipItem(zipId, filePath) {
        let ext = filePath.split('.').pop().toLowerCase();
        let fileName = filePath.split('/').pop();

        // URL Ekstraksi Absolute
        let extractUrl = window.location.origin + `/admin/zip/${zipId}/extract?path=` + encodeURIComponent(filePath);

        let officeTypes = ['docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt', 'csv', 'txt'];
        
        if (officeTypes.includes(ext)) {
            document.getElementById('previewTitle').innerText = fileName;
            let body = document.getElementById('previewBody');
            body.innerHTML = `<div id="onlyoffice-preview" style="width:100%; height:100%;"></div>`;
            
            initOnlyOfficePreview(zipId, fileName, extractUrl, ext);
        } 
        else if (['jpg', 'png', 'pdf', 'mp4'].includes(ext)) {
             window.open(extractUrl, '_blank');
        }
        else {
            window.location.href = extractUrl;
        }
    }

    function closePreview() {
        document.getElementById('previewModal').classList.remove('active');
        if (window.docEditor) { window.docEditor.destroyEditor(); }
        setTimeout(() => {
            document.getElementById('previewBody').innerHTML = '';
        }, 300);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePreview();
    });

    // ================= CALENDAR =================
    function loadCalendar() {
        const now = new Date();
        const grid = document.getElementById("calendar-grid");
        if (!grid) return;
        grid.innerHTML = "";
        ["S", "S", "R", "K", "J", "S", "M"].forEach(d => {
            const el = document.createElement("span");
            el.style.fontWeight = "800";
            el.style.color = "var(--accent-lime)";
            el.innerText = d;
            grid.appendChild(el);
        });
        const total = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
        for (let d = 1; d <= total; d++) {
            const day = document.createElement("div");
            day.className = "calendar-day" + (d === now.getDate() ? " cal-today" : "");
            day.innerText = d;
            grid.appendChild(day);
        }
    }
    document.addEventListener("DOMContentLoaded", loadCalendar);
    
    // ================= NAVIGASI SUB-FOLDER =================
    function openSubfolder(id, name) {
        let targetUrl = new URL("{{ route('user.folder') }}");
        targetUrl.searchParams.set('folder_id', id);
        window.location.href = targetUrl.toString();
    }
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            
            // 1. Cari di dalam grid Folder & File
            // Kita mengambil semua elemen .folder-card
            const cards = document.querySelectorAll('.folder-card');

            cards.forEach(card => {
                // Ambil teks di dalam elemen <strong> (tempat nama folder/file berada)
                const textElement = card.querySelector('strong');
                if (textElement) {
                    const textValue = textElement.textContent || textElement.innerText;
                    
                    // Jika cocok, tampilkan card. Jika tidak, sembunyikan.
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        card.style.display = ""; // Tampilkan
                    } else {
                        card.style.display = "none"; // Sembunyikan
                    }
                }
            });

            // 2. Sembunyikan Judul (h3) jika tidak ada isinya (Opsional agar lebih rapi)
            const headings = document.querySelectorAll('.content-area h3');
            headings.forEach(h => {
                // Jika ingin heading folder/file juga hilang saat pencarian kosong, 
                // Anda bisa menambahkan logika tambahan di sini.
            });
        });
    }
});
</script>
</body>

</html>