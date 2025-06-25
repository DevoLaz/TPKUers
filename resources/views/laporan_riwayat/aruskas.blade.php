@extends('layouts.app')

@section('title', 'Laporan Arus Kas')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <!-- Header -->
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

            @if($tahun || $bulan)
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Menampilkan arus kas untuk: 
                    <strong>
                        @if($bulan && isset($namaBulan[$bulan]))
                            {{ $namaBulan[$bulan] }}
                        @endif
                        {{ $tahun ?? 'Semua Periode' }}
                    </strong>
                </p>
            </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Saldo Awal, Kas Masuk, Kas Keluar, Saldo Akhir -->
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
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $saldoAkhir >= 0 ? 'border-purple-500' : 'border-orange-500' }} hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Saldo Akhir</p>
                        <p class="text-2xl font-bold {{ $saldoAkhir >= 0 ? 'text-purple-600' : 'text-orange-600' }}">
                            Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $saldoAkhir >= $saldoAwal ? 'Surplus' : 'Defisit' }} {{ number_format(abs($saldoAkhir - $saldoAwal), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 {{ $saldoAkhir >= 0 ? 'bg-purple-100' : 'bg-orange-100' }} rounded-full">
                        <i data-lucide="{{ $saldoAkhir >= $saldoAwal ? 'trending-up' : 'trending-down' }}" 
                           class="w-8 h-8 {{ $saldoAkhir >= 0 ? 'text-purple-600' : 'text-orange-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Chart -->
        <div class="mt-6 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-[#173720] mb-4">
                <i data-lucide="bar-chart-3" class="w-5 h-5 inline mr-2"></i>
                Grafik Arus Kas Tahun {{ $tahun }}
            </h3>
            <div class="h-80">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>

        <!-- Detail Arus Kas -->
        <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b">
                <button onclick="switchTab('operasional')" id="tab-operasional" class="flex-1 px-6 py-4 font-semibold text-[#173720] border-b-2 border-[#173720] bg-green-50 transition-all">
                    <i data-lucide="briefcase" class="w-5 h-5 inline mr-2"></i> Aktivitas Operasional
                </button>
                <button onclick="switchTab('investasi')" id="tab-investasi" class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="trending-up" class="w-5 h-5 inline mr-2"></i> Aktivitas Investasi
                </button>
                <button onclick="switchTab('pendanaan')" id="tab-pendanaan" class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="dollar-sign" class="w-5 h-5 inline mr-2"></i> Aktivitas Pendanaan
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- ====================================================== -->
                <!-- 🔥🔥🔥 KODE TABEL SEKARANG ADA DI DALAM SINI 🔥🔥🔥 -->
                <!-- ====================================================== -->

                <!-- Aktivitas Operasional Tab -->
                <div id="content-operasional" class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Operasional</h3>
                    <!-- Kas Masuk Operasional -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-green-700 mb-3"><i data-lucide="plus-circle" class="w-5 h-5 inline mr-1"></i>Kas Masuk</h4>
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
                                        <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        <td class="py-3 px-4">{{ $item->keterangan }}</td>
                                        <td class="py-3 px-4 text-right font-semibold text-green-600">+Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="py-4 text-center text-gray-500">Tidak ada kas masuk dari aktivitas operasional</td></tr>
                                    @endforelse
                                </tbody>
                                @if(count($operasionalMasuk) > 0)
                                <tfoot>
                                    <tr class="bg-green-100 font-bold"><td colspan="2" class="py-3 px-4">Subtotal Kas Masuk</td><td class="py-3 px-4 text-right text-green-700">+Rp {{ number_format($operasionalMasuk->sum('jumlah'), 0, ',', '.') }}</td></tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    <!-- Kas Keluar Operasional -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-red-700 mb-3"><i data-lucide="minus-circle" class="w-5 h-5 inline mr-1"></i>Kas Keluar</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-red-50 border-b"><th class="py-3 px-4 text-left text-sm font-semibold text-red-900">Tanggal</th><th class="py-3 px-4 text-left text-sm font-semibold text-red-900">Keterangan</th><th class="py-3 px-4 text-right text-sm font-semibold text-red-900">Jumlah</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($operasionalKeluar as $item)
                                    <tr class="hover:bg-red-50 border-b transition-colors"><td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td><td class="py-3 px-4">{{ $item->keterangan }}</td><td class="py-3 px-4 text-right font-semibold text-red-600">-Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td></tr>
                                    @empty
                                    <tr><td colspan="3" class="py-4 text-center text-gray-500">Tidak ada kas keluar dari aktivitas operasional</td></tr>
                                    @endforelse
                                </tbody>
                                @if(count($operasionalKeluar) > 0)
                                <tfoot>
                                    <tr class="bg-red-100 font-bold"><td colspan="2" class="py-3 px-4">Subtotal Kas Keluar</td><td class="py-3 px-4 text-right text-red-700">-Rp {{ number_format($operasionalKeluar->sum('jumlah'), 0, ',', '.') }}</td></tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    <!-- Net Operasional -->
                    @php $netOperasional = $operasionalMasuk->sum('jumlah') - $operasionalKeluar->sum('jumlah'); @endphp
                    <div class="p-4 rounded-lg {{ $netOperasional >= 0 ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                        <div class="flex justify-between items-center"><span class="font-semibold">Arus Kas Bersih dari Aktivitas Operasional</span><span class="text-xl font-bold">{{ $netOperasional >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netOperasional), 0, ',', '.') }}</span></div>
                    </div>
                </div>

                <!-- Aktivitas Investasi Tab -->
                <div id="content-investasi" class="space-y-4 hidden">
                    <h3 class="text-lg font-semibold text-[#173720] mb-4">Arus Kas dari Aktivitas Investasi</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-blue-50 border-b"><th class="py-3 px-4 text-left text-sm font-semibold text-blue-900">Tanggal</th><th class="py-3 px-4 text-left text-sm font-semibold text-blue-900">Keterangan</th><th class="py-3 px-4 text-center text-sm font-semibold text-blue-900">Jenis</th><th class="py-3 px-4 text-right text-sm font-semibold text-blue-900">Jumlah</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($investasi as $item)
                                <tr class="hover:bg-blue-50 border-b transition-colors"><td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td><td class="py-3 px-4">{{ $item->keterangan }}</td><td class="py-3 px-4 text-center">@if($item->jenis == 'masuk')<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Penjualan Aset</span>@else<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Pembelian Aset</span>@endif</td><td class="py-3 px-4 text-right font-semibold {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">{{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td></tr>
                                @empty
                                <tr><td colspan="4" class="py-8 text-center text-gray-500"><i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i><p>Tidak ada aktivitas investasi pada periode ini</p></td></tr>
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
                                <tr class="bg-purple-50 border-b"><th class="py-3 px-4 text-left text-sm font-semibold text-purple-900">Tanggal</th><th class="py-3 px-4 text-left text-sm font-semibold text-purple-900">Keterangan</th><th class="py-3 px-4 text-center text-sm font-semibold text-purple-900">Jenis</th><th class="py-3 px-4 text-right text-sm font-semibold text-purple-900">Jumlah</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($pendanaan as $item)
                                <tr class="hover:bg-purple-50 border-b transition-colors"><td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td><td class="py-3 px-4">{{ $item->keterangan }}</td><td class="py-3 px-4 text-center">@if($item->jenis == 'masuk')<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Modal/Pinjaman</span>@else<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Pembayaran</span>@endif</td><td class="py-3 px-4 text-right font-semibold {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">{{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td></tr>
                                @empty
                                <tr><td colspan="4" class="py-8 text-center text-gray-500"><i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i><p>Tidak ada aktivitas pendanaan pada periode ini</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
{{-- Library Chart.js dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    lucide.createIcons();
    
    // Tab switching
    function switchTab(tab) {
        // Reset all tabs
        const tabs = ['operasional', 'investasi', 'pendanaan'];
        tabs.forEach(t => {
            document.getElementById('tab-' + t).classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
            document.getElementById('content-' + t).classList.add('hidden');
        });
        
        document.getElementById('tab-operasional').classList.remove('bg-green-50');
        document.getElementById('tab-investasi').classList.remove('bg-blue-50');
        document.getElementById('tab-pendanaan').classList.remove('bg-purple-50');
        
        // Activate selected tab
        const tabElement = document.getElementById('tab-' + tab);
        tabElement.classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]');
        if (tab === 'operasional') tabElement.classList.add('bg-green-50');
        if (tab === 'investasi') tabElement.classList.add('bg-blue-50');
        if (tab === 'pendanaan') tabElement.classList.add('bg-purple-50');

        // Show selected content
        document.getElementById('content-' + tab).classList.remove('hidden');
    }

    // Script Grafik Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const cashFlowCtx = document.getElementById('cashFlowChart');
        if (cashFlowCtx) {
            const cashFlowData = JSON.parse('{!! $dataGrafikJson !!}');
            
            const labels = cashFlowData.map(d => d.bulan);
            const kasMasukData = cashFlowData.map(d => d.kas_masuk);
            const kasKeluarData = cashFlowData.map(d => d.kas_keluar);
            const arusKasBersihData = cashFlowData.map(d => d.arus_kas_bersih);

            new Chart(cashFlowCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { type: 'line', label: 'Arus Kas Bersih', data: arusKasBersihData, borderColor: '#8b5cf6', backgroundColor: 'rgba(139, 92, 246, 0.1)', tension: 0.4, fill: true, yAxisID: 'y', order: 0, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#8b5cf6', },
                        { type: 'bar', label: 'Kas Masuk', data: kasMasukData, backgroundColor: 'rgba(34, 197, 94, 0.7)', borderColor: '#16a34a', borderWidth: 1, borderRadius: 4, yAxisID: 'y', order: 1, },
                        { type: 'bar', label: 'Kas Keluar', data: kasKeluarData, backgroundColor: 'rgba(239, 68, 68, 0.7)', borderColor: '#dc2626', borderWidth: 1, borderRadius: 4, yAxisID: 'y', order: 2, }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false, }, stacked: false,
                    plugins: {
                        title: { display: true, text: 'Arus Kas Bulanan Tahun {{ $tahun }}', font: { size: 16 } },
                        legend: { position: 'bottom', },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) { label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y); }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear', display: true, position: 'left',
                            ticks: {
                                callback: function(value, index, values) {
                                    if (Math.abs(value) >= 1000000) { return 'Rp ' + (value / 1000000) + ' Jt'; }
                                    if (Math.abs(value) >= 1000) { return 'Rp ' + (value / 1000) + ' Rb'; }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    });

</script>
@endpush
