<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;


class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan');
    }

    // ========== TAMPIL VIEW ==========

    public function laba_rugi(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');

        // Siapkan builder dasar
        $queryPendapatan = DB::table('transaksis')->where('jenis', 'masuk');
        $queryPengeluaran = DB::table('transaksis')->where('jenis', 'keluar');

        // Filter berdasarkan input
        if ($tahun) {
            $queryPendapatan->whereYear('tanggal', $tahun);
            $queryPengeluaran->whereYear('tanggal', $tahun);
        }

        if ($bulan) {
            $queryPendapatan->whereMonth('tanggal', $bulan);
            $queryPengeluaran->whereMonth('tanggal', $bulan);
        }

        if ($tanggal) {
            $queryPendapatan->whereDay('tanggal', $tanggal);
            $queryPengeluaran->whereDay('tanggal', $tanggal);
        }

        $pendapatan = $queryPendapatan->get();
        $pengeluaran = $queryPengeluaran->get();

        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');

        // Data untuk dropdown
        $daftarTahun = DB::table('transaksis')
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $daftarBulan = $tahun ? range(1, 12) : [];
        $daftarTanggal = ($tahun && $bulan) ? range(1, 31) : [];

        return view('laporan_riwayat.labarugi', compact(
            'pendapatan',
            'pengeluaran',
            'totalPendapatan',
            'totalPengeluaran',
            'tahun',
            'bulan',
            'tanggal',
            'daftarTahun',
            'daftarBulan',
            'daftarTanggal'
        ));
    }

    public function neraca(Request $request)
{
    $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
    
    // ASET LANCAR
    // Kas = Total transaksi masuk - keluar
    $totalMasuk = DB::table('transaksis')
        ->where('jenis', 'masuk')
        ->whereDate('tanggal', '<=', $tanggal)
        ->sum('jumlah');
        
    $totalKeluar = DB::table('transaksis')
        ->where('jenis', 'keluar')
        ->whereDate('tanggal', '<=', $tanggal)
        ->sum('jumlah');
    
    $kas = $totalMasuk - $totalKeluar;
    
    // Persediaan = Total nilai barang
    $persediaan = DB::table('barangs')->sum(DB::raw('harga * stok'));
    
    // Total Aset (untuk TPKU simple aja)
    $totalAset = $kas + $persediaan;
    
    // KEWAJIBAN & EKUITAS
    // Untuk sementara, anggap semua aset = modal (belum ada utang)
    $modal = $totalAset;
    
    $data = [
        // Aset
        'kas' => $kas,
        'bank' => 0,
        'piutang' => 0,
        'persediaan' => $persediaan,
        'total_aset_lancar' => $kas + $persediaan,
        
        // Aset Tetap (kosong dulu)
        'tanah' => 0,
        'bangunan' => 0,
        'kendaraan' => 0,
        'peralatan' => 0,
        'akm_penyusutan' => 0,
        'total_aset_tetap' => 0,
        
        // Total Aset
        'total_aset' => $totalAset,
        
        // Kewajiban (kosong dulu)
        'utang_usaha' => 0,
        'utang_gaji' => 0,
        'utang_pajak' => 0,
        'total_kewajiban_lancar' => 0,
        'utang_bank' => 0,
        'total_kewajiban_panjang' => 0,
        'total_kewajiban' => 0,
        
        // Ekuitas
        'modal' => $modal,
        'laba_ditahan' => 0,
        'total_ekuitas' => $modal,
        
        // Total Kewajiban & Ekuitas
        'total_kewajiban_ekuitas' => $modal
    ];
    
    return view('laporan_riwayat.neraca', compact('data', 'tanggal'));
}

    public function arus_kas()
    {
        $data = [];
        return view('laporan_riwayat.aruskas', compact('data'));
    }

    public function pengadaan(Request $request)
{
    $query = Barang::query();

    // Filter by nama
    if ($request->nama) {
        $query->where('nama', 'like', '%' . $request->nama . '%');
    }

    // Filter by kategori  
    if ($request->kategori) {
        $query->where('kategori', $request->kategori);
    }

    // Filter by tanggal
    if ($request->tanggal_dari && $request->tanggal_sampai) {
        $query->whereBetween('created_at', [$request->tanggal_dari, $request->tanggal_sampai]);
    }

    $barangs = $query->orderBy('created_at', 'desc')->get();
    
    // Hitung total
    $totalBarang = $barangs->count();
    $totalNilai = $barangs->sum(function($barang) {
        return $barang->harga * $barang->stok;
    });
    $totalStok = $barangs->sum('stok');

    return view('laporan_riwayat.barang', compact('barangs', 'totalBarang', 'totalNilai', 'totalStok'));
}

   public function penjualan(Request $request)
{
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');
    $barang_id = $request->input('barang_id');
    
    // Query penjualan dari transaksi
    $query = DB::table('transaksis')
        ->leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')
        ->where('transaksis.jenis', 'masuk')
        ->where('transaksis.kategori', 'penjualan')
        ->select(
            'transaksis.*',
            'barangs.nama as nama_barang',
            'barangs.kategori as kategori_barang',
            'barangs.harga as harga_jual'
        );
    
    // Filter
    if ($tahun) {
        $query->whereYear('transaksis.tanggal', $tahun);
    }
    if ($bulan) {
        $query->whereMonth('transaksis.tanggal', $bulan);
    }
    if ($barang_id) {
        $query->where('transaksis.barang_id', $barang_id);
    }
    
    $penjualan = $query->orderBy('transaksis.tanggal', 'desc')->get();
    
    // Hitung total
    $totalPenjualan = $penjualan->sum('jumlah');
    $totalItem = $penjualan->sum('qty');
    $totalTransaksi = $penjualan->count();
    
    // Untuk dummy data diskon & pajak (nanti bisa disesuaikan)
    $totalDiskon = $totalPenjualan * 0.05; // 5% diskon
    $totalPajak = ($totalPenjualan - $totalDiskon) * 0.11; // 11% PPN
    $totalBersih = $totalPenjualan - $totalDiskon + $totalPajak;
    
    // Data untuk filter
    $daftarTahun = DB::table('transaksis')
        ->where('jenis', 'masuk')
        ->where('kategori', 'penjualan')
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');
        
    $daftarBarang = DB::table('barangs')->pluck('nama', 'id');
    
    return view('laporan_riwayat.penjualan', compact(
        'penjualan',
        'totalPenjualan',
        'totalItem',
        'totalTransaksi',
        'totalDiskon',
        'totalPajak',
        'totalBersih',
        'tahun',
        'bulan',
        'barang_id',
        'daftarTahun',
        'daftarBarang'
    ));
}
   public function persediaan(Request $request)
{
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');
    $kategori = $request->input('kategori');
    
    // Ambil semua barang
    $query = Barang::query();
    
    if ($kategori) {
        $query->where('kategori', $kategori);
    }
    
    $barangs = $query->get();
    
    // Untuk setiap barang, hitung mutasi stoknya
    foreach ($barangs as $barang) {
        // Stok awal (asumsi dari data barang saat ini)
        $barang->stok_awal = $barang->stok;
        
        // Hitung barang masuk (dari transaksi pembelian)
        $queryMasuk = DB::table('transaksis')
            ->where('barang_id', $barang->id)
            ->where('jenis', 'keluar') // keluar uang = masuk barang
            ->where('kategori', 'pembelian');
            
        if ($tahun) {
            $queryMasuk->whereYear('tanggal', $tahun);
        }
        if ($bulan) {
            $queryMasuk->whereMonth('tanggal', $bulan);
        }
        
        $barang->stok_masuk = $queryMasuk->sum('qty') ?? 0;
        
        // Hitung barang keluar (dari transaksi penjualan)
        $queryKeluar = DB::table('transaksis')
            ->where('barang_id', $barang->id)
            ->where('jenis', 'masuk') // masuk uang = keluar barang
            ->where('kategori', 'penjualan');
            
        if ($tahun) {
            $queryKeluar->whereYear('tanggal', $tahun);
        }
        if ($bulan) {
            $queryKeluar->whereMonth('tanggal', $bulan);
        }
        
        $barang->stok_keluar = $queryKeluar->sum('qty') ?? 0;
        
        // Hitung sisa stok
        $barang->stok_akhir = $barang->stok_awal + $barang->stok_masuk - $barang->stok_keluar;
        
        // Nilai persediaan
        $barang->nilai_persediaan = $barang->stok_akhir * $barang->harga;
    }
    
    // Total nilai persediaan
    $totalNilai = $barangs->sum('nilai_persediaan');
    $totalStok = $barangs->sum('stok_akhir');
    
    // Data untuk filter
    $daftarTahun = DB::table('transaksis')
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');
    
    $daftarKategori = Barang::distinct()->pluck('kategori');
    
    return view('laporan_riwayat.persediaan', compact(
        'barangs',
        'totalNilai',
        'totalStok',
        'tahun',
        'bulan',
        'kategori',
        'daftarTahun',
        'daftarKategori'
    ));
}

   public function produk(Request $request)
{
    $tahun = $request->input('tahun');
    $bulan = $request->input('bulan');
    $kategori = $request->input('kategori');
    
    // Ambil semua barang
    $query = Barang::query();
    
    if ($kategori) {
        $query->where('kategori', $kategori);
    }
    
    $barangs = $query->get();
    
    // Untuk setiap barang, hitung profitabilitasnya
    foreach ($barangs as $barang) {
        // Hitung total pembelian (modal)
        $queryPembelian = DB::table('transaksis')
            ->where('barang_id', $barang->id)
            ->where('jenis', 'keluar')
            ->where('kategori', 'pembelian');
            
        if ($tahun) {
            $queryPembelian->whereYear('tanggal', $tahun);
        }
        if ($bulan) {
            $queryPembelian->whereMonth('tanggal', $bulan);
        }
        
        $totalPembelian = $queryPembelian->sum('jumlah') ?? 0;
        $qtyPembelian = $queryPembelian->sum('qty') ?? 0;
        
        // Hitung total penjualan (pendapatan)
        $queryPenjualan = DB::table('transaksis')
            ->where('barang_id', $barang->id)
            ->where('jenis', 'masuk')
            ->where('kategori', 'penjualan');
            
        if ($tahun) {
            $queryPenjualan->whereYear('tanggal', $tahun);
        }
        if ($bulan) {
            $queryPenjualan->whereMonth('tanggal', $bulan);
        }
        
        $totalPenjualan = $queryPenjualan->sum('jumlah') ?? 0;
        $qtyPenjualan = $queryPenjualan->sum('qty') ?? 0;
        
        // Hitung profit/loss
        $barang->total_pembelian = $totalPembelian;
        $barang->total_penjualan = $totalPenjualan;
        $barang->qty_pembelian = $qtyPembelian;
        $barang->qty_penjualan = $qtyPenjualan;
        
        // Harga rata-rata
        $barang->harga_beli_rata = $qtyPembelian > 0 ? $totalPembelian / $qtyPembelian : $barang->harga;
        $barang->harga_jual_rata = $qtyPenjualan > 0 ? $totalPenjualan / $qtyPenjualan : 0;
        
        // Profit per unit
        $barang->profit_per_unit = $barang->harga_jual_rata - $barang->harga_beli_rata;
        
        // Total profit/loss
        $barang->total_profit = $totalPenjualan - ($barang->harga_beli_rata * $qtyPenjualan);
        
        // Margin profit (%)
        $barang->margin = $barang->harga_beli_rata > 0 ? 
            (($barang->harga_jual_rata - $barang->harga_beli_rata) / $barang->harga_beli_rata) * 100 : 0;
        
        // Status
        $barang->status = $barang->total_profit >= 0 ? 'profit' : 'loss';
    }
    
    // Sort by profit (descending)
    $barangs = $barangs->sortByDesc('total_profit');
    
    // Summary
    $totalModalKeseluruhan = $barangs->sum('total_pembelian');
    $totalPendapatanKeseluruhan = $barangs->sum('total_penjualan');
    $totalProfitKeseluruhan = $barangs->sum('total_profit');
    $marginKeseluruhan = $totalModalKeseluruhan > 0 ? 
        ($totalProfitKeseluruhan / $totalModalKeseluruhan) * 100 : 0;
    
    // Data untuk filter
    $daftarTahun = DB::table('transaksis')
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');
        
    $daftarKategori = Barang::distinct()->pluck('kategori');
    
    return view('laporan_riwayat.produk', compact(
        'barangs',
        'totalModalKeseluruhan',
        'totalPendapatanKeseluruhan',
        'totalProfitKeseluruhan',
        'marginKeseluruhan',
        'tahun',
        'bulan',
        'kategori',
        'daftarTahun',
        'daftarKategori'
    ));
}
    public function transaksi(Request $request)
{
    $periode = $request->input('periode', 'harian'); // harian atau bulanan
    $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
    $bulan = $request->input('bulan', now()->month);
    $tahun = $request->input('tahun', now()->year);
    
    $query = DB::table('transaksis')
        ->leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')
        ->select(
            'transaksis.*',
            'barangs.nama as nama_barang',
            DB::raw("DATE(transaksis.tanggal) as tgl")
        );
    
    // Filter berdasarkan periode
    if ($periode == 'harian') {
        $query->whereDate('transaksis.tanggal', $tanggal);
        $judulPeriode = 'Tanggal ' . \Carbon\Carbon::parse($tanggal)->format('d F Y');
    } else {
        $query->whereMonth('transaksis.tanggal', $bulan)
              ->whereYear('transaksis.tanggal', $tahun);
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $judulPeriode = 'Bulan ' . $namaBulan[$bulan] . ' ' . $tahun;
    }
    
    $transaksi = $query->orderBy('transaksis.tanggal', 'desc')
                       ->orderBy('transaksis.created_at', 'desc')
                       ->get();
    
    // Hitung summary
    $totalMasuk = $transaksi->where('jenis', 'masuk')->sum('jumlah');
    $totalKeluar = $transaksi->where('jenis', 'keluar')->sum('jumlah');
    $saldo = $totalMasuk - $totalKeluar;
    
    // Group by tanggal untuk rekap harian
    $rekapHarian = [];
    if ($periode == 'bulanan') {
        $rekapHarian = $transaksi->groupBy('tgl')->map(function($group) {
            return [
                'tanggal' => $group->first()->tgl,
                'total_masuk' => $group->where('jenis', 'masuk')->sum('jumlah'),
                'total_keluar' => $group->where('jenis', 'keluar')->sum('jumlah'),
                'jumlah_transaksi' => $group->count()
            ];
        });
    }
    
    // Data untuk filter
    $daftarTahun = DB::table('transaksis')
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');
    
    return view('laporan_riwayat.transaksi', compact(
        'transaksi',
        'totalMasuk',
        'totalKeluar',
        'saldo',
        'periode',
        'tanggal',
        'bulan',
        'tahun',
        'judulPeriode',
        'daftarTahun',
        'rekapHarian'
    ));
}

    // ========== CETAK PDF ==========

    public function cetakLabaRugi(Request $request)
    {
        // 🔥 FIXED: Copy logic dari method laba_rugi()
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');

        $queryPendapatan = DB::table('transaksis')->where('jenis', 'masuk');
        $queryPengeluaran = DB::table('transaksis')->where('jenis', 'keluar');

        if ($tahun) {
            $queryPendapatan->whereYear('tanggal', $tahun);
            $queryPengeluaran->whereYear('tanggal', $tahun);
        }

        if ($bulan) {
            $queryPendapatan->whereMonth('tanggal', $bulan);
            $queryPengeluaran->whereMonth('tanggal', $bulan);
        }

        if ($tanggal) {
            $queryPendapatan->whereDay('tanggal', $tanggal);
            $queryPengeluaran->whereDay('tanggal', $tanggal);
        }

        $pendapatan = $queryPendapatan->get();
        $pengeluaran = $queryPengeluaran->get();
        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');

        // Format periode untuk ditampilkan di PDF
        $periode = 'Semua Data';
        if ($tahun || $bulan || $tanggal) {
            $periode = '';
            if ($tahun) $periode .= $tahun;
            if ($bulan) {
                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $periode .= ' - ' . $namaBulan[$bulan];
            }
            if ($tanggal) {
                $periode .= ' - ' . $tanggal;
            }
        }

        // 🔥 FIXED: Kirim variabel yang benar ke view PDF
        $pdf = Pdf::loadView('laporan_riwayat.labarugi_pdf', compact(
            'pendapatan',
            'pengeluaran',
            'totalPendapatan',
            'totalPengeluaran',
            'periode'
        ));
        
        return $pdf->download('laporan-laba-rugi.pdf');
    }

    public function cetakNeraca()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.neraca_pdf', compact('data'));
        return $pdf->download('laporan-neraca.pdf');
    }

    public function cetakArusKas()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.aruskas_pdf', compact('data'));
        return $pdf->download('laporan-arus-kas.pdf');
    }

    public function cetakPengadaan()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.barang_pdf', compact('data'));
        return $pdf->download('laporan-pengadaan.pdf');
    }

    public function cetakPenjualan()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.penjualan_pdf', compact('data'));
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function cetakPersediaan()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.persediaan_pdf', compact('data'));
        return $pdf->download('laporan-persediaan.pdf');
    }

    public function cetakProduk()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.produk_pdf', compact('data'));
        return $pdf->download('laporan-produk.pdf');
    }

    public function cetakTransaksi()
    {
        $data = [];
        $pdf = Pdf::loadView('laporan_riwayat.transaksi_pdf', compact('data'));
        return $pdf->download('laporan-transaksi.pdf');
    }
}