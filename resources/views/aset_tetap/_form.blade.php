@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="nama_aset" class="block text-sm font-bold text-gray-700 mb-2">Nama Aset *</label>
        <input type="text" name="nama_aset" value="{{ old('nama_aset', $aset->nama_aset ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>
    <div>
        <label for="kategori" class="block text-sm font-bold text-gray-700 mb-2">Kategori Aset *</label>
        <input type="text" name="kategori" value="{{ old('kategori', $aset->kategori ?? '') }}" placeholder="Contoh: Kendaraan, Gedung, Peralatan" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>
    <div>
        <label for="tanggal_perolehan" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Perolehan *</label>
        <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', isset($aset) ? $aset->tanggal_perolehan->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>
    <div>
        <label for="harga_perolehan" class="block text-sm font-bold text-gray-700 mb-2">Harga Perolehan (Rp) *</label>
        <input type="number" name="harga_perolehan" value="{{ old('harga_perolehan', $aset->harga_perolehan ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>
    <div>
        <label for="masa_manfaat" class="block text-sm font-bold text-gray-700 mb-2">Masa Manfaat (Tahun) *</label>
        <input type="number" name="masa_manfaat" value="{{ old('masa_manfaat', $aset->masa_manfaat ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
    </div>
    <div>
        <label for="nilai_residu" class="block text-sm font-bold text-gray-700 mb-2">Nilai Residu/Sisa (Rp)</label>
        <input type="number" name="nilai_residu" value="{{ old('nilai_residu', $aset->nilai_residu ?? 0) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
    </div>
    <div class="md:col-span-2">
        <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('deskripsi', $aset->deskripsi ?? '') }}</textarea>
    </div>
</div>
<div class="mt-6 flex justify-end gap-4">
    <a href="{{ route('aset-tetap.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">Batal</a>
    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">Simpan</button>
</div>