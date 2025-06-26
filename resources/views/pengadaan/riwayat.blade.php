@extends('layouts.app')
@section('title', 'Riwayat Pengadaan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')
    
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Riwayat Pengadaan</h1>
                    <p class="text-green-100">Histori semua pembelian dan pengadaan barang</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengadaan.create') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Tambah Pengadaan</span>
                    </a>
                    <a href="{{ route('pengadaan.index') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span>Daftar Barang</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('pengadaan.riwayat') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" 
                           name="dari" 
                           value="{{ request('dari') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" 
                           name="sampai" 
                           value="{{ request('sampai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <select name="barang_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>
                    
                    <a href="{{ route('pengadaan.riwayat') }}" 
                       class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        @if($pengadaans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Transaksi</p>
                        <p class="text-3xl font-bold">{{ $pengadaans->total() }}</p>
                    </div>
                    <i data-lucide="shopping-cart" class="w-12 h-12 text-blue-200"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Pengeluaran</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($pengadaans->sum('total_harga'), 0, ',', '.') }}</p>
                    </div>
                    <i data-lucide="dollar-sign" class="w-12 h-12 text-green-200"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Item Masuk</p>
                        <p class="text-3xl font-bold">{{ number_format($pengadaans->sum('jumlah_masuk'), 0, ',', '.') }}</p>
                    </div>
                    <i data-lucide="package" class="w-12 h-12 text-purple-200"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Rata-rata/Transaksi</p>
                        <p class="text-xl font-bold">Rp {{ number_format($pengadaans->avg('total_harga'), 0, ',', '.') }}</p>
                    </div>
                    <i data-lucide="trending-up" class="w-12 h-12 text-orange-200"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#173720] text-white">
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Tanggal</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">No Invoice</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Barang</th>
                            <th class="py-4 px-4 text-left text-sm font-bold uppercase">Supplier</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">Jumlah</th>
                            <th class="py-4 px-4 text-right text-sm font-bold uppercase">Harga Beli</th>
                            <th class="py-4 px-4 text-right text-sm font-bold uppercase">Total</th>
                            <!-- <th class="py-4 px-4 text-center text-sm font-bold uppercase">Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengadaans as $item)
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400 mr-2"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                        {{ $item->no_invoice }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->barang->nama ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->barang->kode_barang ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->supplier->nama_supplier ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->supplier->kontak_person ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                        {{ number_format($item->jumlah_masuk, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-medium">
                                    Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="font-bold text-green-600 text-lg">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <!-- <td class="py-4 px-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('pengadaan.edit', $item->id) }}" 
                                           class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors" 
                                           title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('pengadaan.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus pengadaan ini? Stok barang akan dikurangi!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" 
                                                    title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> -->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-4">
                                        <i data-lucide="inbox" class="w-16 h-16 text-gray-300"></i>
                                        <p class="text-gray-500 text-lg">Belum ada riwayat pengadaan</p>
                                        <a href="{{ route('pengadaan.create') }}" 
                                           class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                                            <span>Tambah Pengadaan Pertama</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengadaans->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $pengadaans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endpush