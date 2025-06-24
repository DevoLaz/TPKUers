<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaction;
use App\Models\UtangPiutang; // 🔥 PENTING: Import model UtangPiutang
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ===================================================================
        // DATA UTAMA
        // ===================================================================

        // Data Hari Ini
        $pendapatanHariIni = Transaction::where('jenis', 'masuk')->whereDate('tanggal', today())->sum('jumlah');
        $transaksiHariIni = Transaction::whereDate('tanggal', today())->count();

        // Data Bulan Ini vs Bulan Lalu
        $pendapatanBulanIni = Transaction::where('jenis', 'masuk')->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('jumlah');
        $pengeluaranBulanIni = Transaction::where('jenis', 'keluar')->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('jumlah');
        $labaBulanIni = $pendapatanBulanIni - $pengeluaranBulanIni;

        $pendapatanBulanLalu = Transaction::where('jenis', 'masuk')->whereYear('tanggal', now()->subMonth()->year)->whereMonth('tanggal', now()->subMonth()->month)->sum('jumlah');
        $pengeluaranBulanLalu = Transaction::where('jenis', 'keluar')->whereYear('tanggal', now()->subMonth()->year)->whereMonth('tanggal', now()->subMonth()->month)->sum('jumlah');
        $labaBulanLalu = $pendapatanBulanLalu - $pengeluaranBulanLalu;

        // Perhitungan Perubahan Persentase
        $perubahanPendapatan = ($pendapatanBulanLalu > 0) ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100 : ($pendapatanBulanIni > 0 ? 100 : 0);
        $perubahanLaba = ($labaBulanLalu != 0) ? (($labaBulanIni - $labaBulanLalu) / abs($labaBulanLalu)) * 100 : ($labaBulanIni > 0 ? 100 : 0);

        // Data Aset & Kas
        $totalKas = Transaction::where('jenis', 'masuk')->sum('jumlah') - Transaction::where('jenis', 'keluar')->sum('jumlah');
        $totalPersediaan = Barang::sum(DB::raw('harga * stok'));
        $totalAset = $totalKas + $totalPersediaan;

        // 🔥 DATA BARU UNTUK METRIC CARDS
        $totalPiutang = UtangPiutang::where('tipe', 'piutang')->where('status', 'belum_lunas')->sum('jumlah');
        $totalUtang = UtangPiutang::where('tipe', 'utang')->where('status', 'belum_lunas')->sum('jumlah');

        // Data Target (contoh, bisa Anda sesuaikan)
        $targetPendapatan = 20000000; // Misal target 20 Juta
        $progressTarget = ($targetPendapatan > 0) ? ($pendapatanBulanIni / $targetPendapatan) * 100 : 0;

        $stokMenipis = Barang::where('stok', '<=', 10)->orderBy('stok', 'asc')->get();
        
        $produkTerlaris = DB::table('transaksis')
            ->join('barangs', 'transaksis.barang_id', '=', 'barangs.id')
            ->where('transaksis.jenis', 'masuk')->whereYear('transaksis.tanggal', now()->year)->whereMonth('transaksis.tanggal', now()->month)
            ->select('barangs.nama', DB::raw('SUM(transaksis.qty) as total_terjual'), DB::raw('SUM(transaksis.jumlah) as total_pendapatan'))
            ->groupBy('barangs.nama')->orderBy('total_terjual', 'desc')->limit(5)->get();
            
        $penjualanKategori = DB::table('transaksis')
            ->join('barangs', 'transaksis.barang_id', '=', 'barangs.id')
            ->where('transaksis.jenis', 'masuk')->whereYear('transaksis.tanggal', now()->year)->whereMonth('transaksis.tanggal', now()->month)
            ->select('barangs.kategori', DB::raw('SUM(transaksis.jumlah) as total_penjualan'), DB::raw('COUNT(transaksis.id) as jumlah_transaksi'))
            ->groupBy('barangs.kategori')->orderBy('total_penjualan', 'desc')->get();
        
        $totalBarang = Barang::count();
        $totalKategori = Barang::distinct('kategori')->count('kategori');
        $totalTransaksi = Transaction::count();
        $rataRataPenjualanHarian = $totalTransaksi > 0 ? (Transaction::where('jenis', 'masuk')->sum('jumlah') / $totalTransaksi) : 0;
        
        $transaksiTerbaru = Transaction::leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')
            ->select('transaksis.*', 'barangs.nama as nama_barang')->latest('tanggal')->limit(5)->get();
            
        $hariDalamBulan = now()->daysInMonth;
        $hariBerlalu = now()->day;
        $proyeksiAkhirBulan = $hariBerlalu > 0 ? ($pendapatanBulanIni / $hariBerlalu) * $hariDalamBulan : 0;

        // ===================================================================
        // DATA UNTUK GRAFIK (CHARTS)
        // ===================================================================

        $tanggal7Hari = [];
        $penjualan7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $tanggal7Hari[] = $tanggal->format('d/m');
            $penjualan7Hari[] = Transaction::where('jenis', 'masuk')->whereDate('tanggal', $tanggal)->sum('jumlah');
        }
        
        $dataBulanan = [];
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatan = Transaction::where('jenis', 'masuk')->whereYear('tanggal', now()->year)->whereMonth('tanggal', $i)->sum('jumlah');
            $pengeluaran = Transaction::where('jenis', 'keluar')->whereYear('tanggal', now()->year)->whereMonth('tanggal', $i)->sum('jumlah');
            $dataBulanan[] = ['bulan' => $namaBulan[$i-1], 'pendapatan' => $pendapatan, 'pengeluaran' => $pengeluaran, 'laba' => $pendapatan - $pengeluaran,];
        }

        // 🔥 FIXED: Menambahkan key 'icon' dan 'url' ke setiap alert
        $alerts = [];
        if (!$stokMenipis->isEmpty()) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'message' => 'Ada ' . $stokMenipis->count() . ' produk dengan stok menipis!',
                'action' => 'Periksa Stok',
                'url' => route('laporan.persediaan')
            ];
        }

        if ($perubahanLaba > 20) {
             $alerts[] = [
                'type' => 'success',
                'icon' => 'party-popper',
                'message' => 'Laba meningkat ' . number_format($perubahanLaba, 1) . '% dari bulan lalu! 🎉',
                'action' => 'Lihat Detail',
                'url' => route('laporan.laba_rugi')
            ];
        }
        
        return view('dashboard', compact(
            'pendapatanHariIni', 'transaksiHariIni', 'totalKas', 'pendapatanBulanIni',
            'pendapatanBulanLalu', 'perubahanPendapatan', 'labaBulanIni', 'labaBulanLalu',
            'perubahanLaba', 'totalAset', 'totalPersediaan', 'targetPendapatan',
            'progressTarget', 'tanggal7Hari', 'penjualan7Hari', 'dataBulanan',
            'produkTerlaris', 'stokMenipis', 'penjualanKategori', 'totalBarang',
            'totalKategori', 'totalTransaksi', 'rataRataPenjualanHarian',
            'transaksiTerbaru', 'proyeksiAkhirBulan', 'alerts',
            'totalPiutang', 'totalUtang' // 🔥 PENTING: Variabel baru dikirim ke view
        ));
    }
}
