@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <!-- Header dengan gradient -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Laporan Laba Rugi</h1>
            <p class="text-green-100">Analisis pendapatan dan pengeluaran perusahaan</p>
        </div>

        <!-- Filter Section dengan card effect -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-[#173720] rounded"></div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
            </div>
            
            <form method="GET" action="{{ route('laporan.laba_rugi') }}" class="flex flex-wrap gap-4">
                @csrf
                <!-- Filter Tahun -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Tahun
                    </label>
                    <select name="tahun" id="tahun-select"
                        class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] focus:border-transparent hover:border-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 inline mr-1"></i>
                        Bulan
                    </label>
                    <select name="bulan" id="bulan-select"
                        class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] focus:border-transparent hover:border-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Semua Bulan</option>
                        @php
                            // Define nama bulan di sini kalau belum ada dari controller
                            if (!isset($namaBulan)) {
                                $namaBulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            }
                            
                            // Kalau daftarBulan kosong atau tidak ada, gunakan default
                            $bulanList = (!empty($daftarBulan)) ? $daftarBulan : range(1, 12);
                        @endphp
                        @foreach($bulanList as $bln)
                            <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                                {{ $namaBulan[$bln] ?? 'Bulan ' . $bln }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tanggal -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar-check" class="w-4 h-4 inline mr-1"></i>
                        Tanggal
                    </label>
                    <select name="tanggal" id="tanggal-select"
                        class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] focus:border-transparent hover:border-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Semua Tanggal</option>
                        @php
                            // Generate tanggal berdasarkan bulan dan tahun yang dipilih
                            $tanggalList = [];
                            if ($tahun && $bulan) {
                                // Kalau ada daftarTanggal dari controller, pakai itu
                                if (!empty($daftarTanggal)) {
                                    $tanggalList = $daftarTanggal;
                                } else {
                                    // Generate manual berdasarkan bulan
                                    $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                                    $tanggalList = range(1, $jumlahHari);
                                }
                            }
                        @endphp
                        @if(count($tanggalList) > 0)
                            @foreach($tanggalList as $tgl)
                                <option value="{{ $tgl }}" {{ request('tanggal') == $tgl ? 'selected' : '' }}>
                                    {{ $tgl }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Pilih tahun dan bulan dulu</option>
                        @endif
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] hover:bg-[#2a5a37] text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Tampilkan</span>
                    </button>
                    <a href="{{ route('laporan.laba_rugi') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <!-- Period Info -->
            @if($tahun || $bulan || $tanggal)
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Menampilkan data untuk: 
                    <strong>
                        @if($tanggal)
                            {{ $tanggal }}
                        @endif
                        @if($bulan)
                            {{ $namaBulan[$bulan] ?? 'Bulan ' . $bulan }}
                        @endif
                        @if($tahun)
                            {{ $tahun }}
                        @endif
                    </strong>
                </p>
            </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Card Pendapatan -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ count($pendapatan) }} transaksi</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Card Pengeluaran -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ count($pengeluaran) }} transaksi</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i data-lucide="trending-down" class="w-8 h-8 text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Card Laba/Rugi -->
            @php
                $selisih = $totalPendapatan - $totalPengeluaran;
                $isProfit = $selisih >= 0;
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $isProfit ? 'border-blue-500' : 'border-orange-500' }} hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ $isProfit ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                        <p class="text-2xl font-bold {{ $isProfit ? 'text-blue-600' : 'text-orange-600' }}">
                            Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $isProfit ? 'Profit' : 'Loss' }} {{ number_format($totalPendapatan > 0 ? (abs($selisih) / $totalPendapatan * 100) : 0, 1) }}%
                        </p>
                    </div>
                    <div class="p-3 {{ $isProfit ? 'bg-blue-100' : 'bg-orange-100' }} rounded-full">
                        <i data-lucide="{{ $isProfit ? 'dollar-sign' : 'alert-triangle' }}" class="w-8 h-8 {{ $isProfit ? 'text-blue-600' : 'text-orange-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Laporan -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b">
                <button onclick="switchTab('pendapatan')" id="tab-pendapatan" 
                    class="flex-1 px-6 py-4 font-semibold text-[#173720] border-b-2 border-[#173720] bg-green-50 transition-all">
                    <i data-lucide="arrow-down-circle" class="w-5 h-5 inline mr-2"></i>
                    Pendapatan
                </button>
                <button onclick="switchTab('pengeluaran')" id="tab-pengeluaran"
                    class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="arrow-up-circle" class="w-5 h-5 inline mr-2"></i>
                    Pengeluaran
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Pendapatan Tab -->
                <div id="content-pendapatan" class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-green-50 border-b">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-[#173720]">Tanggal</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-[#173720]">Keterangan</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-[#173720]">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendapatan as $item)
                                    <tr class="hover:bg-green-50 border-b transition-colors">
                                        <td class="py-3 px-4 text-sm">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-medium">{{ $item->keterangan }}</span>
                                            @if($item->kategori)
                                                <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                    {{ $item->kategori }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-semibold text-green-600">
                                            +Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-gray-500">
                                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                            <p>Belum ada data pendapatan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($pendapatan) > 0)
                                <tfoot>
                                    <tr class="bg-green-100 font-bold">
                                        <td colspan="2" class="py-3 px-4">Total Pendapatan</td>
                                        <td class="py-3 px-4 text-right text-green-700">
                                            +Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Pengeluaran Tab -->
                <div id="content-pengeluaran" class="space-y-4 hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-red-50 border-b">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-red-900">Tanggal</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-red-900">Keterangan</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-red-900">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengeluaran as $item)
                                    <tr class="hover:bg-red-50 border-b transition-colors">
                                        <td class="py-3 px-4 text-sm">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-medium">{{ $item->keterangan }}</span>
                                            @if($item->kategori)
                                                <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                                    {{ $item->kategori }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-semibold text-red-600">
                                            -Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-gray-500">
                                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                            <p>Belum ada data pengeluaran</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($pengeluaran) > 0)
                                <tfoot>
                                    <tr class="bg-red-100 font-bold">
                                        <td colspan="2" class="py-3 px-4">Total Pengeluaran</td>
                                        <td class="py-3 px-4 text-right text-red-700">
                                            -Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Footer -->
            <div class="p-6 bg-gradient-to-r {{ $isProfit ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600' }}">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <p class="text-lg font-semibold opacity-90">{{ $isProfit ? 'LABA BERSIH' : 'RUGI BERSIH' }}</p>
                        <p class="text-3xl font-bold">Rp {{ number_format(abs($selisih), 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('laporan.cetak.laba_rugi', request()->all()) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/30 rounded-lg transition-all transform hover:scale-105 backdrop-blur">
                            <i data-lucide="printer" class="w-5 h-5"></i>
                            <span class="font-semibold">Cetak PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide-static@latest"></script>
<script>
    lucide.createIcons();
    
    // Tab switching
    function switchTab(tab) {
        // Reset all tabs
        document.getElementById('tab-pendapatan').classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
        document.getElementById('tab-pengeluaran').classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-red-50');
        document.getElementById('tab-pendapatan').classList.add('text-gray-600');
        document.getElementById('tab-pengeluaran').classList.add('text-gray-600');
        
        // Hide all content
        document.getElementById('content-pendapatan').classList.add('hidden');
        document.getElementById('content-pengeluaran').classList.add('hidden');
        
        // Show selected tab
        if (tab === 'pendapatan') {
            document.getElementById('tab-pendapatan').classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
            document.getElementById('tab-pendapatan').classList.remove('text-gray-600');
            document.getElementById('content-pendapatan').classList.remove('hidden');
        } else {
            document.getElementById('tab-pengeluaran').classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-red-50');
            document.getElementById('tab-pengeluaran').classList.remove('text-gray-600');
            document.getElementById('content-pengeluaran').classList.remove('hidden');
        }
    }
    
    // Manual submit dengan event listener
    document.addEventListener('DOMContentLoaded', function() {
        const tahunSelect = document.getElementById('tahun-select');
        const bulanSelect = document.getElementById('bulan-select');
        const tanggalSelect = document.getElementById('tanggal-select');
        const form = document.querySelector('form');
        
        // Jangan auto submit, tunggu user klik tombol
        if (tahunSelect) {
            tahunSelect.addEventListener('change', function() {
                // Enable/disable bulan dropdown
                if (this.value) {
                    bulanSelect.disabled = false;
                    bulanSelect.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    bulanSelect.disabled = true;
                    bulanSelect.classList.add('opacity-50', 'cursor-not-allowed');
                    bulanSelect.value = '';
                    tanggalSelect.disabled = true;
                    tanggalSelect.classList.add('opacity-50', 'cursor-not-allowed');
                    tanggalSelect.value = '';
                }
            });
        }
        
        if (bulanSelect) {
            bulanSelect.addEventListener('change', function() {
                // Enable/disable tanggal dropdown
                if (this.value && tahunSelect.value) {
                    tanggalSelect.disabled = false;
                    tanggalSelect.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    tanggalSelect.disabled = true;
                    tanggalSelect.classList.add('opacity-50', 'cursor-not-allowed');
                    tanggalSelect.value = '';
                }
            });
        }
    });
</script>
@endpush