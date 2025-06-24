<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengadaan;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    /**
     * Menampilkan daftar semua barang untuk pengadaan.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // Search by nama atau kode_barang
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        // Urutkan berdasarkan ID terbaru dan paginate
        $barangs = $query->latest('id')->paginate(15);
        
        // Ambil daftar kategori untuk dropdown (yang ada datanya)
        $kategoriList = Barang::distinct()->whereNotNull('kategori')->pluck('kategori')->filter();

        return view('daftarbarang', compact('barangs', 'kategoriList'));
    }

    /**
     * Menampilkan form untuk membuat pengadaan barang baru.
     */
    public function create()
    {
        // Ambil semua barang yang ada untuk dropdown
        $barangs = Barang::orderBy('nama')->get();
        
        // Ambil semua supplier yang ada
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        
        return view('pengadaan.create', compact('barangs', 'suppliers'));
    }

    /**
     * Menyimpan pengadaan barang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'no_invoice' => 'required|string|max:100|unique:pengadaans,no_invoice',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'barang_id.required' => 'Barang wajib dipilih.',
            'barang_id.exists' => 'Barang yang dipilih tidak valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'tanggal_pembelian.required' => 'Tanggal pembelian wajib diisi.',
            'no_invoice.required' => 'Nomor invoice wajib diisi.',
            'no_invoice.unique' => 'Nomor invoice sudah digunakan.',
            'jumlah_masuk.required' => 'Jumlah masuk wajib diisi.',
            'jumlah_masuk.min' => 'Jumlah masuk minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
        ]);

        try {
            // Buat record pengadaan
            $pengadaan = Pengadaan::create([
                'barang_id' => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'no_invoice' => $request->no_invoice,
                'jumlah_masuk' => $request->jumlah_masuk,
                'harga_beli' => $request->harga_beli,
                'total_harga' => $request->total_harga,
                'keterangan' => $request->keterangan,
            ]);

            // Update stok barang
            $barang = Barang::find($request->barang_id);
            $barang->increment('stok', $request->jumlah_masuk);
            
            // Update status barang menjadi tersedia
            $barang->update(['status' => 'Tersedia']);

            return redirect()->route('pengadaan.riwayat')
                             ->with('success', 'Pengadaan barang berhasil ditambahkan dan stok telah diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menyimpan pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pengadaan.
     */
    public function show($id)
    {
        $pengadaan = Pengadaan::with(['barang', 'supplier'])->findOrFail($id);
        return view('pengadaan.show', compact('pengadaan'));
    }

    /**
     * Menampilkan form untuk mengedit pengadaan.
     */
    public function edit($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $barangs = Barang::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        
        return view('pengadaan.edit', compact('pengadaan', 'barangs', 'suppliers'));
    }

    /**
     * Memperbarui data pengadaan.
     */
    public function update(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'no_invoice' => 'required|string|max:100|unique:pengadaans,no_invoice,' . $id,
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            // Jika barang atau jumlah berubah, update stok
            if ($pengadaan->barang_id != $request->barang_id || $pengadaan->jumlah_masuk != $request->jumlah_masuk) {
                // Kurangi stok dari barang lama
                $barangLama = Barang::find($pengadaan->barang_id);
                $barangLama->decrement('stok', $pengadaan->jumlah_masuk);
                
                // Tambah stok ke barang baru
                $barangBaru = Barang::find($request->barang_id);
                $barangBaru->increment('stok', $request->jumlah_masuk);
                
                // Update status
                $barangBaru->update(['status' => 'Tersedia']);
                if ($barangLama->stok <= 0) {
                    $barangLama->update(['status' => 'Habis']);
                }
            }

            $pengadaan->update([
                'barang_id' => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'no_invoice' => $request->no_invoice,
                'jumlah_masuk' => $request->jumlah_masuk,
                'harga_beli' => $request->harga_beli,
                'total_harga' => $request->total_harga,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('pengadaan.riwayat')
                             ->with('success', 'Data pengadaan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal memperbarui pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pengadaan.
     */
    public function destroy($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        
        try {
            // Kurangi stok barang
            $barang = Barang::find($pengadaan->barang_id);
            $barang->decrement('stok', $pengadaan->jumlah_masuk);
            
            // Update status jika stok habis
            if ($barang->stok <= 0) {
                $barang->update(['status' => 'Habis']);
            }
            
            $pengadaan->delete();
            
            return redirect()->route('pengadaan.riwayat')
                             ->with('success', 'Data pengadaan berhasil dihapus dan stok telah disesuaikan!');
        } catch (\Exception $e) {
            return redirect()->route('pengadaan.riwayat')
                             ->with('error', 'Gagal menghapus data pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Get barang data for AJAX requests
     */
    public function getBarang(Request $request)
    {
        if ($request->ajax()) {
            $query = Barang::query();
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            }
            
            if ($request->has('kategori') && $request->kategori != '') {
                $query->where('kategori', $request->kategori);
            }
            
            $barangs = $query->orderBy('nama')
                            ->get(['id', 'kode_barang', 'nama', 'kategori', 'unit', 'harga', 'stok']);
            
            return response()->json($barangs);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Riwayat pengadaan
     */
    public function riwayat(Request $request)
    {
        $query = Pengadaan::with(['barang', 'supplier']);

        // Filter by tanggal (ubah parameter name untuk konsistensi)
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembelian', [$request->dari, $request->sampai]);
        }

        // Filter by barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $pengadaans = $query->latest('tanggal_pembelian')->paginate(10);
        
        // Data untuk filter dropdown
        $barangs = Barang::orderBy('nama')->get(['id', 'nama']);
        $suppliers = Supplier::orderBy('nama_supplier')->get(['id', 'nama_supplier']);

        return view('pengadaan.riwayat', compact('pengadaans', 'barangs', 'suppliers'));
    }

    /**
     * Cetak laporan pengadaan (untuk keperluan laporan)
     */
    public function cetakPengadaan(Request $request)
    {
        $query = Pengadaan::with(['barang', 'supplier']);

        // Apply filters
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembelian', [$request->dari, $request->sampai]);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $pengadaans = $query->latest('tanggal_pembelian')->get();
        
        // Data summary
        $totalPengadaan = $pengadaans->count();
        $totalNilai = $pengadaans->sum('total_harga');
        $totalItem = $pengadaans->sum('jumlah_masuk');

        $data = compact('pengadaans', 'totalPengadaan', 'totalNilai', 'totalItem');

        // Return PDF atau view untuk print
        return view('pengadaan.cetak', $data);
    }
}