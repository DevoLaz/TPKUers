<?php

namespace App\Http\Controllers;

use App\Models\AsetTetap;
use Illuminate\Http\Request;

class AsetTetapController extends Controller
{
    public function index()
    {
        $asets = AsetTetap::latest()->paginate(10);
        return view('aset_tetap.index', compact('asets'));
    }

    public function create()
    {
        return view('aset_tetap.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:1',
            'nilai_residu' => 'nullable|numeric|min:0',
        ]);

        AsetTetap::create($request->all());

        return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil ditambahkan.');
    }

    public function edit(AsetTetap $aset_tetap)
    {
        return view('aset_tetap.edit', ['aset' => $aset_tetap]);
    }

    public function update(Request $request, AsetTetap $aset_tetap)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'masa_manfaat' => 'required|integer|min:1',
            'nilai_residu' => 'nullable|numeric|min:0',
        ]);

        $aset_tetap->update($request->all());

        return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil diperbarui.');
    }

    public function destroy(AsetTetap $aset_tetap)
    {
        $aset_tetap->delete();
        return redirect()->route('aset-tetap.index')->with('success', 'Aset tetap berhasil dihapus.');
    }
}