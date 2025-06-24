@extends('layouts.app')
@section('title', 'Tambah Barang Baru')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')
    
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Tambah Barang Baru</h1>
                    <p class="text-green-100">Daftarkan produk baru ke dalam sistem inventori</p>
                </div>
                <a href="{{ route('pengadaan.index') }}" 
                   class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Ada kesalahan dalam form:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('barang.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Kode Barang -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="hash" class="w-4 h-4 inline mr-1"></i>
                            Kode Barang *
                        </label>
                        <input type="text" 
                               name="kode_barang" 
                               value="{{ old('kode_barang') }}"
                               placeholder="BRG001"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk identifikasi barang</p>
                    </div>

                    <!-- Nama Barang -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                            Nama Barang *
                        </label>
                        <input type="text" 
                               name="nama" 
                               value="{{ old('nama') }}"
                               placeholder="Benang Katun 20s"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                            Kategori *
                        </label>
                        <select name="kategori" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" 
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="BedUnit" {{ old('kategori') == 'BedUnit' ? 'selected' : '' }}>BedUnit</option>
                            <option value="Benang5000Y" {{ old('kategori') == 'Benang5000Y' ? 'selected' : '' }}>Benang5000Y</option>
                            <option value="KancingLubang4" {{ old('kategori') == 'KancingLubang4' ? 'selected' : '' }}>KancingLubang4</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="ruler" class="w-4 h-4 inline mr-1"></i>
                            Unit *
                        </label>
                        <select name="unit" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" 
                                required>
                            <option value="">-- Pilih Unit --</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>pcs (pieces)</option>
                            <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>meter</option>
                            <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>roll</option>
                            <option value="pak" {{ old('unit') == 'pak' ? 'selected' : '' }}>pak</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i>
                            Harga Jual (per unit) *
                        </label>
                        <input type="number" 
                               name="harga" 
                               value="{{ old('harga') }}"
                               min="0"
                               step="0.01"
                               placeholder="15000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                        <p class="text-xs text-gray-500 mt-1">Harga jual ke customer</p>
                    </div>

                    <!-- Stok Awal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="box" class="w-4 h-4 inline mr-1"></i>
                            Stok Awal *
                        </label>
                        <input type="number" 
                               name="stok" 
                               value="{{ old('stok', 0) }}"
                               min="0"
                               placeholder="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                        <p class="text-xs text-gray-500 mt-1">Jumlah stok saat pertama kali didaftarkan</p>
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                        Keterangan
                    </label>
                    <textarea name="keterangan" 
                              rows="3"
                              placeholder="Deskripsi tambahan tentang barang ini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                    <div class="flex items-start">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-blue-800 mb-2">Informasi Penting:</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Kode barang harus unik dan tidak boleh sama</li>
                                <li>• Stok awal bisa diisi 0 jika barang belum ada</li>
                                <li>• Setelah barang terdaftar, gunakan fitur "Pengadaan" untuk menambah stok</li>
                                <li>• Harga yang diinput adalah harga jual, bukan harga beli</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2 shadow-lg">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Simpan Barang Baru</span>
                    </button>
                    
                    <a href="{{ route('pengadaan.index') }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endpush