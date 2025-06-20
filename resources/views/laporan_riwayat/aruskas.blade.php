@extends('layouts.app')

@section('title', 'Laporan Arus Kas')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <!-- Header dengan gradient -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">Laporan Arus Kas</h1>
            <p class="text-green-100">Analisis pergerakan kas masuk dan keluar perusahaan</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-[#173720] rounded"></div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
            </div>
            
            <form method="GET" action="{{ route('laporan.arus_kas') }}" class="flex flex-wrap gap-4">
                @csrf
                <!-- Filter Tahun -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Tahun
                    </label>
                    <select name="tahun" id="tahun-select"
                        class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] focus:border-transparent hover:border-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Pilih Tahun</option>
                        @foreach($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ request('tahun', $tahun) == $thn ? 'selected' : '' }}>
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
                            $namaBulan = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan', $bulan) == $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] hover:bg-[#2a5a37] text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Tampilkan</span>
                    </button>
                    <a href="{{ route('laporan.arus_kas') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <!-- Period Info -->
            @if($tahun || $bulan)
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Menampilkan arus kas untuk: 
                    <strong>
                        @if($bulan && isset($namaBulan[$bulan]))
                            {{ $namaBulan[$bulan] }}
                        @endif
                        @if($tahun)
                            {{ $tahun }}
                        @else
                            Semua Periode
                        @endif
                    </strong>
                </p>
            </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Saldo Awal -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Saldo Awal</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Awal periode</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i data-lucide="wallet" class="w-8 h-8 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Kas Masuk -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Kas Masuk</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $jumlahTransaksiMasuk }} transaksi</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i data-lucide="arrow-down-circle" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Kas Keluar -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Kas Keluar</p>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $jumlahTransaksiKeluar }} transaksi</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i data-lucide="arrow-up-circle" class="w-8 h-8 text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Saldo Akhir -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $saldoAkhir >= 0 ? 'border-purple-500' : 'border-orange-500' }} hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Saldo Akhir</p>
                        <p class="text-2xl font-bold {{ $saldoAkhir >= 0 ? 'text-purple-600' : 'text-orange-600' }}">
                            Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $saldoAkhir >= 0 ? 'Surplus' : 'Defisit' }} {{ number_format(abs($saldoAkhir - $saldoAwal), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 {{ $saldoAkhir >= 0 ? 'bg-purple-100' : 'bg-orange-100' }} rounded-full">
                        <i data-lucide="{{ $saldoAkhir >= 0 ? 'trending-up' : 'trending-down' }}" 
                           class="w-8 h-8 {{ $saldoAkhir >= 0 ? 'text-purple-600' : 'text-orange-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Arus Kas -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b">
                <button onclick="switchTab('operasional')" id="tab-operasional" 
                    class="flex-1 px-6 py-4 font-semibold text-[#173720] border-b-2 border-[#173720] bg-green-50 transition-all">
                    <i data-lucide="briefcase" class="w-5 h-5 inline mr-2"></i>
                    Aktivitas Operasional
                </button>
                <button onclick="switchTab('investasi')" id="tab-investasi"
                    class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="trending-up" class="w-5 h-5 inline mr-2"></i>
                    Aktivitas Investasi
                </button>
                <button onclick="switchTab('pendanaan')" id="tab-pendanaan"
                    class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="dollar-sign" class="w-5 h-5 inline mr-2"></i>
                    Aktivitas Pendanaan
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Aktivitas Operasional Tab -->
                <div id="content-operasional" class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Operasional</h3>
                    
                    <!-- Kas Masuk Operasional -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-green-700 mb-3">
                            <i data-lucide="plus-circle" class="w-5 h-5 inline mr-1"></i>
                            Kas Masuk
                        </h4>
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
                                    @forelse ($operasionalMasuk as $item)
                                        <tr class="hover:bg-green-50 border-b transition-colors">
                                            <td class="py-3 px-4 text-sm">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            </td>
                                            <td class="py-3 px-4">{{ $item->keterangan }}</td>
                                            <td class="py-3 px-4 text-right font-semibold text-green-600">
                                                +Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500">
                                                Tidak ada kas masuk dari aktivitas operasional
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($operasionalMasuk) > 0)
                                    <tfoot>
                                        <tr class="bg-green-100 font-bold">
                                            <td colspan="2" class="py-3 px-4">Subtotal Kas Masuk</td>
                                            <td class="py-3 px-4 text-right text-green-700">
                                                +Rp {{ number_format($operasionalMasuk->sum('jumlah'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Kas Keluar Operasional -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-red-700 mb-3">
                            <i data-lucide="minus-circle" class="w-5 h-5 inline mr-1"></i>
                            Kas Keluar
                        </h4>
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
                                    @forelse ($operasionalKeluar as $item)
                                        <tr class="hover:bg-red-50 border-b transition-colors">
                                            <td class="py-3 px-4 text-sm">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            </td>
                                            <td class="py-3 px-4">{{ $item->keterangan }}</td>
                                            <td class="py-3 px-4 text-right font-semibold text-red-600">
                                                -Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500">
                                                Tidak ada kas keluar dari aktivitas operasional
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($operasionalKeluar) > 0)
                                    <tfoot>
                                        <tr class="bg-red-100 font-bold">
                                            <td colspan="2" class="py-3 px-4">Subtotal Kas Keluar</td>
                                            <td class="py-3 px-4 text-right text-red-700">
                                                -Rp {{ number_format($operasionalKeluar->sum('jumlah'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Net Operasional -->
                    @php
                        $netOperasional = $operasionalMasuk->sum('jumlah') - $operasionalKeluar->sum('jumlah');
                    @endphp
                    <div class="p-4 rounded-lg {{ $netOperasional >= 0 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Arus Kas Bersih dari Aktivitas Operasional</span>
                            <span class="text-xl font-bold">
                                {{ $netOperasional >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netOperasional), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Investasi Tab -->
                <div id="content-investasi" class="space-y-4 hidden">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Investasi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-blue-50 border-b">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-blue-900">Tanggal</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-blue-900">Keterangan</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold text-blue-900">Jenis</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-blue-900">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($investasi as $item)
                                    <tr class="hover:bg-blue-50 border-b transition-colors">
                                        <td class="py-3 px-4 text-sm">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4">{{ $item->keterangan }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($item->jenis == 'masuk')
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Penjualan Aset</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Pembelian Aset</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-semibold {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">
                                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                            <p>Tidak ada aktivitas investasi pada periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Aktivitas Pendanaan Tab -->
                <div id="content-pendanaan" class="space-y-4 hidden">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Pendanaan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-purple-50 border-b">
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-purple-900">Tanggal</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-purple-900">Keterangan</th>
                                    <th class="py-3 px-4 text-center text-sm font-semibold text-purple-900">Jenis</th>
                                    <th class="py-3 px-4 text-right text-sm font-semibold text-purple-900">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendanaan as $item)
                                    <tr class="hover:bg-purple-50 border-b transition-colors">
                                        <td class="py-3 px-4 text-sm">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4">{{ $item->keterangan }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($item->jenis == 'masuk')
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Modal/Pinjaman</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Pembayaran</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right font-semibold {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">
                                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                            <p>Tidak ada aktivitas pendanaan pada periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Footer -->
            <div class="p-6 bg-gradient-to-r {{ $saldoAkhir >= $saldoAwal ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600' }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white">
                    <div>
                        <p class="text-sm opacity-90">Arus Kas Operasional</p>
                        <p class="text-xl font-bold">
                            {{ $netOperasional >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netOperasional), 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Arus Kas Investasi</p>
                        <p class="text-xl font-bold">
                            @php
                                $netInvestasi = $investasi->where('jenis', 'masuk')->sum('jumlah') - 
                                               $investasi->where('jenis', 'keluar')->sum('jumlah');
                            @endphp
                            {{ $netInvestasi >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netInvestasi), 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Arus Kas Pendanaan</p>
                        <p class="text-xl font-bold">
                            @php
                                $netPendanaan = $pendanaan->where('jenis', 'masuk')->sum('jumlah') - 
                                               $pendanaan->where('jenis', 'keluar')->sum('jumlah');
                            @endphp
                            {{ $netPendanaan >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netPendanaan), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-white/30 flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold opacity-90">PERUBAHAN KAS BERSIH</p>
                        <p class="text-3xl font-bold">
                            {{ ($saldoAkhir - $saldoAwal) >= 0 ? '+' : '-' }}Rp {{ number_format(abs($saldoAkhir - $saldoAwal), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('laporan.cetak.arus_kas', request()->all()) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/30 rounded-lg transition-all transform hover:scale-105 backdrop-blur">
                            <i data-lucide="printer" class="w-5 h-5"></i>
                            <span class="font-semibold">Cetak PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Chart (Optional) -->
        <div class="mt-6 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-[#173720] mb-4">
                <i data-lucide="bar-chart-3" class="w-5 h-5 inline mr-2"></i>
                Grafik Arus Kas
            </h3>
            <div class="h-64 flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-300 rounded-lg">
                <div class="text-center">
                    <i data-lucide="bar-chart" class="w-16 h-16 mx-auto mb-2"></i>
                    <p>Grafik arus kas akan ditampilkan di sini</p>
                    <p class="text-sm mt-1">Dapat dikembangkan dengan Chart.js</p>
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
        const tabs = ['operasional', 'investasi', 'pendanaan'];
        tabs.forEach(t => {
            document.getElementById('tab-' + t).classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50', 'bg-blue-50', 'bg-purple-50');
            document.getElementById('tab-' + t).classList.add('text-gray-600');
            document.getElementById('content-' + t).classList.add('hidden');
        });
        
        // Activate selected tab
        document.getElementById('tab-' + tab).classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]');
        document.getElementById('tab-' + tab).classList.remove('text-gray-600');
        
        // Add background color based on tab
        if (tab === 'operasional') {
            document.getElementById('tab-' + tab).classList.add('bg-green-50');
        } else if (tab === 'investasi') {
            document.getElementById('tab-' + tab).classList.add('bg-blue-50');
        } else if (tab === 'pendanaan') {
            document.getElementById('tab-' + tab).classList.add('bg-purple-50');
        }
        
        // Show selected content
        document.getElementById('content-' + tab).classList.remove('hidden');
    }
</script>

<style>
    @media print {
        .sidebar, form, button, a {
            display: none !important;
        }
        main {
            margin-left: 0 !important;
        }
    }
</style>
@endpush