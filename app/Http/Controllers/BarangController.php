<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;


class BarangController extends Controller
{
    // ✅ TAMPILKAN LIST BARANG
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->nama) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $barangs = $query->orderBy('created_at', 'desc')->get();

        return view('kelolabrg', compact('barangs'));
    }

    // app/Http/Controllers/BarangController.php

public function create()
{
    return view('barang.create');
}

    // ✅ SIMPAN DATA BARANG BARU
    public function store(Request $request)
{
    $request->merge([
        'harga' => str_replace('.', '', $request->harga), // hapus titik ribuan
    ]);

    $request->validate([
        'nama' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|numeric',
    ]);

    Barang::create([
        'nama' => $request->nama,
        'kategori' => $request->kategori,
        'harga' => $request->harga,
        'stok' => $request->stok,
    ]);

    return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
}


    // ✅ FORM EDIT BARANG
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    // ✅ UPDATE DATA BARANG
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $barang->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    // ✅ HAPUS BARANG
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
