@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label for="kode_barang" class="block text-sm font-bold text-gray-700 mb-2">Kode Barang *</label>
        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang ?? '') }}" placeholder="BRG001" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
    </div>

    <div>
        <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Barang *</label>
        <input type="text" name="nama" value="{{ old('nama', $barang->nama ?? '') }}" placeholder="Kain Katun" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
    </div>

    <div>
        <label for="kategori" class="block text-sm font-bold text-gray-700 mb-2">Kategori *</label>
        <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoriList as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori', $barang->kategori ?? '') == $kategori)>
                    {{ $kategori }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="unit" class="block text-sm font-bold text-gray-700 mb-2">Unit *</label>
        <select name="unit" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white" required>
            <option value="">-- Pilih Unit --</option>
            <option value="pcs" @selected(old('unit', $barang->unit ?? '') == 'pcs')>pcs</option>
            <option value="meter" @selected(old('unit', $barang->unit ?? '') == 'meter')>meter</option>
            <option value="roll" @selected(old('unit', $barang->unit ?? '') == 'roll')>roll</option>
            <option value="pak" @selected(old('unit', $barang->unit ?? '') == 'pak')>pak</option>
            <option value="kg" @selected(old('unit', $barang->unit ?? '') == 'kg')>kg</option>
        </select>
    </div>

    <div>
        <label for="harga" class="block text-sm font-bold text-gray-700 mb-2">Harga Jual (per unit) *</label>
        <input type="number" name="harga" value="{{ old('harga', $barang->harga ?? '') }}" min="0" placeholder="15000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
    </div>

    <div>
        <label for="stok" class="block text-sm font-bold text-gray-700 mb-2">Stok *</label>
        <input type="number" name="stok" value="{{ old('stok', $barang->stok ?? 0) }}" min="0" placeholder="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
    </div>
</div>

<div class="flex gap-4 pt-6 border-t border-gray-200 mt-6">
    <button type="submit" class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
        Simpan Perubahan
    </button>
    <a href="{{ route('barang.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition">
        Batal
    </a>
</div>