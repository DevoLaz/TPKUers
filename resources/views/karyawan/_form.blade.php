@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
    {{-- NAMA LENGKAP --}}
    <div>
        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" required placeholder="Contoh: Adam Sholihuddin">
    </div>

    {{-- JABATAN --}}
    <div>
        <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
        <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $karyawan->jabatan ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" required placeholder="Contoh: Manajer Keuangan">
    </div>

    {{-- NIK --}}
    <div>
        <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK (Opsional)</label>
        <input type="text" name="nik" id="nik" value="{{ old('nik', $karyawan->nik ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" placeholder="16 digit NIK">
    </div>

    {{-- NPWP --}}
    <div>
        <label for="npwp" class="block text-sm font-semibold text-gray-700 mb-2">NPWP (Opsional)</label>
        <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $karyawan->npwp ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" placeholder="15 atau 16 digit NPWP">
    </div>

    {{-- STATUS KARYAWAN --}}
    <div>
        <label for="status_karyawan" class="block text-sm font-semibold text-gray-700 mb-2">Status Karyawan</label>
        <select name="status_karyawan" id="status_karyawan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition">
            <option value="kontrak" @selected(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'kontrak')>Kontrak</option>
            <option value="tetap" @selected(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'tetap')>Tetap</option>
            <option value="harian" @selected(old('status_karyawan', $karyawan->status_karyawan ?? '') == 'harian')>Harian</option>
        </select>
    </div>

    {{-- TANGGAL BERGABUNG --}}
    <div>
        <label for="tanggal_bergabung" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bergabung</label>
        <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" value="{{ old('tanggal_bergabung', isset($karyawan) ? \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" required>
    </div>

    {{-- GAJI POKOK --}}
    <div class="md:col-span-2">
        <label for="gaji_pokok_default" class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok Default</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <span class="text-gray-500">Rp</span>
            </div>
            <input type="number" name="gaji_pokok_default" id="gaji_pokok_default" value="{{ old('gaji_pokok_default', $karyawan->gaji_pokok_default ?? '') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition" required placeholder="5000000">
        </div>
    </div>
    
    {{-- KHUSUS UNTUK FORM EDIT --}}
    @if (isset($karyawan))
    <div>
        <label for="aktif" class="block text-sm font-semibold text-gray-700 mb-2">Status Aktif</label>
        <select name="aktif" id="aktif" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm focus:border-[#173720] focus:ring-2 focus:ring-[#173720]/50 transition">
            <option value="1" @selected(old('aktif', $karyawan->aktif ?? true) == 1)>Aktif</option>
            <option value="0" @selected(old('aktif', $karyawan->aktif ?? true) == 0)>Non-Aktif</option>
        </select>
    </div>
    @endif
</div>
<div class="mt-10 pt-6 border-t border-gray-200 flex justify-end gap-4">
    <a href="{{ route('karyawan.index') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all transform hover:scale-105 shadow-sm">
        Batal
    </a>
    <button type="submit" class="px-8 py-3 bg-[#173720] hover:bg-[#2a5a37] text-white font-semibold rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
         <i data-lucide="save" class="w-5 h-5"></i>
        Simpan Data
    </button>
</div>
