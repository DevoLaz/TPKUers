@extends('layouts.app')

@section('title', 'Laporan Pengadaan Barang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Pengadaan Barang</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.pengadaan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <input type="text" name="nama" value="{{ request('nama') }}" 
                   placeholder="Cari Nama Barang..." 
                   class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition">
            
            <select name="kategori" class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition">
                <option value="">Semua Kategori</option>
                <option value="ATK" {{ request('kategori') == 'ATK' ? 'selected' : '' }}>ATK</option>
                <option value="Elektronik" {{ request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Peralatan" {{ request('kategori') == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
            </select>
            
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                   class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition"
                   placeholder="Dari Tanggal">
            
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                   class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition"
                   placeholder="Sampai Tanggal">
            
            <button type="submit" class="bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded transition">
                <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                Cari
            </button>
            
            <a href="{{ route('laporan.pengadaan') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center transition">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-1"></i>
                Reset
            </a>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Barang</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ $totalBarang }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Stok</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ number_format($totalStok, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i data-lucide="boxes" class="w-8 h-8 text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Nilai</p>
                        <p class="text-xl font-bold text-[#173720]">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <i data-lucide="wallet" class="w-8 h-8 text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Barang -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-[#173720]">Detail Barang</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print
                    </button>
                    <a href="{{ route('laporan.cetak.pengadaan', request()->all()) }}" 
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
                            <th class="py-3 px-4 text-left border-b">Nama Barang</th>
                            <th class="py-3 px-4 text-left border-b">Kategori</th>
                            <th class="py-3 px-4 text-right border-b">Harga</th>
                            <th class="py-3 px-4 text-center border-b">Stok</th>
                            <th class="py-3 px-4 text-right border-b">Total Nilai</th>
                            <th class="py-3 px-4 text-center border-b">Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($barangs as $index => $barang)
                        <tr class="border-b hover:bg-[#f4faf5] transition">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $barang->nama }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($barang->kategori == 'ATK') bg-blue-100 text-blue-800
                                    @elseif($barang->kategori == 'Elektronik') bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $barang->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">{{ $barang->stok }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($barang->harga * $barang->stok, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">{{ $barang->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                <p>Belum ada data barang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($barangs->count() > 0)
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-right">TOTAL</td>
                            <td class="py-3 px-4 text-center">{{ number_format($totalStok, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-[#173720]">
                                Rp {{ number_format($totalNilai, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Grafik Kategori (Optional) -->
        @if($barangs->count() > 0)
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-[#173720] mb-4">Distribusi Barang per Kategori</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $perKategori = $barangs->groupBy('kategori');
                @endphp
                @foreach($perKategori as $kategori => $items)
                <div class="text-center p-4 border rounded-lg">
                    <h3 class="font-semibold text-gray-700">{{ $kategori }}</h3>
                    <p class="text-2xl font-bold text-[#173720] mt-2">{{ $items->count() }}</p>
                    <p class="text-sm text-gray-500">Total: Rp {{ number_format($items->sum(function($item) { 
                        return $item->harga * $item->stok; 
                    }), 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
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