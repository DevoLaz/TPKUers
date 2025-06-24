@extends('layouts.app')

@section('title', 'Input Utang & Piutang')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
    .flatpickr-calendar {
        background: #f9fafb;
        border-radius: 0.5rem;
        box-shadow: 1px 0px 3px rgba(0, 0, 0, 0.1);
    }
    .flatpickr-day.selected {
        background: #173720;
        border-color: #173720;
    }
    .form-input-custom {
        transition: all 0.3s ease;
    }
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
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Input Data Utang & Piutang</h1>
            <p class="text-green-100">Catat transaksi utang atau piutang baru di sini.</p>
        </div>
        
        {{-- 🔥 TAMBAHKAN KODE INI UNTUK MENAMPILKAN ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Oops! Ada yang salah:</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <!-- Form Section -->
        <div class="bg-white rounded-2xl shadow-md p-6 lg:p-8">
            <form method="POST" action="{{ route('laporan.utang_piutang.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <div>
                            <label for="jenis_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi</label>
                            <select id="jenis_transaksi" name="jenis_transaksi" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                                <option value="piutang">Piutang (Uang Masuk)</option>
                                <option value="utang">Utang (Uang Keluar)</option>
                            </select>
                        </div>

                        <div>
                            <label id="pihak_terkait_label" for="pihak_terkait" class="block text-sm font-semibold text-gray-700 mb-2">Nama Pelanggan</label>
                            <input type="text" id="pihak_terkait" name="pihak_terkait" placeholder="Contoh: PT Maju Jaya" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                        </div>
                        
                        <div>
                            <label for="akun" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Akun</label>
                            <select id="akun" name="akun" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                                <optgroup label="Piutang">
                                    <option value="Piutang Usaha">Piutang Usaha</option>
                                </optgroup>
                                <optgroup label="Utang">
                                    <option value="Utang Usaha">Utang Usaha</option>
                                    <option value="Utang Gaji">Utang Gaji</option>
                                    <option value="Utang Pajak">Utang Pajak (PPh)</option>
                                    <option value="Utang PPN">Utang PPN Keluaran</option>
                                    <option value="Utang Bank">Utang Bank</option>
                                    <option value="Utang Komisi">Utang Komisi Penjualan</option>
                                </optgroup>
                            </select>
                        </div>

                        <div>
                            <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="jumlah" name="jumlah" placeholder="5000000" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-6">
                        <div>
                            <label for="no_referensi" class="block text-sm font-semibold text-gray-700 mb-2">No. Invoice / Referensi</label>
                            <input type="text" id="no_referensi" name="no_referensi" placeholder="Contoh: INV/2025/06/001" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                        </div>

                        <div>
                            <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi</label>
                            <input type="text" id="tanggal_transaksi" name="tanggal_transaksi" placeholder="Pilih tanggal" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                        </div>

                        <div>
                            <label for="tanggal_jatuh_tempo" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Jatuh Tempo</label>
                            <input type="text" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" placeholder="Pilih tanggal" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom">
                        </div>
                        
                        <div>
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea id="keterangan" name="keterangan" rows="3" placeholder="Tulis catatan tambahan di sini..." class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white shadow-sm form-input-custom"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end gap-4">
                    <a href="{{ route('laporan.utang_piutang') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all transform hover:scale-105 shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#173720] hover:bg-[#2a5a37] text-white font-semibold rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script> {{-- Bahasa Indonesia --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Flatpickr (kalender)
        flatpickr("#tanggal_transaksi", {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale: "id", // Menggunakan bahasa Indonesia
            defaultDate: "today"
        });

        flatpickr("#tanggal_jatuh_tempo", {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            locale: "id"
        });

        // Logika untuk mengubah label
        const jenisSelect = document.getElementById('jenis_transaksi');
        const pihakLabel = document.getElementById('pihak_terkait_label');
        const pihakInput = document.getElementById('pihak_terkait');

        jenisSelect.addEventListener('change', function() {
            if (this.value === 'piutang') {
                pihakLabel.textContent = 'Nama Pelanggan';
                pihakInput.placeholder = 'Contoh: PT Maju Jaya';
            } else {
                pihakLabel.textContent = 'Nama Pemasok / Vendor';
                pihakInput.placeholder = 'Contoh: CV Sumber Berkah';
            }
        });
        
        lucide.createIcons();
    });
</script>
@endpush
