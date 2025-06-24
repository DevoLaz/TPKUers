<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar semua barang.
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
        
        // Ambil daftar kategori untuk dropdown
        $kategoriList = Barang::distinct()->pluck('kategori')->filter();

        return view('daftarbarang', compact('barangs', 'kategoriList'));
    }

    /**
     * Menampilkan form untuk membuat barang baru.
     */
    public function create()
    {
        // Ambil kategori yang sudah ada untuk dropdown
        $kategoriList = Barang::distinct()->pluck('kategori')->filter();
        
        return view('barang.create', compact('kategoriList'));
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori barang wajib diisi.',
            'unit.required' => 'Unit barang wajib diisi.',
            'harga.required' => 'Harga barang wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok barang wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
        ]);

        // Auto-generate status berdasarkan stok
        $status = $request->stok > 0 ? 'Tersedia' : 'Habis';

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'unit' => $request->unit,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $status,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail barang.
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit(Barang $barang)
    {
        // Ambil kategori yang sudah ada untuk dropdown
        $kategoriList = Barang::distinct()->pluck('kategori')->filter();
        
        return view('barang.edit', compact('barang', 'kategoriList'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori barang wajib diisi.',
            'unit.required' => 'Unit barang wajib diisi.',
            'harga.required' => 'Harga barang wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok barang wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
        ]);

        // Auto-update status berdasarkan stok
        $status = $request->stok > 0 ? 'Tersedia' : 'Habis';

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'unit' => $request->unit,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $status,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus data barang.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')
                             ->with('success', 'Data barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                             ->with('error', 'Gagal menghapus data barang. Data mungkin sedang digunakan.');
        }
    }

    /**
     * Update stok barang (untuk pengadaan)
     */
    public function updateStok(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'type' => 'required|in:tambah,kurang'
        ]);

        $stokBaru = $barang->stok;
        
        if ($request->type === 'tambah') {
            $stokBaru += $request->jumlah;
        } else {
            $stokBaru = max(0, $stokBaru - $request->jumlah);
        }

        $status = $stokBaru > 0 ? 'Tersedia' : 'Habis';

        $barang->update([
            'stok' => $stokBaru,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diupdate',
            'stok_baru' => $stokBaru,
            'status' => $status
        ]);
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
            
            $barangs = $query->where('stok', '>', 0)
                            ->orderBy('nama')
                            ->get(['id', 'kode_barang', 'nama', 'kategori', 'unit', 'harga', 'stok']);
            
            return response()->json($barangs);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }
}