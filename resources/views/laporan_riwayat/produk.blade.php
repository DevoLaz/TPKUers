@extends('layouts.app')

@section('title', 'Laporan Keuntungan & Kerugian Produk')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Keuntungan & Kerugian Produk</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.produk') }}" class="flex flex-wrap gap-4 mb-6">
            <div class="inline-block">
                <select name="tahun" onchange="this.form.submit()"
                    class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($daftarTahun as $thn)
                        <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>

            @if($tahun)
            <div class="inline-block">
                <select name="bulan" onchange="this.form.submit()"
                    class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
                    <option value="">Semua Bulan</option>
                    @php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                            4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $namaBulan[$i] }}
                        </option>
                    @endfor
                </select>
            </div>
            @endif

            <div class="inline-block">
                <select name="kategori" onchange="this.form.submit()"
                    class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($daftarKategori as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('laporan.produk') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center transition">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-1"></i>
                Reset
            </a>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Modal</p>
                        <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalModalKeseluruhan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i data-lucide="shopping-cart" class="w-8 h-8 text-red-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-xl font-bold text-blue-600">Rp {{ number_format($totalPendapatanKeseluruhan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i data-lucide="trending-up" class="w-8 h-8 text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Profit/Loss</p>
                        <p class="text-xl font-bold {{ $totalProfitKeseluruhan >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($totalProfitKeseluruhan), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="{{ $totalProfitKeseluruhan >= 0 ? 'bg-green-100' : 'bg-red-100' }} p-3 rounded-lg">
                        <i data-lucide="{{ $totalProfitKeseluruhan >= 0 ? 'arrow-up-circle' : 'arrow-down-circle' }}" 
                           class="w-8 h-8 {{ $totalProfitKeseluruhan >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Margin Keseluruhan</p>
                        <p class="text-2xl font-bold {{ $marginKeseluruhan >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($marginKeseluruhan, 1) }}%
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i data-lucide="percent" class="w-8 h-8 text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Profitabilitas -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-[#173720]">Analisa Profitabilitas Produk</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print
                    </button>
                    <a href="{{ route('laporan.cetak.produk', request()->all()) }}" 
                       target="_blank" 
                       class="bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Cetak PDF
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-[#e0e0e0]">
                    <thead class="bg-[#dff2e1] text-[#173720]">
                        <tr>
                            <th rowspan="2" class="py-3 px-4 text-left border-b border-r">No</th>
                            <th rowspan="2" class="py-3 px-4 text-left border-b border-r">Produk</th>
                            <th colspan="2" class="py-2 px-4 text-center border-b border-r bg-red-50">Pembelian</th>
                            <th colspan="2" class="py-2 px-4 text-center border-b border-r bg-blue-50">Penjualan</th>
                            <th rowspan="2" class="py-3 px-4 text-right border-b border-r">Profit/Unit</th>
                            <th rowspan="2" class="py-3 px-4 text-right border-b border-r">Total Profit</th>
                            <th rowspan="2" class="py-3 px-4 text-center border-b border-r">Margin</th>
                            <th rowspan="2" class="py-3 px-4 text-center border-b">Status</th>
                        </tr>
                        <tr>
                            <th class="py-2 px-2 text-center border-b border-r text-xs bg-red-50">Qty</th>
                            <th class="py-2 px-2 text-right border-b border-r text-xs bg-red-50">Rata²/Unit</th>
                            <th class="py-2 px-2 text-center border-b border-r text-xs bg-blue-50">Qty</th>
                            <th class="py-2 px-2 text-right border-b border-r text-xs bg-blue-50">Rata²/Unit</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($barangs as $index => $barang)
                        <tr class="border-b hover:bg-[#f4faf5] transition">
                            <td class="py-3 px-4 border-r">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 border-r">
                                <div>
                                    <p class="font-semibold">{{ $barang->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $barang->kategori }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center border-r bg-red-50">{{ $barang->qty_pembelian }}</td>
                            <td class="py-3 px-4 text-right border-r bg-red-50">
                                Rp {{ number_format($barang->harga_beli_rata, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center border-r bg-blue-50">{{ $barang->qty_penjualan }}</td>
                            <td class="py-3 px-4 text-right border-r bg-blue-50">
                                Rp {{ number_format($barang->harga_jual_rata, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right border-r {{ $barang->profit_per_unit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($barang->profit_per_unit), 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right border-r font-semibold {{ $barang->total_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($barang->total_profit), 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center border-r">
                                <span class="font-semibold {{ $barang->margin >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($barang->margin, 1) }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($barang->status == 'profit')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">PROFIT</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">LOSS</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                <p>Belum ada data transaksi produk</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Top Profitable & Loss Products -->
            @if($barangs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Top Profitable -->
                <div>
                    <h3 class="text-lg font-semibold text-green-600 mb-3">
                        <i data-lucide="trending-up" class="w-5 h-5 inline mr-1"></i>
                        Top 5 Produk Menguntungkan
                    </h3>
                    <div class="space-y-2">
                        @foreach($barangs->where('total_profit', '>', 0)->take(5) as $product)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded">
                            <div>
                                <p class="font-semibold">{{ $product->nama }}</p>
                                <p class="text-sm text-gray-600">Margin: {{ number_format($product->margin, 1) }}%</p>
                            </div>
                            <p class="font-semibold text-green-600">+Rp {{ number_format($product->total_profit, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Loss -->
                <div>
                    <h3 class="text-lg font-semibold text-red-600 mb-3">
                        <i data-lucide="trending-down" class="w-5 h-5 inline mr-1"></i>
                        Produk Merugi
                    </h3>
                    <div class="space-y-2">
                        @forelse($barangs->where('total_profit', '<', 0)->take(5) as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded">
                            <div>
                                <p class="font-semibold">{{ $product->nama }}</p>
                                <p class="text-sm text-gray-600">Margin: {{ number_format($product->margin, 1) }}%</p>
                            </div>
                            <p class="font-semibold text-red-600">-Rp {{ number_format(abs($product->total_profit), 0, ',', '.') }}</p>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada produk yang merugi 🎉</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide-static@latest"></script>
<script>
    lucide.createIcons();
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