@extends('layouts.app')
@section('title', 'Daftar Barang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')
    
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Daftar Barang</h1>
                    <p class="text-green-100">Kelola dan pantau semua barang dalam inventori</p>
                </div>
                {{-- Area Tombol di Header --}}
                <div class="flex items-center gap-3">
                    {{-- TOMBOL TAMBAH BARANG BARU (DIPINDAH KE SINI) --}}
                    <a href="{{ route('barang.create') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="package-plus" class="w-5 h-5"></i>
                        <span>Tambah Barang</span>
                    </a>
                    
                    {{-- TOMBOL TAMBAH PENGADAAN (STOK) --}}
                    <a href="{{ route('pengadaan.create') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Tambah Pengadaan</span>
                    </a>

                     <a href="{{ route('pengadaan.riwayat') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5"></i>
                <span>Riwayat Pengadaan</span>
            </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('pengadaan.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari barang..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <div class="min-w-[200px]">
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Kategori</option>
                        @php
                            $kategoris = ['BedUnit', 'Benang5000Y', 'KancingLubang4'];
                        @endphp
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Filter</span>
                </button>
                
                <a href="{{ route('pengadaan.index') }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    Reset
                </a>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#173720] text-white">
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">NO</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">KODE BARANG</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">NAMA BARANG</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">KATEGORI</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">UNIT</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">STOK</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">STATUS</th>
                            <th class="py-4 px-4 text-center text-sm font-bold uppercase">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangs as $index => $item)
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-4 text-center">{{ $loop->iteration }}</td>
                                <td class="py-4 px-4 text-center font-mono text-sm">{{ $item->kode_barang ?? 'N/A' }}</td>
                                <td class="py-4 px-4 font-medium">{{ $item->nama }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center text-gray-600">{{ $item->unit ?? 'pcs' }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold {{ $item->stok < 1000 ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ number_format($item->stok, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($item->stok > 0)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('barang.edit', $item->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors" 
                                       title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <i data-lucide="package" class="w-16 h-16 text-gray-300"></i>
                                        <p class="text-gray-500 text-lg">Belum ada data barang</p>
                                        <a href="{{ route('barang.create') }}" 
                                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                            Tambah Barang Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($barangs->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $barangs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- <div class="mt-6 flex gap-4">
            {{-- TOMBOL TAMBAH BARANG DIHAPUS DARI SINI --}}
            <a href="{{ route('pengadaan.riwayat') }}" 
               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5"></i>
                <span>Riwayat Pengadaan</span>
            </a>
        </div> -->
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Auto submit on category change
        const categorySelect = document.querySelector('select[name="kategori"]');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
</script>
@endpush