@extends('layouts.app')
@section('title', 'Tambah Pengadaan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')
    
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Tambah Pengadaan Barang</h1>
                    <p class="text-green-100">Tambah pembelian barang baru untuk menambah stok</p>
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

            <form action="{{ route('pengadaan.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Pilih Barang -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                            Pilih Barang *
                        </label>
                        <select name="barang_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" 
                                required onchange="updateHargaBeli()">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                        data-harga="{{ $barang->harga }}"
                                        {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    [{{ $barang->kode_barang }}] {{ $barang->nama }} - {{ $barang->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Supplier -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="truck" class="w-4 h-4 inline mr-1"></i>
                            Pilih Supplier *
                        </label>
                        <select name="supplier_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" 
                                required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                            Tanggal Pembelian *
                        </label>
                        <input type="date" 
                               name="tanggal_pembelian" 
                               value="{{ old('tanggal_pembelian', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                    </div>

                    <!-- No Invoice -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="file-text" class="w-4 h-4 inline mr-1"></i>
                            No Invoice *
                        </label>
                        <input type="text" 
                               name="no_invoice" 
                               value="{{ old('no_invoice') }}"
                               placeholder="INV-2025-001"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required>
                    </div>

                    <!-- Jumlah Masuk -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="plus-circle" class="w-4 h-4 inline mr-1"></i>
                            Jumlah Masuk *
                        </label>
                        <input type="number" 
                               name="jumlah_masuk" 
                               value="{{ old('jumlah_masuk') }}"
                               min="1"
                               placeholder="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required onchange="hitungTotal()">
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i>
                            Harga Beli (per unit) *
                        </label>
                        <input type="number" 
                               name="harga_beli" 
                               id="harga_beli"
                               value="{{ old('harga_beli') }}"
                               min="0"
                               step="0.01"
                               placeholder="15000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                               required onchange="hitungTotal()">
                    </div>
                </div>

                <!-- Total Harga (Auto Calculate) -->
                <div class="bg-blue-50 p-6 rounded-lg border-2 border-blue-200">
                    <label class="block text-sm font-bold text-blue-800 mb-2">
                        <i data-lucide="calculator" class="w-4 h-4 inline mr-1"></i>
                        Total Harga
                    </label>
                    <input type="text" 
                           id="total_harga_display"
                           value="Rp 0"
                           class="w-full px-4 py-3 bg-blue-100 border border-blue-300 rounded-lg text-blue-900 font-bold text-xl" 
                           readonly>
                    <input type="hidden" name="total_harga" id="total_harga" value="0">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                        Keterangan
                    </label>
                    <textarea name="keterangan" 
                              rows="3"
                              placeholder="Catatan tambahan untuk pengadaan ini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2 shadow-lg">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Simpan Pengadaan</span>
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

    // Auto update harga beli berdasarkan barang yang dipilih
    function updateHargaBeli() {
        const barangSelect = document.querySelector('select[name="barang_id"]');
        const hargaBeliInput = document.getElementById('harga_beli');
        
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        if (selectedOption.dataset.harga) {
            hargaBeliInput.value = selectedOption.dataset.harga;
            hitungTotal();
        }
    }

    // Hitung total harga otomatis
    function hitungTotal() {
        const jumlahMasuk = document.querySelector('input[name="jumlah_masuk"]').value;
        const hargaBeli = document.querySelector('input[name="harga_beli"]').value;
        
        if (jumlahMasuk && hargaBeli) {
            const total = parseFloat(jumlahMasuk) * parseFloat(hargaBeli);
            
            // Update display
            document.getElementById('total_harga_display').value = 'Rp ' + total.toLocaleString('id-ID');
            
            // Update hidden input
            document.getElementById('total_harga').value = total;
        }
    }

    // Auto calculate on page load if values exist
    window.addEventListener('load', function() {
        hitungTotal();
    });
</script>
@endpush