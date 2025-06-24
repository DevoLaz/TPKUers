<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengadaanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kita mendaftarkan semua rute untuk aplikasi.
| Struktur ini menggabungkan semua kebutuhan route Anda.
|
*/

// ===================================================================
// RUTE UTAMA & DASHBOARD
// ===================================================================

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ===================================================================
// RUTE MANAJEMEN DATA (CRUD)
// ===================================================================

Route::middleware('auth')->group(function () {
    // Route resource untuk CRUD
    Route::resource('barang', BarangController::class);
    Route::resource('transaksi', TransactionController::class);
    Route::resource('karyawan', KaryawanController::class);
});

// ===================================================================
// RUTE PENGADAAN (DEDICATED)
// ===================================================================

Route::prefix('pengadaan')->name('pengadaan.')->middleware('auth')->group(function () {
    Route::get('/', [PengadaanController::class, 'index'])->name('index');
    Route::get('/create', [PengadaanController::class, 'create'])->name('create');
    Route::post('/', [PengadaanController::class, 'store'])->name('store');
    Route::get('/riwayat', [PengadaanController::class, 'riwayat'])->name('riwayat');
    Route::get('/{id}/edit', [PengadaanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PengadaanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PengadaanController::class, 'destroy'])->name('destroy');
    
    // AJAX routes (optional untuk future features)
    Route::get('/ajax/barang', [PengadaanController::class, 'getBarang'])->name('ajax.barang');
});

// ===================================================================
// RUTE SEMUA LAPORAN
// ===================================================================

Route::middleware('auth')->prefix('laporan')->name('laporan.')->group(function () {
    
    // Halaman utama laporan -> mengarah ke Riwayat Transaksi
    Route::get('/', [LaporanController::class, 'index'])->name('index'); 

    // --- Rute Laporan Utama & Inputnya ---
    Route::get('/laba-rugi', [LaporanController::class, 'laba_rugi'])->name('laba_rugi');
    
    Route::get('/utang-piutang', [LaporanController::class, 'utangPiutang'])->name('utang_piutang');
    Route::get('/utang-piutang/create', [LaporanController::class, 'createUtangPiutang'])->name('utang_piutang.create');
    Route::post('/utang-piutang', [LaporanController::class, 'storeUtangPiutang'])->name('utang_piutang.store');

    Route::get('/penggajian', [LaporanController::class, 'penggajian'])->name('penggajian');
    Route::get('/penggajian/create', [LaporanController::class, 'createPenggajian'])->name('penggajian.create');
    Route::post('/penggajian', [LaporanController::class, 'storePenggajian'])->name('penggajian.store');

    Route::get('/perpajakan', [LaporanController::class, 'perpajakan'])->name('perpajakan');
    Route::get('/perpajakan/create', [LaporanController::class, 'createPerpajakan'])->name('perpajakan.create');
    Route::post('/perpajakan', [LaporanController::class, 'storePerpajakan'])->name('perpajakan.store');

    // --- Rute Laporan Analisis & Detail ---
    Route::get('/neraca', [LaporanController::class, 'neraca'])->name('neraca');
    Route::get('/arus-kas', [LaporanController::class, 'arus_kas'])->name('arus_kas');
    Route::get('/persediaan', [LaporanController::class, 'persediaan'])->name('persediaan');
    Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
    Route::get('/produk', [LaporanController::class, 'produk'])->name('produk');
    Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
    
    // FIXED: Laporan pengadaan ngarah ke PengadaanController, bukan BarangController
    Route::get('/pengadaan', [PengadaanController::class, 'riwayat'])->name('pengadaan');
    
    // --- Rute untuk melihat & mencetak Slip Gaji ---
    Route::get('/penggajian/{gaji}/slip', [LaporanController::class, 'showSlipGaji'])->name('slip_gaji');
    
    // --- Rute untuk mencetak PDF ---
    Route::prefix('cetak')->name('cetak.')->group(function () {
        Route::get('/laba-rugi', [LaporanController::class, 'cetakLabaRugi'])->name('laba_rugi');
        Route::get('/neraca', [LaporanController::class, 'cetakNeraca'])->name('neraca');
        Route::get('/arus-kas', [LaporanController::class, 'cetakArusKas'])->name('arus_kas');
        Route::get('/transaksi', [LaporanController::class, 'cetakTransaksi'])->name('transaksi');
        Route::get('/penjualan', [LaporanController::class, 'cetakPenjualan'])->name('penjualan');
        Route::get('/persediaan', [LaporanController::class, 'cetakPersediaan'])->name('persediaan');
        Route::get('/produk', [LaporanController::class, 'cetakProduk'])->name('produk');
        Route::get('/utang-piutang', [LaporanController::class, 'cetakUtangPiutang'])->name('utang_piutang');
        Route::get('/penggajian', [LaporanController::class, 'cetakPenggajian'])->name('penggajian');
        Route::get('/perpajakan', [LaporanController::class, 'cetakPerpajakan'])->name('perpajakan');
        Route::get('/penggajian/{gaji}/slip', [LaporanController::class, 'cetakSlipGaji'])->name('slip_gaji');
        
        // FIXED: Cetak pengadaan ngarah ke PengadaanController
        Route::get('/pengadaan', [PengadaanController::class, 'cetakPengadaan'])->name('pengadaan');
    });
});

// ===================================================================
// RUTE PROFILE PENGGUNA
// ===================================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================================================================
// RUTE AUTENTIKASI (LOGIN, REGISTER, DLL.)
// ===================================================================

require __DIR__.'/auth.php';

// ===================================================================
// RUTE PENGAMAN & REDIRECTS
// ===================================================================

// Redirect legacy routes
Route::get('/riwayat-redirect', function() {
    return redirect()->route('laporan.index');
})->middleware('auth')->name('riwayat');

// Redirect old pengadaan routes (jika ada)
Route::get('/kelola-barang', function() {
    return redirect()->route('barang.index');
})->middleware('auth');

// Catch-all untuk route yang tidak ditemukan (optional)
Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan');
});