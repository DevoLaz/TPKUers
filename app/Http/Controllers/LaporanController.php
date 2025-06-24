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
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * ===================================================================
     * METHOD UTAMA UNTUK MENU LAPORAN
     * ===================================================================
     */

    public function index(Request $request)
    {
        $periode = $request->input('periode', 'harian');
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $query = DB::table('transaksis')->leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')->select('transaksis.*', 'barangs.nama as nama_barang', DB::raw("DATE(transaksis.tanggal) as tgl"));
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
                return ['tanggal' => $group->first()->tgl, 'total_masuk' => $group->where('jenis', 'masuk')->sum('jumlah'), 'total_keluar' => $group->where('jenis', 'keluar')->sum('jumlah'), 'jumlah_transaksi' => $group->count()];
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

    public function penggajian()
    {
        $penggajian = Gaji::with('karyawan')->latest('periode')->get();
        $totalGajiKotor = $penggajian->sum('total_pendapatan');
        $totalPotongan = $penggajian->sum('total_potongan');
        $totalGajiBersih = $penggajian->sum('gaji_bersih');
        return view('laporan_riwayat.penggajian', compact('penggajian', 'totalGajiKotor', 'totalPotongan', 'totalGajiBersih'));
    }

    public function createPenggajian()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('laporan_riwayat.penggajian.create', compact('karyawan'));
    }

    public function storePenggajian(Request $request)
    {
        $request->validate(['karyawan_id' => 'required|exists:karyawans,id', 'periode' => 'required|date_format:Y-m', 'gaji_pokok' => 'required|numeric|min:0', 'tunjangan_jabatan' => 'nullable|numeric|min:0', 'tunjangan_transport' => 'nullable|numeric|min:0', 'bonus' => 'nullable|numeric|min:0', 'pph21' => 'nullable|numeric|min:0', 'bpjs' => 'nullable|numeric|min:0', 'potongan_lain' => 'nullable|numeric|min:0',]);
        $totalPendapatan = ($request->gaji_pokok ?? 0) + ($request->tunjangan_jabatan ?? 0) + ($request->tunjangan_transport ?? 0) + ($request->bonus ?? 0);
        $totalPotongan = ($request->pph21 ?? 0) + ($request->bpjs ?? 0) + ($request->potongan_lain ?? 0);
        $gajiBersih = $totalPendapatan - $totalPotongan;
        Gaji::create(['karyawan_id' => $request->karyawan_id, 'periode' => $request->periode . '-01', 'gaji_pokok' => $request->gaji_pokok ?? 0, 'tunjangan_jabatan' => $request->tunjangan_jabatan ?? 0, 'tunjangan_transport' => $request->tunjangan_transport ?? 0, 'bonus' => $request->bonus ?? 0, 'pph21' => $request->pph21 ?? 0, 'bpjs' => $request->bpjs ?? 0, 'potongan_lain' => $request->potongan_lain ?? 0, 'total_pendapatan' => $totalPendapatan, 'total_potongan' => $totalPotongan, 'gaji_bersih' => $gajiBersih,]);
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
        $summaryQuery = Pajak::query();
        if ($request->filled('periode')) {
            $periode = Carbon::parse($request->periode);
            $query->whereYear('tanggal_transaksi', $periode->year)->whereMonth('tanggal_transaksi', $periode->month);
            $summaryQuery->whereYear('tanggal_transaksi', $periode->year)->whereMonth('tanggal_transaksi', $periode->month);
        }
        $pajaks = $query->latest('tanggal_transaksi')->get();
        $totalPphTerutang = (clone $summaryQuery)->where('jenis_pajak', 'like', 'PPh%')->where('status', 'belum_dibayar')->sum('jumlah_pajak');
        $ppnMasukan = (clone $summaryQuery)->where('jenis_pajak', 'PPN Masukan')->sum('jumlah_pajak');
        $ppnKeluaran = (clone $summaryQuery)->where('jenis_pajak', 'PPN Keluaran')->sum('jumlah_pajak');
        $totalPpnDisetor = $ppnKeluaran - $ppnMasukan;
        $totalPajakDisetor = (clone $summaryQuery)->where('status', 'sudah_dibayar')->sum('jumlah_pajak');
        return view('laporan_riwayat.perpajakan', compact('pajaks', 'totalPphTerutang', 'totalPpnDisetor', 'totalPajakDisetor'));
    }

    public function createPerpajakan()
    {
        return view('laporan_riwayat.perpajakan.create');
    }

    public function storePerpajakan(Request $request)
    {
        $request->validate(['jenis_pajak' => 'required|string', 'dasar_pengenaan_pajak' => 'required|numeric|min:0', 'tarif_pajak' => 'required|numeric|min:0', 'tanggal_transaksi' => 'required|date',]);
        $dpp = $request->dasar_pengenaan_pajak;
        $tarif = $request->tarif_pajak;
        $jumlah_pajak = ($dpp * $tarif) / 100;
        Pajak::create(['jenis_pajak' => $request->jenis_pajak, 'no_referensi' => $request->no_referensi, 'tanggal_transaksi' => $request->tanggal_transaksi, 'dasar_pengenaan_pajak' => $dpp, 'tarif_pajak' => $tarif, 'jumlah_pajak' => $jumlah_pajak, 'keterangan' => $request->keterangan,]);
        return redirect()->route('laporan.perpajakan')->with('success', 'Data pajak berhasil dicatat!');
    }

    public function neraca(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $totalMasuk = DB::table('transaksis')->where('jenis', 'masuk')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $totalKeluar = DB::table('transaksis')->where('jenis', 'keluar')->whereDate('tanggal', '<=', $tanggal)->sum('jumlah');
        $kas = $totalMasuk - $totalKeluar;
        $persediaan = DB::table('barangs')->sum(DB::raw('harga * stok'));
        $totalAset = $kas + $persediaan;
        $modal = $totalAset;
        $data = ['kas' => $kas, 'bank' => 0, 'piutang' => 0, 'persediaan' => $persediaan, 'total_aset_lancar' => $kas + $persediaan, 'tanah' => 0, 'bangunan' => 0, 'kendaraan' => 0, 'peralatan' => 0, 'akm_penyusutan' => 0, 'total_aset_tetap' => 0, 'total_aset' => $totalAset, 'utang_usaha' => 0, 'utang_gaji' => 0, 'utang_pajak' => 0, 'total_kewajiban_lancar' => 0, 'utang_bank' => 0, 'total_kewajiban_panjang' => 0, 'total_kewajiban' => 0, 'modal' => $modal, 'laba_ditahan' => 0, 'total_ekuitas' => $modal, 'total_kewajiban_ekuitas' => $modal];
        return view('laporan_riwayat.neraca', compact('data', 'tanggal'));
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
        if ($bulan) {
            $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
        } else {
            $query->whereYear('tanggal', $tahun);
        }
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
        return view('laporan_riwayat.aruskas', compact('operasionalMasuk', 'operasionalKeluar', 'investasi', 'pendanaan', 'saldoAwal', 'saldoAkhir', 'totalKasMasuk', 'totalKasKeluar', 'jumlahTransaksiMasuk', 'jumlahTransaksiKeluar', 'tahun', 'bulan', 'daftarTahun'));
    }

    public function pengadaan(Request $request)
    {
        $query = Barang::query();
        if ($request->nama) $query->where('nama', 'like', '%' . $request->nama . '%');
        if ($request->kategori) $query->where('kategori', $request->kategori);
        if ($request->tanggal_dari && $request->tanggal_sampai) { $query->whereBetween('created_at', [$request->tanggal_dari, $request->tanggal_sampai]); }
        $barangs = $query->orderBy('created_at', 'desc')->get();
        $totalBarang = $barangs->count();
        $totalNilai = $barangs->sum(fn($barang) => $barang->harga * $barang->stok);
        $totalStok = $barangs->sum('stok');
        return view('laporan_riwayat.barang', compact('barangs', 'totalBarang', 'totalNilai', 'totalStok'));
    }

    public function penjualan(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $barang_id = $request->input('barang_id');
        $query = DB::table('transaksis')->leftJoin('barangs', 'transaksis.barang_id', '=', 'barangs.id')->where('transaksis.jenis', 'masuk')->where('transaksis.kategori', 'penjualan')->select('transaksis.*', 'barangs.nama as nama_barang', 'barangs.kategori as kategori_barang', 'barangs.harga as harga_jual');
        if ($tahun) $query->whereYear('transaksis.tanggal', $tahun);
        if ($bulan) $query->whereMonth('transaksis.tanggal', $bulan);
        if ($barang_id) $query->where('transaksis.barang_id', $barang_id);
        $penjualan = $query->orderBy('transaksis.tanggal', 'desc')->get();
        $totalPenjualan = $penjualan->sum('jumlah');
        $totalItem = $penjualan->sum('qty');
        $totalTransaksi = $penjualan->count();
        $totalDiskon = $totalPenjualan * 0.05;
        $totalPajak = ($totalPenjualan - $totalDiskon) * 0.11;
        $totalBersih = $totalPenjualan - $totalDiskon + $totalPajak;
        $daftarTahun = DB::table('transaksis')->where('jenis', 'masuk')->where('kategori', 'penjualan')->selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $daftarBarang = DB::table('barangs')->pluck('nama', 'id');
        return view('laporan_riwayat.penjualan', compact('penjualan', 'totalPenjualan', 'totalItem', 'totalTransaksi', 'totalDiskon', 'totalPajak', 'totalBersih', 'tahun', 'bulan', 'barang_id', 'daftarTahun', 'daftarBarang'));
    }

    public function persediaan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $kategori = $request->input('kategori');
        $query = Barang::query();
        if ($kategori) $query->where('kategori', $kategori);
        $barangs = $query->get();
        foreach ($barangs as $barang) {
            $barang->stok_awal = $barang->stok;
            $queryMasuk = DB::table('transaksis')->where('barang_id', $barang->id)->where('jenis', 'keluar')->where('kategori', 'pembelian');
            if ($tahun) $queryMasuk->whereYear('tanggal', $tahun);
            if ($bulan) $queryMasuk->whereMonth('tanggal', $bulan);
            $barang->stok_masuk = $queryMasuk->sum('qty') ?? 0;
            $queryKeluar = DB::table('transaksis')->where('barang_id', $barang->id)->where('jenis', 'masuk')->where('kategori', 'penjualan');
            if ($tahun) $queryKeluar->whereYear('tanggal', $tahun);
            if ($bulan) $queryKeluar->whereMonth('tanggal', $bulan);
            $barang->stok_keluar = $queryKeluar->sum('qty') ?? 0;
            $barang->stok_akhir = $barang->stok_awal + $barang->stok_masuk - $barang->stok_keluar;
            $barang->nilai_persediaan = $barang->stok_akhir * $barang->harga;
        }
        $totalNilai = $barangs->sum('nilai_persediaan');
        $totalStok = $barangs->sum('stok_akhir');
        $daftarTahun = DB::table('transaksis')->selectRaw('YEAR(tanggal) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
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
            $barang->harga_beli_rata = $qtyPembelian > 0 ? $totalPembelian / $qtyPembelian : $barang->harga;
            $barang->harga_jual_rata = $qtyPenjualan > 0 ? $totalPenjualan / $qtyPenjualan : 0;
            $barang->profit_per_unit = $barang->harga_jual_rata - $barang->harga_beli_rata;
            $barang->total_profit = $totalPenjualan - ($barang->harga_beli_rata * $qtyPenjualan);
            $barang->margin = $barang->harga_beli_rata > 0 ? (($barang->harga_jual_rata - $barang->harga_beli_rata) / $barang->harga_beli_rata) * 100 : 0;
            $barang->status = $barang->total_profit >= 0 ? 'profit' : 'loss';
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
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $tanggal = $request->input('tanggal');
        $queryPendapatan = DB::table('transaksis')->where('jenis', 'masuk');
        $queryPengeluaran = DB::table('transaksis')->where('jenis', 'keluar');
        if ($tahun) { $queryPendapatan->whereYear('tanggal', $tahun); $queryPengeluaran->whereYear('tanggal', $tahun); }
        if ($bulan) { $queryPendapatan->whereMonth('tanggal', $bulan); $queryPengeluaran->whereMonth('tanggal', $bulan); }
        if ($tanggal) { $queryPendapatan->whereDay('tanggal', $tanggal); $queryPengeluaran->whereDay('tanggal', $tanggal); }
        $pendapatan = $queryPendapatan->get();
        $pengeluaran = $queryPengeluaran->get();
        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $periode = 'Semua Data';
        if ($tahun || $bulan || $tanggal) {
            $periode = '';
            if ($tahun) $periode .= $tahun;
            if ($bulan) { $namaBulan = [ 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember' ]; $periode .= ' - ' . ($namaBulan[$bulan] ?? ''); }
            if ($tanggal) $periode .= ' - ' . $tanggal;
        }
        $pdf = Pdf::loadView('laporan_riwayat.labarugi_pdf', compact('pendapatan', 'pengeluaran', 'totalPendapatan', 'totalPengeluaran', 'periode'));
        return $pdf->download('laporan-laba-rugi.pdf');
    }
    
    public function cetakUtangPiutang(Request $request)
    {
        $data = ['piutang' => [], 'utang' => [], 'totalPiutang' => 0, 'totalUtang' => 0, 'periode' => 'Semua Data'];
        $pdf = Pdf::loadView('laporan_riwayat.utang_piutang_pdf', $data);
        return $pdf->download('laporan-utang-piutang.pdf');
    }
    
    public function cetakPenggajian(Request $request)
    {
        $data = ['penggajian' => [], 'periode' => 'Periode Tertentu'];
        $pdf = Pdf::loadView('laporan_riwayat.penggajian_pdf', $data);
        return $pdf->download('laporan-penggajian.pdf');
    }
    
    public function cetakPerpajakan(Request $request)
    {
        $data = ['pajak' => [], 'periode' => 'Periode Tertentu'];
        $pdf = Pdf::loadView('laporan_riwayat.perpajakan_pdf', $data);
        return $pdf->download('laporan-perpajakan.pdf');
    }
    
    public function cetakSlipGaji(Gaji $gaji)
    {
        $gaji->load('karyawan');
        $pdf = Pdf::loadView('laporan_riwayat.penggajian.slip_pdf', compact('gaji'));
        return $pdf->download('slip-gaji-' . $gaji->karyawan->nama_lengkap . '-' . $gaji->periode->format('M-Y') . '.pdf');
    }
    
    public function cetakNeraca() { $pdf = Pdf::loadView('laporan_riwayat.neraca_pdf', ['data' => []]); return $pdf->download('laporan-neraca.pdf'); }
    public function cetakArusKas() { $pdf = Pdf::loadView('laporan_riwayat.aruskas_pdf', ['data' => []]); return $pdf->download('laporan-arus-kas.pdf'); }
    public function cetakPengadaan() { $pdf = Pdf::loadView('laporan_riwayat.barang_pdf', ['data' => []]); return $pdf->download('laporan-pengadaan.pdf'); }
    public function cetakPenjualan() { $pdf = Pdf::loadView('laporan_riwayat.penjualan_pdf', ['data' => []]); return $pdf->download('laporan-penjualan.pdf'); }
    public function cetakPersediaan() { $pdf = Pdf::loadView('laporan_riwayat.persediaan_pdf', ['data' => []]); return $pdf->download('laporan-persediaan.pdf'); }
    public function cetakProduk() { $pdf = Pdf::loadView('laporan_riwayat.produk_pdf', ['data' => []]); return $pdf->download('laporan-produk.pdf'); }
    public function cetakTransaksi() { $pdf = Pdf::loadView('laporan_riwayat.transaksi_pdf', ['data' => []]); return $pdf->download('laporan-transaksi.pdf'); }
}
