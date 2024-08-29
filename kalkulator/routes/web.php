<?php

use App\Http\Controllers\BangunDatarController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\kalkulatorController;

Route::get('/', [KalkulatorController::class, 'index']);

Route::get('/bangun-datar', [BangunDatarController::class, 'index']);

Route::get('/gallery', [GalleryController::class, 'index']);

// kalkulator

Route::post('/simpan-perhitungan', [KalkulatorController::class, 'simpanPerhitungan'])->name('simpan-perhitungan');

Route::put('/edit/{id}', [KalkulatorController::class, 'editPerhitungan'])->name('edit-perhitungan');

Route::delete('/hapus-perhitungan/{id}', [KalkulatorController::class, 'hapusPerhitungan'])->name('hapus-perhitungan');

// bangun datar

Route::post('/simpan-bangun-datar', [BangunDatarController::class, 'SimpanBangunDatar']);

Route::put('/edit-bangun-datar/{id}', [BangunDatarController::class, 'editBangunDatar'])->name('edit-bangun-datar');

Route::delete('/hapus-bangun-datar/{id}', [BangunDatarController::class, 'destroy'])->name('hapus-bangun-datar');

// gallery

Route::post('/generate-images', [GalleryController::class, 'generateImages']);

Route::get('/image/{id}', [GalleryController::class, 'showImage']);


// Route untuk menampilkan form upload gambar
Route::get('/upload-form', function () {
    return view('upload'); // Pastikan Anda memiliki view bernama 'upload.blade.php'
});

// Route untuk menangani upload gambar
Route::post('/upload-image', [GalleryController::class, 'uploadImage'])->name('upload.image');