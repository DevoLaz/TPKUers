@extends('layouts.app')

@section('title', 'Input Data Perpajakan')

@push('styles')
<style>
    .form-input-custom:focus {
        border-color: #173720;
        box-shadow: 0 0 0 2px rgba(23, 55, 32, 0.2);
    }
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Pencatatan Pajak</h1>
            <p class="text-green-100">Catat transaksi PPN atau PPh yang terjadi.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-bold">Oops! Ada yang salah:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="tax-form" method="POST" action="{{ route('laporan.perpajakan.store') }}" class="bg-white rounded-2xl shadow-md p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="jenis_pajak" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pajak</label>
                        <select id="jenis_pajak" name="jenis_pajak" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 form-input-custom" required>
                            <option value="PPN Masukan">PPN Masukan (Pajak dari Pembelian)</option>
                            <option value="PPN Keluaran">PPN Keluaran (Pajak dari Penjualan)</option>
                            <option value="PPh 21">PPh Pasal 21 (Gaji Karyawan)</option>
                            <option value="PPh 23">PPh Pasal 23 (Jasa/Sewa)</option>
                            <option value="PPh Final">PPh Final (UMKM/Lainnya)</option>
                        </select>
                    </div>

                    <div>
                        <label for="dasar_pengenaan_pajak" class="block text-sm font-semibold text-gray-700 mb-2">Dasar Pengenaan Pajak (DPP)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">Rp</span>
                            <input type="number" id="dasar_pengenaan_pajak" name="dasar_pengenaan_pajak" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 form-input-custom" placeholder="10000000" required>
                        </div>
                    </div>

                    <div>
                        <label for="tarif_pajak" class="block text-sm font-semibold text-gray-700 mb-2">Tarif Pajak (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" id="tarif_pajak" name="tarif_pajak" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 form-input-custom" placeholder="11" required>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500">%</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                     <div>
                        <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi/Faktur</label>
                        <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 form-input-custom" required>
                    </div>

                    <div>
                        <label for="no_referensi" class="block text-sm font-semibold text-gray-700 mb-2">No. Faktur / Referensi</label>
                        <input type="text" id="no_referensi" name="no_referensi" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 form-input-custom" placeholder="Nomor faktur pajak atau bukti potong">
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 form-input-custom" placeholder="Contoh: PPN atas pembelian ATK dari CV Sumber Jaya"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <p class="text-sm font-semibold text-gray-600">Jumlah Pajak Terhitung</p>
                    <p id="hasil_pajak" class="text-4xl font-extrabold text-[#173720] mt-2">Rp 0</p>
                </div>
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <a href="{{ route('laporan.perpajakan') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#173720] hover:bg-[#2a5a37] text-white font-semibold rounded-lg flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Data Pajak
                </button>
            </div>
        </form>
    </main>
</div>
@endsection

@push('scripts')
{{-- 🔥 FIXED: Menambahkan skrip untuk memuat ikon Lucide --}}
<script src="https://cdn.jsdelivr.net/npm/lucide-static@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 🔥 FIXED: Menjalankan skrip untuk membuat ikon
    lucide.createIcons();

    const dppInput = document.getElementById('dasar_pengenaan_pajak');
    const tarifInput = document.getElementById('tarif_pajak');
    const hasilPajakElem = document.getElementById('hasil_pajak');

    function calculateTax() {
        const dpp = parseFloat(dppInput.value) || 0;
        const tarif = parseFloat(tarifInput.value) || 0;
        const pajak = (dpp * tarif) / 100;

        hasilPajakElem.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(pajak);
    }

    dppInput.addEventListener('input', calculateTax);
    tarifInput.addEventListener('input', calculateTax);

    // Set default tarif untuk PPN
    document.getElementById('jenis_pajak').addEventListener('change', function() {
        if (this.value.includes('PPN')) {
            tarifInput.value = 11;
        } else {
            tarifInput.value = '';
        }
        calculateTax();
    });
    
    // Trigger change event to set default tarif PPN on page load
    document.getElementById('jenis_pajak').dispatchEvent(new Event('change'));
});
</script>
@endpush
