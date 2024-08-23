<?php

use App\Http\Controllers\BangunDatarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\kalkulatorController;

Route::get('/', [KalkulatorController::class, 'index']);

Route::get('/bangun-datar', [BangunDatarController::class, 'index']);

// kalkulator

Route::post('/simpan-perhitungan', [KalkulatorController::class, 'simpanPerhitungan'])->name('simpan-perhitungan');

Route::put('/edit/{id}', [KalkulatorController::class, 'editPerhitungan'])->name('edit-perhitungan');

Route::delete('/hapus-perhitungan/{id}', [KalkulatorController::class, 'hapusPerhitungan'])->name('hapus-perhitungan');

// bangun datar

Route::post('/simpan-bangun-datar', [BangunDatarController::class, 'SimpanBangunDatar']);

Route::put('/edit-bangun-datar/{id}', [BangunDatarController::class, 'editBangunDatar'])->name('edit-bangun-datar');

Route::delete('/hapus-bangun-datar', [BangunDatarController::class, 'destroy'])->name('hapus-bangun-datar');

