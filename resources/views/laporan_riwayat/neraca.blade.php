@extends('layouts.app')

@section('title', 'Laporan Neraca')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Neraca</h1>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('laporan.neraca') }}" class="flex flex-wrap gap-4 mb-6">
            <div class="inline-block">
                <label for="tanggal" class="text-sm font-medium text-gray-700">Pilih Tanggal Neraca:</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}"
                       class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
            </div>
            <button type="submit" class="self-end bg-[#173720] hover:bg-green-700 text-white px-4 py-2 rounded">
                Tampilkan
            </button>
        </form>

        <div class="bg-white shadow rounded-lg p-6">
            <!-- Header Neraca -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-[#173720]">NERACA</h2>
                <p class="text-gray-600">Per {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- KOLOM KIRI: ASET -->
                <div>
                    <h3 class="text-lg font-bold text-[#173720] mb-3 border-b-2 border-green-700 pb-1">ASET</h3>
                    
                    <!-- Aset Lancar -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Aset Lancar</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Kas dan Setara Kas</span>
                                <span>Rp {{ number_format($totalKas, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Piutang Usaha</span>
                                <span>Rp {{ number_format($totalPiutang, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Persediaan Barang</span>
                                <span>Rp {{ number_format($totalPersediaan, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1 mt-1">
                                <span>Total Aset Lancar</span>
                                <span>Rp {{ number_format($totalAsetLancar, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Aset Tetap -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Aset Tetap</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Harga Perolehan</span>
                                <span>Rp {{ number_format($totalHargaPerolehan, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Akumulasi Penyusutan</span>
                                <span class="text-red-600">(Rp {{ number_format($totalAkumulasiPenyusutan, 2, ',', '.') }})</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1 mt-1">
                                <span>Nilai Buku Aset Tetap</span>
                                <span>Rp {{ number_format($totalNilaiBukuAsetTetap, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Aset -->
                    <div class="bg-green-50 p-3 rounded mt-4">
                        <div class="flex justify-between font-bold text-[#173720]">
                            <span>TOTAL ASET</span>
                            <span>Rp {{ number_format($totalAset, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: KEWAJIBAN & EKUITAS -->
                <div>
                    <h3 class="text-lg font-bold text-[#173720] mb-3 border-b-2 border-green-700 pb-1">KEWAJIBAN & EKUITAS</h3>
                    
                    <!-- Kewajiban -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Kewajiban</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Utang Usaha</span>
                                <span>Rp {{ number_format($totalUtang, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1 mt-1">
                                <span>Total Kewajiban</span>
                                <span>Rp {{ number_format($totalLiabilitas, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ekuitas -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Ekuitas</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Modal</span>
                                <span>Rp {{ number_format($totalEkuitas, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1 mt-1">
                                <span>Total Ekuitas</span>
                                <span>Rp {{ number_format($totalEkuitas, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kewajiban & Ekuitas -->
                    @php
                        $totalKewajibanEkuitas = $totalLiabilitas + $totalEkuitas;
                    @endphp
                    <div class="bg-green-50 p-3 rounded mt-4">
                        <div class="flex justify-between font-bold text-[#173720]">
                            <span>TOTAL KEWAJIBAN & EKUITAS</span>
                            <span>Rp {{ number_format($totalKewajibanEkuitas, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Balance Check -->
            @php
                $isBalanced = round($totalAset) == round($totalKewajibanEkuitas);
            @endphp
            
            <div class="mt-6 p-4 rounded-lg {{ $isBalanced ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <div class="flex items-center justify-center gap-2">
                    <i data-lucide="{{ $isBalanced ? 'check-circle' : 'alert-circle' }}" class="w-5 h-5"></i>
                    <span class="font-semibold">
                        {{ $isBalanced ? 'Neraca Seimbang (Balanced)' : 'Neraca Tidak Seimbang!' }}
                    </span>
                </div>
            </div>

            <!-- Tombol Cetak -->
            <div class="flex justify-end mt-6 gap-3">
                <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    Print
                </button>
                <a href="{{ route('laporan.cetak.neraca', request()->only(['tanggal'])) }}" 
                   target="_blank" 
                   class="bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Cetak PDF
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
@endpush
