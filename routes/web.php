<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LaporanController;

// ---------------- REDIRECT UTAMA ------------------ //
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

// ---------------- DASHBOARD ------------------ //
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------- RIWAYAT TRANSAKSI ------------------ //
Route::get('/riwayat', [TransactionController::class, 'index'])->name('riwayat.index')->middleware('auth');

// ---------------- BARANG (CRUD) ------------------ //
Route::middleware('auth')->group(function () {
    Route::get('/kelola-barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
});

// ---------------- CETAK & VIEW LAPORAN ------------------ //
Route::middleware('auth')->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');

    // View tiap jenis laporan
    Route::get('/laba-rugi', [LaporanController::class, 'laba_rugi'])->name('laba_rugi');
    Route::get('/neraca', [LaporanController::class, 'neraca'])->name('neraca');
    Route::get('/arus-kas', [LaporanController::class, 'arus_kas'])->name('arus_kas');
    Route::get('/pengadaan', [LaporanController::class, 'pengadaan'])->name('pengadaan');
    Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
    Route::get('/persediaan', [LaporanController::class, 'persediaan'])->name('persediaan');
    Route::get('/produk', [LaporanController::class, 'produk'])->name('produk');
    Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');

    // Tombol cetak PDF per jenis laporan
    Route::get('/cetak/laba-rugi', [LaporanController::class, 'cetakLabaRugi'])->name('cetak.laba_rugi');
    Route::get('/cetak/neraca', [LaporanController::class, 'cetakNeraca'])->name('cetak.neraca');
    Route::get('/cetak/arus-kas', [LaporanController::class, 'cetakArusKas'])->name('cetak.arus_kas');
    Route::get('/cetak/pengadaan', [LaporanController::class, 'cetakPengadaan'])->name('cetak.pengadaan');
    Route::get('/cetak/penjualan', [LaporanController::class, 'cetakPenjualan'])->name('cetak.penjualan');
    Route::get('/cetak/persediaan', [LaporanController::class, 'cetakPersediaan'])->name('cetak.persediaan');
    Route::get('/cetak/produk', [LaporanController::class, 'cetakProduk'])->name('cetak.produk');
    Route::get('/cetak/transaksi', [LaporanController::class, 'cetakTransaksi'])->name('cetak.transaksi');
});

// ---------------- PROFILE ------------------ //
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------- AUTH ------------------ //
require __DIR__.'/auth.php';
