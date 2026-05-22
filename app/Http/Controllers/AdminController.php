<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Folder;
use App\Models\Link;
use App\Models\LinkArsip;
use App\Models\Teknisi;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Imagick;
use Firebase\JWT\Key; // Tambahkan ini juga untuk jaga-jaga versi baru
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class AdminController extends Controller
{

    public function lihatlink(Request $request)
    {
        $categories = Category::all();
        $query = Link::with('category');
        //filter
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        $link = $query->get();
        return view("admin.lihatlink", compact("link", "categories"));
    }

    public function simpanlink(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'judul_link'  => 'required',
            'url'         => 'required'
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'judul_link.required'  => 'Judul link tidak boleh kosong.',
            'url.required'         => 'URL wajib diisi.',
        ]);

        $cek = Link::where('kategori_id', $request->kategori_id)->first();

        if ($cek) {
            return back()->with(
                'error',
                'Kategori ini sudah memiliki link. Silakan edit link yang ada.'
            );
        }

        Link::create([
            'kategori_id' => $request->kategori_id,
            'judul_link'  => $request->judul_link,
            'url'         => $request->url,
            'keterangan'  => $request->keterangan
        ]);

        return redirect()->route('admin.lihatlink');
    }

    public function updatelink(Request $request, $id)
    {
        $link = Link::find($id);
        $request->validate([
            'kategori_id' => 'required',
            'judul_link'  => 'required',
            'url'         => 'required'
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'judul_link.required'  => 'Judul link tidak boleh kosong.',
            'url.required'         => 'URL wajib diisi.',
        ]);

        LinkArsip::create([
            'link_id'     => $link->id,
            'kategori_id' => $link->kategori_id,
            'judul_link'  => $link->judul_link,
            'url'         => $link->url,
            'keterangan'  => $link->keterangan,
        ]);

        $link->update([
            'kategori_id' => $request->kategori_id,
            'judul_link'  => $request->judul_link,
            'url'         => $request->url,
            'keterangan'  => $request->keterangan
        ]);

        return redirect()->route('admin.lihatlink');
    }

    public function arsipLink()
    {
        $arsip = LinkArsip::with('kategori')->latest()->get();
        return view('admin.arsiplink', compact('arsip'));
    }

    public function deletelink($id)
    {
        $link = Link::find($id);
        $link->delete();
        return redirect()->route('admin.lihatlink');
    }

    // ==========================================
    // 2. MANAJEMEN TEKNISI
    // ==========================================
    public function lihatteknisi()
    {
        $teknisi = Teknisi::all();
        return view("admin.lihatteknisi", compact("teknisi"));
    }

    public function simpanteknisi(Request $request)
    {
        $request->validate([
            'nama_teknisi' => 'required',
            'no_hp'  => 'required|regex:/^[0-9]+$/|min:10|max:15',
            'email'  => 'required|email',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_teknisi.required' => 'Nama teknisi wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP hanya boleh angka.',
            'no_hp.min' => 'Nomor HP minimal 10 digit.',
            'no_hp.max' => 'Nomor HP maksimal 15 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'gambar.required' => 'Foto teknisi wajib diupload.',
            'gambar.image' => 'File harus berupa gambar.',
        ]);

        // upload gambar
        $file = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/teknisi'), $namaFile);

        Teknisi::create([
            'nama_teknisi' => $request->nama_teknisi,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'gambar'       => $namaFile,
        ]);

        return redirect()->route("admin.lihatteknisi");
    }

    public function updateteknisi(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);

        $request->validate([
            'nama_teknisi' => 'required',
            'no_hp'  => 'required|regex:/^[0-9]+$/|min:10|max:15',
            'email'  => 'required|email',
            'gambar' => 'image|mimes:jpg,jpeg,png|max:2048', // Gambar optional saat update
        ]);

        // kalau upload gambar baru
        if ($request->hasFile('gambar')) {
            // hapus gambar lama
            $oldPath = public_path('uploads/teknisi/' . $teknisi->gambar);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/teknisi'), $namaFile);

            $teknisi->gambar = $namaFile;
        }

        $teknisi->update([
            'nama_teknisi' => $request->nama_teknisi,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
        ]);

        return redirect()->route("admin.lihatteknisi");
    }

    public function deleteteknisi($id)
    {
        $teknisi = Teknisi::findOrFail($id);

        // hapus file foto
        $path = public_path('uploads/teknisi/' . $teknisi->gambar);
      if (\Illuminate\Support\Facades\File::exists($path)) {
        \Illuminate\Support\Facades\File::delete($path);
    }

        $teknisi->delete();
        return redirect()->route("admin.lihatteknisi");
    }

    // ==========================================
    // 3. MANAJEMEN FOLDER
    // ==========================================
    public function lihatfolder()
    {
        // hanya folder utama (parent_id = null)
        $folders = Folder::with('parent')
            ->whereNull('parent_id')
            ->get();

        return view('admin.lihatfolder', compact('folders'));
    }

    public function simpanfolder(Request $request)
    {
        $request->validate([
            'nama_folder' => 'required'
        ]);

        Folder::create([
            'nama_folder' => $request->nama_folder,
            'parent_id'   => $request->parent_id
        ]);
        return redirect()->route('admin.lihatfolder');
    }

    public function editfolder($id)
    {
        $folders = Folder::find($id);
        return view('admin.editfolder', compact('folders'));
    }

    public function updatefolder(Request $request, $id)
    {
        $folders = Folder::find($id);
        $request->validate([
            'nama_folder' => 'required'
        ]);

        $folders->update([
            'nama_folder' => $request->nama_folder,
            'parent_id'   => $request->parent_id
        ]);

        return redirect()->route('admin.lihatfolder');
    }

    public function deletefolder($id)
    {
        $folders = Folder::find($id);
        $folders->delete();
        return redirect()->route('admin.lihatfolder');
    }

    public function bukaFolder($id)
    {
        $folder = Folder::with(['children', 'files'])->findOrFail($id);
        return view('admin.detailfolder', compact('folder'));
    }

    public function simpansubfolder(Request $request, $id)
    {
        $request->validate([
            'nama_folder' => 'required'
        ]);

        Folder::create([
            'nama_folder' => $request->nama_folder,
            'parent_id'   => $id
        ]);

        return back()->with('success', 'Subfolder berhasil ditambahkan');
    }

    public function renameFolder(Request $request, $id)
    {
        $request->validate(['nama' => 'required']);
        $folder = Folder::findOrFail($id);
        $folder->update(['nama_folder' => $request->nama]);
        return back();
    }

    public function deleteFolderku($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();   // subfolder & file ikut terhapus (cascade)
        return back();
    }

    // ==========================================
    // 4. MANAJEMEN FILE (UPLOAD & CRUD)
    // ==========================================
    public function simpanfile(Request $request, $id)
    {
        set_time_limit(0);

        $request->validate([
            'files'   => 'required',
            'files.*' => 'required|max:102400', // 100MB
        ], [
            'files.required' => 'Pilih file terlebih dahulu.',
            'files.*.max'    => 'Ada file yang melebihi batas 100MB.',
        ]);

        try {

            foreach ($request->file('files') as $upload) {

                $ext = strtolower($upload->getClientOriginalExtension());
                $originalName = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);

                $timestamp = time();
                $folderPath = 'uploads';

                // ======================
                // KHUSUS HEIC
                // ======================
                if ($ext === 'heic') {

                    $newName = $timestamp . '_' . $originalName . '.jpg';

                    $imagick = new Imagick();
                    $imagick->readImage($upload->getPathname());
                    $imagick->setImageFormat('jpg');

                    // simpan ke drive H
                    $fullPath = Storage::disk('drive_h')->path($folderPath . '/' . $newName);
                    $imagick->writeImage($fullPath);

                    $imagick->clear();
                    $imagick->destroy();

                    File::create([
                        'folder_id' => $id,
                        'nama_file' => $newName,
                        'path_file' => 'H:/teknisi/' . $folderPath . '/' . $newName,
                        'tipe_file' => 'jpg'
                    ]);
                }

                // ======================
                // FILE NORMAL
                // ======================
                else {

                    $fileName = $timestamp . '_' . $upload->getClientOriginalName();

                    $path = Storage::disk('drive_h')
                        ->putFileAs($folderPath, $upload, $fileName);

                    File::create([
                        'folder_id' => $id,
                        'nama_file' => $fileName,
                        'path_file' => 'H:/teknisi/' . $path,
                        'tipe_file' => $ext
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Upload berhasil'
            ]);
        } catch (\Exception $e) {

            \Log::error("Gagal simpan file: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal upload: ' . $e->getMessage()
            ], 500);
        }
    }
    public function renameFile(Request $request, $id)
    {
        $request->validate(['nama' => 'required']);
        $file = File::findOrFail($id);
        $file->update(['nama_file' => $request->nama]);
        return back();
    }

    public function deleteFile($id)
    {
        $file = File::findOrFail($id);

        if (file_exists($file->path_file)) {
            unlink($file->path_file);
        }

        $file->delete();
        return back();
    }

    public function downloadFile1($id)
    {
        $file = File::findOrFail($id);

        // Perbaikan: Cek path manual untuk Drive H
        $path = $file->path_file;

        // Jika path di database pakai slash terbalik (khas Windows), perbaiki dulu
        // Contoh database: "H:\teknisi\file.pdf", tapi PHP butuh "H:/teknisi/file.pdf"
        $fixedPath = str_replace('\\', '/', $path);

        if (!file_exists($fixedPath)) {
            return back()->with('error', 'File fisik tidak ditemukan di Drive H!');
        }

        return response()->download($fixedPath, $file->nama_file);
    }

  public function viewFile($id)
{
    $file = File::findOrFail($id);

    $path = $file->path_file;

    // cek file ada
    if (!file_exists($path)) {
        $fixedPath = str_replace('/', '\\', $path);
        if (!file_exists($fixedPath)) {
            abort(404, 'File fisik hilang dari Drive H.');
        }
        $path = $fixedPath;
    }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    // ================= HEIC =================
    if ($ext === 'heic') {
        try {
            $imagick = new Imagick($path);
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompressionQuality(90);

            return response($imagick)
                ->header('Content-Type', 'image/jpeg')
                ->header('Content-Disposition', 'inline; filename="'.pathinfo($path, PATHINFO_FILENAME).'.jpg"');

        } catch (\Exception $e) {
            Log::error("Gagal convert HEIC: ".$e->getMessage());
            abort(500, 'Gagal preview HEIC');
        }
    }

    // ================= IMAGE NORMAL =================
    if (in_array($ext, ['jpg','jpeg','png','gif','svg','webp'])) {
        return response()->file($path, [
            'Content-Type' => mime_content_type($path),
            'Content-Disposition' => 'inline; filename="'.basename($path).'"'
        ]);
    }

    // ================= PDF =================
    if ($ext === 'pdf') {
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"'
        ]);
    }

    // ================= VIDEO =================
    if (in_array($ext, ['mp4','mkv','avi','mov'])) {
        return response()->file($path, [
            'Content-Type' => 'video/'.$ext,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"'
        ]);
    }

    // ================= DEFAULT (DOWNLOAD) =================
    return response()->download($path);
}

    // ==========================================
    // 5. MANAJEMEN KATEGORI
    // ==========================================
    public function lihatkategori()
    {
        $kategori = Category::all();
        return view("admin.lihatkategori", compact('kategori'));
    }

    public function simpankategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/kategori'), $namaFile);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'gambar' => $namaFile
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updatekategori(Request $request, $id)
    {
        $kategori = Category::findOrFail($id);

        $data = [
            'nama_kategori' => $request->nama_kategori
        ];

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kategori'), $namaFile);

            // hapus foto lama
            if ($kategori->gambar && file_exists(public_path('uploads/kategori/' . $kategori->gambar))) {
                unlink(public_path('uploads/kategori/' . $kategori->gambar));
            }

            $data['gambar'] = $namaFile;
        }

        $kategori->update($data);
        return back()->with('success', 'Kategori berhasil diupdate');
    }

    public function deletekategori($id)
    {
        $kategori = Category::findOrFail($id);

        if ($kategori->gambar && file_exists(public_path('uploads/kategori/' . $kategori->gambar))) {
            unlink(public_path('uploads/kategori/' . $kategori->gambar));
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }

    // ==========================================
    // 6. DASHBOARD & UTILS
    // ==========================================
    public function teknisidasbord()
    {
        $teknisi = Teknisi::all();
        $totalFile = File::count();
        $files = File::all();
        $totalSize = 0;

        foreach ($files as $f) {
            $realPath = $f->path_file;
            if (file_exists($realPath)) {
                $totalSize += filesize($realPath);
            }
        }

        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
        $categories = Category::with('links')->get();

        return view('admin.dashboard', compact(
            'teknisi',
            'categories',
            'totalFile',
            'totalSizeMB'
        ));
    }

    public function teknisidasbord1()
    {
        $teknisi = Teknisi::all();
        $categories = Category::with('links')->get();
        return view('admin.dashboard1', compact('teknisi', 'categories'));
    }

    public function show($id)
    {
        $kategori = Category::findOrFail($id);
        return view('fitur.show', compact('kategori'));
    }

    private function fileIcon($type)
    {
        $type = strtolower($type ?? '');
        return match ($type) {
            'pdf' => asset('img/pdfku.png'),
            'doc', 'docx' => asset('img/wordku.png'),
            'xls', 'xlsx' => asset('img/xlku.png'),
            'ppt', 'pptx' => asset('img/pptku.png'),
            'jpg', 'jpeg', 'png', 'gif', 'svg', 'heic' => asset('img/camera.jpg'),
            'mp4', 'mkv', 'avi', 'mov' => asset('img/vidio.jpg'),
            'rar', 'zip', '7z' => asset('img/zip.png'),
            default => asset('img/folderku.png'),
        };
    }

    private function folderColor($type)
    {
        $type = strtolower($type ?? '');
        return match ($type) {
            'pdf' => '#e74c3c',
            'doc', 'docx' => '#2b579a',
            'xls', 'xlsx' => '#217346',
            'ppt', 'pptx' => '#b7472a',
            'jpg', 'jpeg', 'png', 'gif', 'svg' => '#9b59b6',
            'mp4', 'mkv', 'avi', 'mov' => '#34495e',
            'rar', 'zip', '7z' => '#f1c40f',
            default => '#adff2f',
        };
    }

    // ==========================================
    // 7. HALAMAN UTAMA FILE (Folder View)
    // ==========================================
    // ==========================================
    // 7. HALAMAN UTAMA FILE (Folder View)
    // ==========================================
    public function folder(Request $request)
    {
        $keyword = $request->get('q');
        $folderId = $request->get('folder_id'); // <--- Tangkap ID folder

        // ===== SIDEBAR (MENU UTAMA) SELALU ADA =====
        // Kita butuh ini dirender di SEMUA kondisi agar sidebar kiri tidak hilang
        $sidebarFolders = Folder::with(['children', 'files'])
            ->whereNull('parent_id')->get();

        // Helper function (Closure) untuk format icon agar tidak duplikat kode
        $formatFiles = function ($files) {
            $files->each(function ($f) {
                $f->icon  = $this->fileIcon($f->tipe_file);
                $f->color = $this->folderColor($f->tipe_file);
            });
        };

        // Format sidebar icons
        $sidebarFolders->each(function ($folder) use ($formatFiles) {
            $formatFiles($folder->files);
        });

        // ===== AJAX REQUEST (Jika butuh JSON) =====
        if ($request->query('ajax')) {
            $folder = Folder::with('files')->findOrFail($request->query('folder'));
            return response()->json([
                'files' => $folder->files->map(function ($f) {
                    return [
                        'name'  => $f->nama_file,
                        'ext'   => $f->tipe_file,
                        'url'   => asset('storage/' . $f->path_file),
                        'icon'  => $this->fileIcon($f->tipe_file),
                        'color' => $this->folderColor($f->tipe_file),
                    ];
                })
            ]);
        }

        // Siapkan Recent Files untuk Panel Kanan (Selalu ada)
        $recentFiles = File::latest()->take(5)->get();
        $formatFiles($recentFiles);

        // ================= SEARCH MODE =================
        if ($keyword) {
            $files = File::where('nama_file', 'like', "%$keyword%")->get();
            $folders = Folder::where('nama_folder', 'like', "%$keyword%")->get();
            $formatFiles($files);

            return view('admin.dokumentasiteknis', [
                'mode' => 'search',
                'keyword' => $keyword,
                'files' => $files,
                'folders' => $folders,
                'sidebarFolders' => $sidebarFolders,
                'recentFiles' => $recentFiles, // Panel kanan tetap ada
            ]);
        }

        // ================= SUBFOLDER MODE (DETAIL) =================
        // Logika baru: Jika ada folder_id, tampilkan isinya saja
        if ($folderId) {
            $currentFolder = Folder::with(['children', 'files'])->findOrFail($folderId);

            // Format file di folder yang sedang dibuka
            $formatFiles($currentFolder->files);

            return view('admin.dokumentasiteknis', [
                'mode' => 'detail', // Mode baru
                'currentFolder' => $currentFolder,
                'sidebarFolders' => $sidebarFolders, // Sidebar kiri tetap
                'recentFiles' => $recentFiles,       // Sidebar kanan tetap
            ]);
        }

        // ================= NORMAL MODE (ROOT) =================
        return view('admin.dokumentasiteknis', [
            'mode' => 'root',
            'sidebarFolders' => $sidebarFolders,
            'recentFiles' => $recentFiles,
        ]);
    }

    // ==========================================
    // 8. INTEGRASI ONLYOFFICE (CRITICAL)
    // ==========================================

    // A. Halaman Editor Full Screen (Tab Baru)
    // Di AdminController.php

    public function editorPage($id)
    {
        $file = File::findOrFail($id);

        // 1. Tentukan Path Fisik di Drive H
        $filePath = $file->path_file; // Contoh: H:/teknisi/uploads/doc.docx

        // Fix slash windows jika perlu
        $filePath = str_replace('\\', '/', $filePath);

        // 2. GENERATE KEY DINAMIS (RAHASIA AGAR BISA SAVE BERULANG KALI)
        // Kita hash konten file fisik. Jika file berubah, key berubah.
        if (file_exists($filePath)) {
            $fileHash = md5_file($filePath);
        } else {
            // Fallback jika file fisik belum ada/error
            $fileHash = md5($file->updated_at . $file->nama_file);
        }

        // Key unik gabungan ID + Hash File
        $key = md5($file->id . '-' . $fileHash);

        // 3. Konfigurasi Dasar
        $url = route('onlyoffice.file', ['id' => $file->id]); // URL Download
        $callbackUrl = route('onlyoffice.callback', ['id' => $file->id]); // URL Callback

        $fileType = strtolower($file->tipe_file);
        $documentType = match ($fileType) {
            'xls', 'xlsx', 'csv' => 'cell',
            'ppt', 'pptx' => 'slide',
            default => 'word',
        };

        // 4. Susun Config OnlyOffice
        // RAHASIA HARUS SAMA DENGAN SERVER ONLYOFFICE
        $secret = config('onlyoffice.jwt_secret');

        $config = [
            "document" => [
                "fileType" => $fileType,
                "key" => $key, // <--- INI KUNCI UTAMANYA
                "title" => $file->nama_file,
                "url" => $url,
                "permissions" => [
                    "edit" => true,
                    "download" => true,
                ]
            ],
            "documentType" => $documentType,
            "editorConfig" => [
                "mode" => "edit",
                "lang" => "id",
                "callbackUrl" => $callbackUrl,
                "user" => [
                    "id" => (string) (auth()->id() ?? 'guest'),
                    "name" => auth()->user()->name ?? 'Teknisi BMKG'
                ],
                "customization" => [
                    "forcesave" => true, // Agar tombol save berfungsi
                    "autosave" => false,
                ]
            ]
        ];

        // 5. Generate Token JWT
        $token = JWT::encode($config, $secret, 'HS256');
        $config['token'] = $token;

        return view('admin.editor_page', compact('config'));
    }

    // ==========================================
    // CALLBACK HANDLER (Modifikasi gaya Dosen)
    // ==========================================
    public function callback(Request $request, $id)
    {
        // 1. Baca Data Callback
        $data = $request->all();
        if (empty($data)) {
            $data = json_decode($request->getContent(), true);
        }

        Log::info("Callback OnlyOffice masuk. ID: $id. Status: " . ($data['status'] ?? 'null'));

        $status = $data['status'] ?? 0;

        // Status 2 (Ready for Saving) atau 6 (Force Save)
        if ($status == 2 || $status == 6) {

            $downloadUrl = $data['url'] ?? null;
            if (!$downloadUrl) {
                return response()->json(['error' => 1, 'message' => 'URL download kosong']);
            }

            try {
                $file = File::findOrFail($id);
                $destinationPath = $file->path_file; // Path Drive H

                // Normalisasi path untuk Windows
                $destinationPath = str_replace('/', '\\', $destinationPath);

                // 2. DOWNLOAD MENGGUNAKAN STREAM CONTEXT (Teknik Dosen)
                // Ini mengatasi masalah SSL/Network yang sering gagal di file_get_contents biasa
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 60,
                        'ignore_errors' => true,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                Log::info("Mencoba download file baru dari: $downloadUrl");

                $newContent = file_get_contents($downloadUrl, false, $context);

                if ($newContent === false || empty($newContent)) {
                    Log::error("Gagal mendownload file dari OnlyOffice.");
                    return response()->json(['error' => 1, 'message' => 'Download failed']);
                }

                // 3. SIMPAN KE DRIVE H
                // Pastikan folder ada
                $dir = dirname($destinationPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                $saved = file_put_contents($destinationPath, $newContent);

                if ($saved === false) {
                    throw new \Exception("Gagal menulis file ke Drive H. Cek Permission.");
                }

                // 4. UPDATE TIMESTAMP DATABASE (PENTING!)
                // Agar 'key' di editorPage berubah saat file dibuka lagi nanti
                $file->touch();

                Log::info("Sukses menyimpan file ke Drive H. Ukuran: $saved bytes");
            } catch (\Exception $e) {
                Log::error("Callback Error: " . $e->getMessage());
                return response()->json(['error' => 1, 'message' => $e->getMessage()]);
            }
        }

        return response()->json(['error' => 0]);
    }
    // D. Preview OnlyOffice (Untuk Modal View Only)
    public function previewOnlyOffice($id)
    {
        $file = File::findOrFail($id);
        $path = $file->path_file;

        // Normalisasi Path
        $windowsPath = str_replace('/', '\\', $path);

        if (!file_exists($windowsPath)) {
            Log::error('File tidak ditemukan untuk preview: ' . $windowsPath);
            abort(404, 'File tidak ditemukan');
        }

        $mime = mime_content_type($windowsPath);

        // Stream file ke browser
        return response()->stream(function () use ($windowsPath) {
            readfile($windowsPath);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline'
        ]);
    }
    public function onlyOfficeFile($id)
    {
        $file = File::findOrFail($id);

        // Ambil path H:/...
        $path = $file->path_file;

        // Normalisasi slash Windows (\ jadi /)
        $windowsPath = str_replace('/', '\\', $path);
        // Atau jika path di DB sudah benar pakai slash biasa, sesuaikan.
        // Intinya pastikan file_exists menemukan filenya.
        if (!file_exists($windowsPath)) {
            // Coba alternatif slash
            $windowsPath = str_replace('\\', '/', $path);
        }

        if (!file_exists($windowsPath)) {
            abort(404);
        }

        // Deteksi Content Type
        $type = strtolower($file->tipe_file);
        $contentType = 'application/octet-stream';

        if ($type == 'csv') {
            $contentType = 'text/csv'; // <--- PENTING
        } elseif ($type == 'pdf') {
            $contentType = 'application/pdf';
        } else {
            $contentType = mime_content_type($windowsPath);
        }

        return response()->file($windowsPath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $file->nama_file . '"',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
        ]);
    }

    public function generateOnlyOfficeToken(Request $request)
    {
        // Pastikan Secret Key ini SAMA PERSIS dengan yang ada di local.json / docker-compose OnlyOffice server
        $secret = config('onlyoffice.jwt_secret');

        // Ambil mode dari request, jika tidak ada default ke 'view'
        $mode = $request->mode ?? 'view';

        $payload = [
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "document" => [
                "fileType" => $request->fileType,
                "key" => $request->key,
                "title" => $request->title,
                "url" => $request->url
            ],
            "documentType" => $request->documentType,
            "editorConfig" => [
                "mode" => $mode, // <--- UBAH INI JADI DINAMIS
                "lang" => "id",
                "callbackUrl" => route('onlyoffice.callback', ['id' => $request->fileId]),
                "user" => [
                    // Opsional: Tambahkan nama user agar kelihatan siapa yang edit
                    "id" => (string) auth()->id() ?? 'guest',
                    "name" => auth()->user()->name ?? 'Teknisi BMKG'
                ],
                "customization" => [
                    "compactHeader" => true,
                    "toolbar" => true,
                    "forcesave" => true // Tambahkan ini agar tombol save muncul/aktif
                ]
            ]
        ];

        $token = JWT::encode($payload, $secret, 'HS256');

        return response()->json([
            'token' => $token,
            // Kirim balik config agar JS 100% sama dengan Token
            'config' => $payload
        ]);
    }
    // ==========================================
    // 1. LIHAT ISI ZIP
    // ==========================================
    // ==========================================
    // 1. LIHAT ISI ARSIP (ZIP & RAR)
    // ==========================================
    public function getZipContent($id)
    {
        $file = File::findOrFail($id);
        $path = $this->fixPath($file->path_file); // Menggunakan helper fixPath yang sudah benar tadi

        if (!file_exists($path)) {
            return response()->json(['status' => 'error', 'message' => 'File fisik tidak ditemukan di: ' . $path]);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $files = [];

        // --- SKENARIO 1: FORMAT ZIP (Pakai Native PHP) ---
        if ($ext === 'zip') {
            $zip = new \ZipArchive;
            if ($zip->open($path) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    $files[] = [
                        'name' => $stat['name'],
                        'size' => $this->formatSize($stat['size']),
                        'index' => $i,
                        'is_dir' => substr($stat['name'], -1) == '/'
                    ];
                }
                $zip->close();
                return response()->json(['status' => 'success', 'files' => $files]);
            }
            return response()->json(['status' => 'error', 'message' => 'Gagal membuka ZIP (Corrupt?)']);
        }

        // --- SKENARIO 2: FORMAT RAR (Pakai 7-Zip CLI) ---
        elseif ($ext === 'rar') {
            // Lokasi 7z.exe (Sesuaikan jika beda)
            $sevenZip = '"C:\Program Files\7-Zip\7z.exe"';

            // Command: List isi rar dengan format teknikal (-slt) agar mudah diparsing
            $cmd = "$sevenZip l -slt \"$path\"";

            // Eksekusi command
            $output = [];
            exec($cmd, $output, $returnVar);

            if ($returnVar !== 0) {
                return response()->json(['status' => 'error', 'message' => 'Gagal membaca RAR. Pastikan 7-Zip terinstall.']);
            }

            // Parsing Output 7-Zip
            $currentFile = [];
            foreach ($output as $line) {
                $line = trim($line);

                // 7-Zip memisahkan file dengan baris kosong
                if ($line === '') {
                    if (!empty($currentFile['Path'])) {
                        $files[] = [
                            'name' => $currentFile['Path'],
                            'size' => $this->formatSize($currentFile['Size'] ?? 0),
                            'index' => 0, // RAR tidak butuh index numerik
                            'is_dir' => ($currentFile['Attributes'] ?? '') === 'D' || isset($currentFile['Folder'])
                        ];
                    }
                    $currentFile = []; // Reset untuk file berikutnya
                    continue;
                }

                // Ambil Key = Value
                if (strpos($line, ' = ') !== false) {
                    list($key, $value) = explode(' = ', $line, 2);
                    $currentFile[$key] = $value;
                }
            }

            // Masukkan file terakhir jika ada sisa
            if (!empty($currentFile['Path'])) {
                $files[] = [
                    'name' => $currentFile['Path'],
                    'size' => $this->formatSize($currentFile['Size'] ?? 0),
                    'index' => 0,
                    'is_dir' => ($currentFile['Attributes'] ?? '') === 'D'
                ];
            }

            return response()->json(['status' => 'success', 'files' => $files]);
        }

        return response()->json(['status' => 'error', 'message' => 'Format tidak didukung']);
    }

    // ==========================================
    // 2. EKSTRAK ITEM (ZIP & RAR)
    // ==========================================
    public function extractZipItem(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $archivePath = $this->fixPath($file->path_file);
        $entryName = $request->query('path'); // Nama file yang mau dibuka
        $ext = strtolower(pathinfo($archivePath, PATHINFO_EXTENSION));

        // Folder Temp di Drive H
        $extractDir = 'H:/teknisi/temp_extract/' . $id . '/';
        if (!is_dir($extractDir)) mkdir($extractDir, 0777, true);

        // --- SKENARIO ZIP ---
        if ($ext === 'zip') {
            $zip = new \ZipArchive;
            if ($zip->open($archivePath) === TRUE) {
                $zip->extractTo($extractDir, $entryName);
                $zip->close();
            } else {
                abort(500, 'Gagal buka ZIP');
            }
        }
        // --- SKENARIO RAR ---
        elseif ($ext === 'rar') {
            $sevenZip = '"C:\Program Files\7-Zip\"';

            // Command: Ekstrak (e) file tertentu ke folder tujuan (-o)
            // -y: Yes to all overwrite
            // -r: Recurse (cari file sampai dalam)
            $cmd = "$sevenZip e \"$archivePath\" -o\"$extractDir\" \"$entryName\" -y -r";

            exec($cmd);
        }

        // Cari file hasil ekstrak
        // (Kadang 7zip mengekstrak flat tanpa folder, jadi kita cari basename-nya)
        $targetFile = $extractDir . $entryName;
        if (!file_exists($targetFile)) {
            $targetFile = $extractDir . basename($entryName);
        }

        if (!file_exists($targetFile)) {
            abort(404, 'Gagal mengekstrak file.');
        }

        // Serve file ke Browser/OnlyOffice
        return response()->file($targetFile, [
            'Content-Disposition' => 'inline; filename="' . basename($entryName) . '"'
        ]);
    }

    // Helper kecil untuk format size
    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }

    // Helper Path Drive H (Agar tidak duplikat kode)
    private function fixPath($rawPath)
    {
        // 1. Normalisasi slash biar seragam dulu
        $path = str_replace('\\', '/', $rawPath);

        // 2. Ambil nama filenya saja (Buang semua folder sebelumnya)
        // Ini langkah 'bersih-bersih' paling ekstrim tapi aman
        // Contoh: "H:/teknisi/uploads/data.zip" -> "data.zip"
        // Contoh: "uploads/data.zip" -> "data.zip"
        $filename = basename($path);

        // 3. Susun ulang dengan path yang Anda yakini BENAR
        // Hasilnya PASTI: H:/teknisi/uploads/namafile.zip
        $finalPath = 'H:/teknisi/uploads/' . $filename;

        // 4. Kembalikan ke format Windows (Backslash)
        return str_replace('/', '\\', $finalPath);
    }
}