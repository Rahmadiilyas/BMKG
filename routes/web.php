<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SessionController::class, 'index'])->name('login');
Route::post('/sesi/login', [SessionController::class, 'login'])->name('login.proses');

Route::get('/admin/file/view/{id}', [AdminController::class, 'viewFile'])
    ->name('admin.file.view');
  Route::get('/onlyoffice/file/{id}', [AdminController::class, 'onlyOfficeFile'])->name('onlyoffice.file');;
Route::get('/Dashboard-Teknisi', [AdminController::class, 'teknisidasbord1'])->name('user.dashboard');
Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
 Route::get('/adminku/file/{id}/download', [AdminController::class, 'downloadFile1'])
     ->name('admin.file.download1');
     Route::get('/excel-preview/{id}', [AdminController::class, 'previewExcelAsPdf']);
Route::post('/onlyoffice/callback/{id}', [AdminController::class, 'callback'])
    ->name('onlyoffice.callback');
    Route::get('/admin/editor/{id}', [AdminController::class, 'editorPage'])->name('admin.editor.page');
    Route::get('/onlyoffice/file/{id}', [AdminController::class, 'onlyOfficeFile'])->name('onlyoffice.file');

Route::get('/admin/zip/{id}/content', [AdminController::class, 'getZipContent']);

// Route untuk mengambil/membuka file SATUAN di dalam ZIP
Route::get('/admin/zip/{id}/extract', [AdminController::class, 'extractZipItem']);
Route::post('/admin/get-token', [AdminController::class, 'generateOnlyOfficeToken'])->name('admin.get-token');
Route::middleware(['auth'])->group(function () {
Route::post('/send-otp', [SessionController::class, 'sendOTP'])->name('admin.sendOTP');

    // 2. Route untuk memproses update Email/Password
    Route::post('/update-account', [SessionController::class, 'updateAccount'])->name('admin.updateAccount');

Route::get('/subfolder/dokumentasi/teknis',[AdminController::class, 'folder'])->name('user.folder');

    Route::get('/folder/{id}', [AdminController::class, 'show'])
        ->name('user.show');

        Route::get('/admin/file/{id}/download', [AdminController::class, 'downloadFile'])
     ->name('admin.file.download');
       
    Route::get('/dokumentasiteknisi', [AdminController::class, 'folder'])->name('dashboardnya');
    // Route::get('/dokumentasi-teknis', [AdminController::class, 'folder'])
    //     ->name('user.folder');

    // link
    Route::get("admin/link", [AdminController::class, 'lihatlink'])->name("admin.lihatlink");
    Route::get("admin/tambahlink", [AdminController::class, 'tambahlink'])->name("admin.tambahlink");
    Route::post("admin/tambahlink/simpan", [AdminController::class, 'simpanlink'])->name("admin.simpanlink");
    Route::get('admin/editlink/{id}', [AdminController::class, 'editlink'])->name("admin.editlink");
    Route::post('admin/editlink/simpan/{id}', [AdminController::class, 'updatelink'])->name("admin.updatelink");
    Route::post('admin/editlink/delete/{id}', [AdminController::class, 'deletelink'])->name("admin.deletelink");

    // teknisi
    Route::get("admin/teknisi", [AdminController::class, 'lihatteknisi'])->name("admin.lihatteknisi");
    Route::get("admin/tambahteknisi", [AdminController::class, 'tambahteknisi'])->name("admin.tambahteknisi");
    Route::post("admin/tambahteknisi/simpan", [AdminController::class, 'simpanteknisi'])->name("admin.simpanteknisi");
    Route::get('admin/editteknisi/{id}', [AdminController::class, 'editteknisi'])->name("admin.editteknisi");
    Route::post('admin/editteknisi/simpan/{id}', [AdminController::class, 'updateteknisi'])->name("admin.updateteknisi");
    Route::post('admin/editteknisi/delete/{id}', [AdminController::class, 'deleteteknisi'])->name("admin.deleteteknisi");

    // folder
    Route::get('/admin/folder', [AdminController::class, 'lihatfolder'])->name('admin.lihatfolder');
    Route::get('/admin/folder/{id}', [AdminController::class, 'bukaFolder'])->name('admin.bukafolder');

    Route::post('/admin/folder/simpan', [AdminController::class, 'simpanfolder'])->name('admin.simpanfolder');
    Route::post('/admin/folder/{id}/subfolder', [AdminController::class, 'simpansubfolder'])->name('admin.simpansubfolder');
    Route::post('/admin/folder/{id}/file', [AdminController::class, 'simpanfile'])->name('admin.simpanfile');

    Route::get('/admin/folder/{id}/edit', [AdminController::class, 'editfolder'])->name('admin.editfolder');
    Route::post('/admin/folder/{id}/update', [AdminController::class, 'updatefolder'])->name('admin.updatefolder');

    // rename
    Route::post('/admin/folder/{id}/rename', [AdminController::class, 'renameFolder'])
        ->name('admin.folder.rename');

    Route::post('/admin/file/{id}/rename', [AdminController::class, 'renameFile'])
        ->name('admin.file.rename');

    // delete
    Route::post('/admin/folder/{id}/delete', [AdminController::class, 'deleteFolderku'])
        ->name('admin.folder.delete');

    Route::post('/admin/file/{id}/delete', [AdminController::class, 'deleteFile'])
        ->name('admin.file.delete');

    // kategori
    Route::get('/admin/kategori', [AdminController::class, 'lihatkategori'])
        ->name('admin.lihatkategori');

    Route::post('/admin/kategori/simpan', [AdminController::class, 'simpankategori'])
        ->name('admin.simpankategori');

    Route::post('/admin/kategori/{id}/update', [AdminController::class, 'updatekategori'])
        ->name('admin.updatekategori');

    Route::post('/admin/kategori/{id}/delete', [AdminController::class, 'deletekategori'])
        ->name('admin.deletekategori');

    Route::get('/admin/Kelola-Data', [AdminController::class, 'teknisidasbord'])->name('admin.dashboardku');

    Route::get('admin/arsip-link', [AdminController::class, 'arsipLink'])
        ->name('admin.arsiplink');

      

});
