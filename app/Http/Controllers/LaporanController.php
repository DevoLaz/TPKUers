<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaction;
use App\Models\UtangPiutang;
use App\Models\Karyawan;
use App\Models\Gaji;
use App\Models\Pajak;
use App\Models\AsetTetap;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // ... Method index(), laba_rugi(), dll tetap sama ...
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'harian');
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        
        $query = DB::table('transaksis')
            ->leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')
            ->select('transaksis.*', 'barangs.nama as nama_barang', DB::raw("DATE(transaksis.tanggal) as tgl"));

        if ($periode == 'harian') {
            $query->whereDate('transaksis.tanggal', $tanggal);
            $judulPeriode = 'Tanggal ' . Carbon::parse($tanggal)->format('d F Y');
        } else {
            $query->whereMonth('transaksis.tanggal', $bulan)->whereYear('transaksis.tanggal', $tahun);
            $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            $judulPeriode = 'Bulan ' . ($namaBulan[$bulan] ?? '') . ' ' . $tahun;
        }
        
        $transaksi = $query->orderBy('transaksis.tanggal', 'desc')->orderBy('transaksis.created_at', 'desc')->get();
        $totalMasuk = $transaksi->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;
        
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
        
        $daftarTahun = DB::table('transaksis')->selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        return view('laporan_riwayat.transaksi', compact('transaksi', 'totalMasuk', 'totalKeluar', 'saldo', 'periode', 'tanggal', 'bulan', 'tahun', 'judulPeriode', 'daftarTahun', 'rekapHarian'));
    }

    public function laba_rugi(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');

        $queryPendapatan = DB::table('transaksis')->where('jenis', 'masuk');
        $queryPengeluaran = DB::table('transaksis')->where('jenis', 'keluar');

        if ($tahun) { $queryPendapatan->whereYear('tanggal', $tahun); $queryPengeluaran->whereYear('tanggal', $tahun); }
        if ($bulan) { $queryPendapatan->whereMonth('tanggal', $bulan); $queryPengeluaran->whereMonth('tanggal', $bulan); }
        if ($tanggal) { $queryPendapatan->whereDay('tanggal', $tanggal); $queryPengeluaran->whereDay('tanggal', $tanggal); }
        
        $pendapatan = $queryPendapatan->orderBy('tanggal', 'desc')->get();
        $pengeluaran = $queryPengeluaran->orderBy('tanggal', 'desc')->get();
        
        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        
        $daftarTahun = DB::table('transaksis')->selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $daftarBulan = range(1, 12);
        
        $daftarTanggal = [];
        if ($tahun && $bulan) { $daftarTanggal = range(1, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun)); }
        
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        
        return view('laporan_riwayat.labarugi', compact('pendapatan', 'pengeluaran', 'totalPendapatan', 'totalPengeluaran', 'tahun', 'bulan', 'tanggal', 'daftarTahun', 'daftarBulan', 'daftarTanggal', 'namaBulan'));
    }

    public function utangPiutang(Request $request)
    {
        $query = UtangPiutang::query();
        if ($request->filled('start_date')) { $query->whereDate('tanggal', '>=', $request->start_date); }
        if ($request->filled('end_date')) { $query->whereDate('tanggal', '<=', $request->end_date); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        
        $piutang = (clone $query)->where('tipe', 'piutang')->latest('tanggal')->get();
        $utang = (clone $query)->where('tipe', 'utang')->latest('tanggal')->get();
        
        $totalPiutang = UtangPiutang::where('tipe', 'piutang')->sum('jumlah');
        $totalUtang = UtangPiutang::where('tipe', 'utang')->sum('jumlah');
        $sisaPiutang = UtangPiutang::where('tipe', 'piutang')->where('status', 'belum_lunas')->sum('jumlah');
        
        return view('laporan_riwayat.utang_piutang', compact('piutang', 'utang', 'totalPiutang', 'totalUtang', 'sisaPiutang'));
    }

    public function createUtangPiutang()
    {
        return view('laporan_riwayat.utang_piutang.create');
    }

    public function storeUtangPiutang(Request $request)
    {
        $request->validate(['jenis_transaksi' => 'required|in:utang,piutang', 'pihak_terkait' => 'required|string|max:255', 'akun' => 'required|string', 'jumlah' => 'required|numeric|min:1', 'no_referensi' => 'nullable|string|max:255', 'tanggal_transaksi' => 'required|date', 'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_transaksi', 'keterangan' => 'nullable|string',]);
        UtangPiutang::create(['tipe' => $request->jenis_transaksi, 'nama_kontak' => $request->pihak_terkait, 'akun' => $request->akun, 'jumlah' => $request->jumlah, 'no_invoice' => $request->no_referensi, 'tanggal' => $request->tanggal_transaksi, 'jatuh_tempo' => $request->tanggal_jatuh_tempo, 'keterangan' => $request->keterangan, 'status' => 'belum_lunas']);
        return redirect()->route('laporan.utang_piutang')->with('success', 'Data ' . ucfirst($request->jenis_transaksi) . ' berhasil disimpan!');
    }

    public function neraca(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $totalMasuk = Transaction::where('jenis', 'masuk')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $totalKeluar = Transaction::where('jenis', 'keluar')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $totalKas = $totalMasuk - $totalKeluar;
        $totalPiutang = UtangPiutang::where('tipe', 'piutang')->where('status', 'belum_lunas')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $totalPersediaan = Barang::all()->sum(function ($barang) { return ($barang->harga_beli ?? $barang->harga ?? 0) * $barang->stok; });
        $totalAsetLancar = $totalKas + $totalPiutang + $totalPersediaan;
        $asetTetaps = AsetTetap::whereDate('tanggal_perolehan', '<=', $tanggal)->get();
        $totalHargaPerolehan = $asetTetaps->sum('harga_perolehan');
        $totalAkumulasiPenyusutan = $asetTetaps->sum(function($aset) { return $aset->akumulasi_penyusutan; });
        $totalNilaiBukuAsetTetap = $totalHargaPerolehan - $totalAkumulasiPenyusutan;
        $totalAset = $totalAsetLancar + $totalNilaiBukuAsetTetap;
        $totalUtang = UtangPiutang::where('tipe', 'utang')->where('status', 'belum_lunas')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $totalLiabilitas = $totalUtang;
        $totalEkuitas = $totalAset - $totalLiabilitas;
        return view('laporan_riwayat.neraca', compact('tanggal', 'totalAset', 'totalAsetLancar', 'totalNilaiBukuAsetTetap', 'totalKas', 'totalPiutang', 'totalPersediaan', 'totalHargaPerolehan', 'totalAkumulasiPenyusutan', 'totalLiabilitas', 'totalUtang', 'totalEkuitas'));
    }

    public function penggajian()
    {
        $penggajian = Gaji::with('karyawan')->latest('periode')->get();
        return view('laporan_riwayat.penggajian', ['penggajian' => $penggajian]);
    }

    public function createPenggajian()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('laporan_riwayat.penggajian.create', compact('karyawan'));
    }

    public function storePenggajian(Request $request)
    {
        $validated = $request->validate(['karyawan_id' => 'required|exists:karyawans,id', 'periode' => 'required|date_format:Y-m', 'gaji_pokok' => 'required|numeric|min:0', 'tunjangan_jabatan' => 'nullable|numeric|min:0', 'tunjangan_transport' => 'nullable|numeric|min:0', 'bonus' => 'nullable|numeric|min:0', 'pph21' => 'nullable|numeric|min:0', 'bpjs' => 'nullable|numeric|min:0', 'potongan_lain' => 'nullable|numeric|min:0',]);
        $totalPendapatan = ($validated['gaji_pokok'] ?? 0) + ($validated['tunjangan_jabatan'] ?? 0) + ($validated['tunjangan_transport'] ?? 0) + ($validated['bonus'] ?? 0);
        $totalPotongan = ($validated['pph21'] ?? 0) + ($validated['bpjs'] ?? 0) + ($validated['potongan_lain'] ?? 0);
        $gajiBersih = $totalPendapatan - $totalPotongan;
        Gaji::create(array_merge($validated, ['periode' => $validated['periode'] . '-01', 'total_pendapatan' => $totalPendapatan, 'total_potongan' => $totalPotongan, 'gaji_bersih' => $gajiBersih]));
        return redirect()->route('laporan.penggajian')->with('success', 'Data gaji berhasil diproses dan disimpan!');
    }
    
    public function showSlipGaji(Gaji $gaji)
    {
        $gaji->load('karyawan');
        return view('laporan_riwayat.penggajian.slip', compact('gaji'));
    }

    public function perpajakan(Request $request)
    {
        $query = Pajak::query();
        if ($request->filled('periode')) {
            $periode = Carbon::parse($request->periode);
            $query->whereYear('tanggal_transaksi', $periode->year)->whereMonth('tanggal_transaksi', $periode->month);
        }
        $pajaks = $query->latest('tanggal_transaksi')->get();
        $totalPphTerutang = Pajak::where('jenis_pajak', 'like', 'PPh%')->where('status', 'belum_dibayar')->sum('jumlah_pajak');
        $ppnMasukan = Pajak::where('jenis_pajak', 'PPN Masukan')->sum('jumlah_pajak');
        $ppnKeluaran = Pajak::where('jenis_pajak', 'PPN Keluaran')->sum('jumlah_pajak');
        $totalPpnDisetor = $ppnKeluaran - $ppnMasukan;
        $totalPajakDisetor = Pajak::where('status', 'sudah_dibayar')->sum('jumlah_pajak');
        return view('laporan_riwayat.perpajakan', compact('pajaks', 'totalPphTerutang', 'totalPpnDisetor', 'totalPajakDisetor'));
    }

    public function createPerpajakan()
    {
        return view('laporan_riwayat.perpajakan.create');
    }

    public function storePerpajakan(Request $request)
    {
        $validated = $request->validate(['jenis_pajak' => 'required|string', 'dasar_pengenaan_pajak' => 'required|numeric|min:0', 'tarif_pajak' => 'required|numeric|min:0', 'tanggal_transaksi' => 'required|date', 'no_referensi' => 'nullable|string', 'keterangan' => 'nullable|string',]);
        $jumlah_pajak = ($validated['dasar_pengenaan_pajak'] * $validated['tarif_pajak']) / 100;
        Pajak::create(array_merge($validated, ['jumlah_pajak' => $jumlah_pajak]));
        return redirect()->route('laporan.perpajakan')->with('success', 'Data pajak berhasil dicatat!');
    }

    public function arus_kas(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan');
        $startOfPeriod = $bulan ? Carbon::create($tahun, $bulan, 1)->startOfMonth() : Carbon::create($tahun, 1, 1)->startOfYear();
        $masukSebelum = Transaction::where('jenis', 'masuk')->where('tanggal', '<', $startOfPeriod)->sum('jumlah');
        $keluarSebelum = Transaction::where('jenis', 'keluar')->where('tanggal', '<', $startOfPeriod)->sum('jumlah');
        $saldoAwal = $masukSebelum - $keluarSebelum;
        $query = Transaction::query();
        if ($bulan) { $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan); } else { $query->whereYear('tanggal', $tahun); }
        $transactionsInPeriod = $query->orderBy('tanggal')->get();
        $operasionalMasuk = $transactionsInPeriod->where('jenis', 'masuk')->whereIn('kategori', ['penjualan', 'operasional', 'lain-lain']);
        $operasionalKeluar = $transactionsInPeriod->where('jenis', 'keluar')->whereIn('kategori', ['pembelian', 'operasional', 'gaji', 'listrik', 'sewa']);
        $investasi = $transactionsInPeriod->whereIn('kategori', ['pembelian_aset', 'penjualan_aset']);
        $pendanaan = $transactionsInPeriod->whereIn('kategori', ['modal', 'pinjaman', 'pembayaran_pinjaman']);
        $totalKasMasuk = $transactionsInPeriod->where('jenis', 'masuk')->sum('jumlah');
        $totalKasKeluar = $transactionsInPeriod->where('jenis', 'keluar')->sum('jumlah');
        $jumlahTransaksiMasuk = $transactionsInPeriod->where('jenis', 'masuk')->count();
        $jumlahTransaksiKeluar = $transactionsInPeriod->where('jenis', 'keluar')->count();
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;
        $daftarTahun = Transaction::selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $tahunGrafik = $request->input('tahun', now()->year);
        $dataGrafik = [];
        $namaBulanGrafik = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 1; $i <= 12; $i++) {
            $kasMasukBulanan = Transaction::where('jenis', 'masuk')->whereYear('tanggal', $tahunGrafik)->whereMonth('tanggal', $i)->sum('jumlah');
            $kasKeluarBulanan = Transaction::where('jenis', 'keluar')->whereYear('tanggal', $tahunGrafik)->whereMonth('tanggal', $i)->sum('jumlah');
            $dataGrafik[] = ['bulan' => $namaBulanGrafik[$i-1], 'kas_masuk' => $kasMasukBulanan, 'kas_keluar' => $kasKeluarBulanan, 'arus_kas_bersih' => $kasMasukBulanan - $kasKeluarBulanan,];
        }
        $dataGrafikJson = json_encode($dataGrafik);
        return view('laporan_riwayat.aruskas', compact('operasionalMasuk', 'operasionalKeluar', 'investasi', 'pendanaan', 'saldoAwal', 'saldoAkhir', 'totalKasMasuk', 'totalKasKeluar', 'jumlahTransaksiMasuk', 'jumlahTransaksiKeluar', 'tahun', 'bulan', 'daftarTahun', 'dataGrafikJson'));
    }

    public function pengadaan(Request $request)
    {
        $query = Barang::query();
        if ($request->nama) $query->where('nama', 'like', '%' . $request->nama . '%');
        if ($request->kategori) $query->where('kategori', $request->kategori);
        if ($request->tanggal_dari && $request->tanggal_sampai) { $query->whereBetween('created_at', [$request->tanggal_dari, $request->tanggal_sampai]); }
        $barangs = $query->orderBy('created_at', 'desc')->get();
        $totalBarang = $barangs->count();
        $totalNilai = $barangs->sum(fn($barang) => ($barang->harga_beli ?? 0) * $barang->stok);
        $totalStok = $barangs->sum('stok');
        return view('laporan_riwayat.barang', compact('barangs', 'totalBarang', 'totalNilai', 'totalStok'));
    }

    /**
     * ===================================================================
     * 🔥 METHOD PERSEDIAAN YANG TELAH DIPERBAIKI TOTAL 🔥
     * ===================================================================
     */
    public function persediaan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', now()->year); // Selalu set tahun, default ke tahun ini
        $kategori = $request->input('kategori');

        // Ambil master barang berdasarkan filter kategori
        $query = Barang::query();
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        $barangs = $query->get();

        // Tentukan periode laporan
        // Jika bulan tidak dipilih, laporan mencakup satu tahun penuh
        $startPeriod = Carbon::create($tahun, $bulan ?: 1, 1)->startOfMonth();
        $endPeriod = Carbon::create($tahun, $bulan ?: 12, 1)->endOfMonth();

        foreach ($barangs as $barang) {
            // 1. Hitung Stok Awal (semua mutasi SEBELUM startPeriod)
            $masukSebelum = DB::table('pengadaans')
                            ->where('barang_id', $barang->id)
                            ->where('tanggal_pembelian', '<', $startPeriod)
                            ->sum('jumlah_masuk');

            $keluarSebelum = DB::table('transaksis')
                            ->where('barang_id', $barang->id)
                            ->where('jenis', 'masuk') // Penjualan (mengurangi stok)
                            ->where('kategori', 'penjualan')
                            ->where('tanggal', '<', $startPeriod)
                            ->sum('qty');
            $barang->stok_awal = $masukSebelum - $keluarSebelum;
            
            // 2. Hitung Stok Masuk (pembelian SELAMA periode)
            $barang->stok_masuk = DB::table('pengadaans')
                                ->where('barang_id', $barang->id)
                                ->whereBetween('tanggal_pembelian', [$startPeriod, $endPeriod])
                                ->sum('jumlah_masuk');

            // 3. Hitung Stok Keluar (penjualan SELAMA periode)
            $barang->stok_keluar = DB::table('transaksis')
                                ->where('barang_id', $barang->id)
                                ->where('jenis', 'masuk') // Penjualan
                                ->where('kategori', 'penjualan')
                                ->whereBetween('tanggal', [$startPeriod, $endPeriod])
                                ->sum('qty');
            
            // 4. Hitung Stok Akhir
            $barang->stok_akhir = $barang->stok_awal + $barang->stok_masuk - $barang->stok_keluar;

            // 5. 🔥 FIX: Hitung Nilai Persediaan dengan Fallback
            // Gunakan harga_beli jika ada, jika tidak ada, gunakan harga (jual)
            $hargaUntukValuasi = $barang->harga_beli ?? $barang->harga ?? 0;
            $barang->nilai_persediaan = $barang->stok_akhir * $hargaUntukValuasi;
        }

        // Hitung total untuk summary card
        $totalNilai = $barangs->sum('nilai_persediaan');
        $totalStok = $barangs->sum('stok_akhir');
        
        // Data untuk dropdown filter
        $daftarTahun = DB::table('pengadaans')->selectRaw('YEAR(tanggal_pembelian) as tahun')->union(DB::table('transaksis')->selectRaw('YEAR(tanggal) as tahun'))->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $daftarKategori = Barang::distinct()->pluck('kategori');
        
        return view('laporan_riwayat.persediaan', compact('barangs', 'totalNilai', 'totalStok', 'tahun', 'bulan', 'kategori', 'daftarTahun', 'daftarKategori'));
    }

    public function produk(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $kategori = $request->input('kategori');
        
        $query = Barang::query();
        if ($kategori) $query->where('kategori', $kategori);
        
        $barangs = $query->get();

        foreach ($barangs as $barang) {
            $queryPembelian = DB::table('transaksis')->where('barang_id', $barang->id)->where('jenis', 'keluar')->where('kategori', 'pembelian');
            if ($tahun) $queryPembelian->whereYear('tanggal', $tahun);
            if ($bulan) $queryPembelian->whereMonth('tanggal', $bulan);
            $totalPembelian = $queryPembelian->sum('jumlah') ?? 0;
            $qtyPembelian = $queryPembelian->sum('qty') ?? 0;
            
            $queryPenjualan = DB::table('transaksis')->where('barang_id', $barang->id)->where('jenis', 'masuk')->where('kategori', 'penjualan');
            if ($tahun) $queryPenjualan->whereYear('tanggal', $tahun);
            if ($bulan) $queryPenjualan->whereMonth('tanggal', $bulan);
            $totalPenjualan = $queryPenjualan->sum('jumlah') ?? 0;
            $qtyPenjualan = $queryPenjualan->sum('qty') ?? 0;
            
            $barang->total_pembelian = $totalPembelian;
            $barang->total_penjualan = $totalPenjualan;
            $barang->qty_pembelian = $qtyPembelian;
            $barang->qty_penjualan = $qtyPenjualan;
            $barang->harga_beli_rata = $qtyPembelian > 0 ? $totalPembelian / $qtyPembelian : ($barang->harga_beli ?? 0);
            $barang->harga_jual_rata = $qtyPenjualan > 0 ? $totalPenjualan / $qtyPenjualan : ($barang->harga_jual ?? 0);
            $barang->total_profit = $totalPenjualan - ($barang->harga_beli_rata * $qtyPenjualan);
            $barang->margin = $barang->harga_beli_rata > 0 ? (($barang->harga_jual_rata - $barang->harga_beli_rata) / $barang->harga_beli_rata) * 100 : 0;
        }

        $barangs = $barangs->sortByDesc('total_profit');
        $totalModalKeseluruhan = $barangs->sum('total_pembelian');
        $totalPendapatanKeseluruhan = $barangs->sum('total_penjualan');
        $totalProfitKeseluruhan = $barangs->sum('total_profit');
        $marginKeseluruhan = $totalModalKeseluruhan > 0 ? ($totalProfitKeseluruhan / $totalModalKeseluruhan) * 100 : 0;
        
        $daftarTahun = DB::table('transaksis')->selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $daftarKategori = Barang::distinct()->pluck('kategori');
        
        return view('laporan_riwayat.produk', compact('barangs', 'totalModalKeseluruhan', 'totalPendapatanKeseluruhan', 'totalProfitKeseluruhan', 'marginKeseluruhan', 'tahun', 'bulan', 'kategori', 'daftarTahun', 'daftarKategori'));
    }

    public function transaksi(Request $request)
    {
        return redirect()->route('laporan.index', $request->query());
    }

    /**
     * ===================================================================
     * METHOD CETAK PDF
     * ===================================================================
     */

    public function cetakLabaRugi(Request $request)
    {
        // Logika ini sebaiknya sama dengan method laba_rugi() untuk konsistensi
        $pdf = Pdf::loadView('laporan_riwayat.labarugi_pdf', []); // Data perlu di-pass
        return $pdf->download('laporan-laba-rugi.pdf');
    }
    
    public function cetakSlipGaji(Gaji $gaji)
    {
        $gaji->load('karyawan');
        $pdf = Pdf::loadView('laporan_riwayat.penggajian.slip_pdf', compact('gaji'));
        return $pdf->download('slip-gaji-' . $gaji->karyawan->nama_lengkap . '-' . Carbon::parse($gaji->periode)->format('M-Y') . '.pdf');
    }

    // Placeholder untuk method cetak lainnya
    public function cetakNeraca() { $pdf = Pdf::loadView('laporan_riwayat.neraca_pdf', []); return $pdf->download('laporan-neraca.pdf'); }
    // ... method cetak lainnya bisa ditambahkan logikanya di sini
}
