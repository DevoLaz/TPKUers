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
                <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                    class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
            </div>
            <button type="submit" class="bg-[#173720] hover:bg-green-700 text-white px-4 py-2 rounded">
                Tampilkan
            </button>
        </form>

        <div class="bg-white shadow rounded-lg p-6">
            <!-- Header Neraca -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-[#173720]">NERACA</h2>
                <p class="text-gray-600">Per {{ \Carbon\Carbon::parse(request('tanggal', now()))->format('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- KOLOM KIRI: ASET -->
                <div>
                    <!-- Aset Lancar -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#173720] mb-3 border-b-2 border-green-700 pb-1">ASET</h3>
                        
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Aset Lancar</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Kas</span>
                                <span>Rp {{ number_format($data['kas'] ?? 50000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Bank</span>
                                <span>Rp {{ number_format($data['bank'] ?? 150000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Piutang Usaha</span>
                                <span>Rp {{ number_format($data['piutang'] ?? 35000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Persediaan Barang</span>
                                <span>Rp {{ number_format($data['persediaan'] ?? 85000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1">
                                <span>Total Aset Lancar</span>
                                <span>Rp {{ number_format($data['total_aset_lancar'] ?? 320000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Aset Tetap -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Aset Tetap</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Tanah</span>
                                <span>Rp {{ number_format($data['tanah'] ?? 500000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Bangunan</span>
                                <span>Rp {{ number_format($data['bangunan'] ?? 750000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kendaraan</span>
                                <span>Rp {{ number_format($data['kendaraan'] ?? 250000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Peralatan</span>
                                <span>Rp {{ number_format($data['peralatan'] ?? 150000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-red-600">
                                <span>(Akumulasi Penyusutan)</span>
                                <span>(Rp {{ number_format($data['akm_penyusutan'] ?? 200000000, 0, ',', '.') }})</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1">
                                <span>Total Aset Tetap</span>
                                <span>Rp {{ number_format($data['total_aset_tetap'] ?? 1450000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Aset -->
                    <div class="bg-green-50 p-3 rounded">
                        <div class="flex justify-between font-bold text-[#173720]">
                            <span>TOTAL ASET</span>
                            <span>Rp {{ number_format($data['total_aset'] ?? 1770000000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: KEWAJIBAN & EKUITAS -->
                <div>
                    <!-- Kewajiban -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-[#173720] mb-3 border-b-2 border-green-700 pb-1">KEWAJIBAN & EKUITAS</h3>
                        
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Kewajiban Lancar</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Utang Usaha</span>
                                <span>Rp {{ number_format($data['utang_usaha'] ?? 65000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Utang Gaji</span>
                                <span>Rp {{ number_format($data['utang_gaji'] ?? 25000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Utang Pajak</span>
                                <span>Rp {{ number_format($data['utang_pajak'] ?? 15000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1">
                                <span>Total Kewajiban Lancar</span>
                                <span>Rp {{ number_format($data['total_kewajiban_lancar'] ?? 105000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kewajiban Jangka Panjang -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Kewajiban Jangka Panjang</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Utang Bank</span>
                                <span>Rp {{ number_format($data['utang_bank'] ?? 300000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1">
                                <span>Total Kewajiban Jangka Panjang</span>
                                <span>Rp {{ number_format($data['total_kewajiban_panjang'] ?? 300000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kewajiban -->
                    <div class="mb-6">
                        <div class="flex justify-between font-semibold text-[#173720] bg-red-50 p-2 rounded">
                            <span>Total Kewajiban</span>
                            <span>Rp {{ number_format($data['total_kewajiban'] ?? 405000000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Ekuitas -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-[#173720] mb-2 ml-2">Ekuitas</h4>
                        <div class="space-y-1 ml-4 text-sm">
                            <div class="flex justify-between">
                                <span>Modal Pemilik</span>
                                <span>Rp {{ number_format($data['modal'] ?? 1200000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Laba Ditahan</span>
                                <span>Rp {{ number_format($data['laba_ditahan'] ?? 165000000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-1">
                                <span>Total Ekuitas</span>
                                <span>Rp {{ number_format($data['total_ekuitas'] ?? 1365000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kewajiban & Ekuitas -->
                    <div class="bg-green-50 p-3 rounded">
                        <div class="flex justify-between font-bold text-[#173720]">
                            <span>TOTAL KEWAJIBAN & EKUITAS</span>
                            <span>Rp {{ number_format($data['total_kewajiban_ekuitas'] ?? 1770000000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Balance Check -->
            @php
                $totalAset = $data['total_aset'] ?? 1770000000;
                $totalKewajEkuitas = $data['total_kewajiban_ekuitas'] ?? 1770000000;
                $isBalanced = $totalAset == $totalKewajEkuitas;
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
<script src="https://unpkg.com/lucide-static@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush