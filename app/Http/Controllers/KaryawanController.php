<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawans = Karyawan::latest()->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'nik' => 'nullable|string|max:16|unique:karyawans,nik',
            'npwp' => 'nullable|string|max:20|unique:karyawans,npwp',
            'status_karyawan' => 'required|in:tetap,kontrak,harian',
            'tanggal_bergabung' => 'required|date',
            'gaji_pokok_default' => 'required|numeric|min:0',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'nik' => 'nullable|string|max:16|unique:karyawans,nik,' . $karyawan->id,
            'npwp' => 'nullable|string|max:20|unique:karyawans,npwp,' . $karyawan->id,
            'status_karyawan' => 'required|in:tetap,kontrak,harian',
            'tanggal_bergabung' => 'required|date',
            'gaji_pokok_default' => 'required|numeric|min:0',
            'aktif' => 'required|boolean',
        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')
                         ->with('success', 'Data karyawan berhasil dihapus.');
    }
}
