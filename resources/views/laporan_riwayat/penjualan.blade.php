@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Penjualan</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex flex-wrap gap-4 mb-6">
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
                <select name="barang_id" onchange="this.form.submit()"
                    class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
                    <option value="">Semua Barang</option>
                    @foreach($daftarBarang as $id => $nama)
                        <option value="{{ $id }}" {{ request('barang_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('laporan.penjualan') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center transition">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-1"></i>
                Reset
            </a>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ $totalTransaksi }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i data-lucide="receipt" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Item Terjual</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ number_format($totalItem, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i data-lucide="shopping-bag" class="w-8 h-8 text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Penjualan</p>
                        <p class="text-xl font-bold text-[#173720]">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <i data-lucide="trending-up" class="w-8 h-8 text-orange-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pendapatan Bersih</p>
                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i data-lucide="wallet" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Diskon & Pajak -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-[#173720] mb-3">Ringkasan Perhitungan</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="border-r">
                    <p class="text-gray-600">Subtotal</p>
                    <p class="font-semibold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
                <div class="border-r">
                    <p class="text-gray-600">Diskon (5%)</p>
                    <p class="font-semibold text-red-600">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</p>
                </div>
                <div class="border-r">
                    <p class="text-gray-600">PPN (11%)</p>
                    <p class="font-semibold text-blue-600">+ Rp {{ number_format($totalPajak, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Total Bersih</p>
                    <p class="font-bold text-green-600 text-lg">Rp {{ number_format($totalBersih, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Tabel Penjualan -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-[#173720]">Detail Penjualan</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print
                    </button>
                    <a href="{{ route('laporan.cetak.penjualan', request()->all()) }}" 
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
                            <th class="py-3 px-4 text-left border-b">No</th>
                            <th class="py-3 px-4 text-left border-b">Tanggal</th>
                            <th class="py-3 px-4 text-left border-b">Invoice</th>
                            <th class="py-3 px-4 text-left border-b">Barang</th>
                            <th class="py-3 px-4 text-center border-b">Qty</th>
                            <th class="py-3 px-4 text-right border-b">Harga</th>
                            <th class="py-3 px-4 text-right border-b">Subtotal</th>
                            <th class="py-3 px-4 text-right border-b">Diskon</th>
                            <th class="py-3 px-4 text-right border-b">PPN</th>
                            <th class="py-3 px-4 text-right border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($penjualan as $index => $item)
                        @php
                            $subtotal = $item->jumlah;
                            $diskon = $subtotal * 0.05;
                            $ppn = ($subtotal - $diskon) * 0.11;
                            $total = $subtotal - $diskon + $ppn;
                        @endphp
                        <tr class="border-b hover:bg-[#f4faf5] transition">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="font-mono text-xs">INV-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div>
                                    <p class="font-semibold">{{ $item->nama_barang ?? 'Produk Umum' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->keterangan }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">{{ $item->qty ?? 1 }}</td>
                            <td class="py-3 px-4 text-right">
                                @if($item->qty)
                                    Rp {{ number_format($item->jumlah / $item->qty, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-red-600">Rp {{ number_format($diskon, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-blue-600">Rp {{ number_format($ppn, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                <p>Belum ada data penjualan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($penjualan->count() > 0)
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="6" class="py-3 px-4 text-right">TOTAL</td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-red-600">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-blue-600">Rp {{ number_format($totalPajak, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-[#173720]">Rp {{ number_format($totalBersih, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Grafik Penjualan (Optional) -->
            @if($penjualan->count() > 0)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-[#173720] mb-4">Top 5 Produk Terlaris</h3>
                <div class="space-y-2">
                    @php
                        $topProducts = $penjualan->groupBy('nama_barang')
                            ->map(function($group) {
                                return [
                                    'nama' => $group->first()->nama_barang ?? 'Produk Umum',
                                    'qty' => $group->sum('qty'),
                                    'total' => $group->sum('jumlah')
                                ];
                            })
                            ->sortByDesc('qty')
                            ->take(5);
                    @endphp
                    @foreach($topProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-semibold">{{ $product['nama'] }}</p>
                            <p class="text-sm text-gray-600">{{ $product['qty'] }} unit terjual</p>
                        </div>
                        <p class="font-semibold text-[#173720]">Rp {{ number_format($product['total'], 0, ',', '.') }}</p>
                    </div>
                    @endforeach
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