@extends('layouts.app')

@section('title', 'Laporan Persediaan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Persediaan</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.persediaan') }}" class="flex flex-wrap gap-4 mb-6">
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

            <a href="{{ route('laporan.persediaan') }}" 
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
                        <p class="text-sm text-gray-600">Total Item</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ $barangs->count() }}</p>
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

        <!-- Tabel Persediaan -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-[#173720]">Kartu Stok Barang</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print
                    </button>
                    <a href="{{ route('laporan.cetak.persediaan', request()->all()) }}" 
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
                            <th rowspan="2" class="py-3 px-4 text-left border-b border-r">Nama Barang</th>
                            <th rowspan="2" class="py-3 px-4 text-left border-b border-r">Kategori</th>
                            <th rowspan="2" class="py-3 px-4 text-right border-b border-r">Harga</th>
                            <th colspan="4" class="py-2 px-4 text-center border-b border-r">Mutasi Stok</th>
                            <th rowspan="2" class="py-3 px-4 text-right border-b">Nilai Persediaan</th>
                        </tr>
                        <tr>
                            <th class="py-2 px-2 text-center border-b border-r text-xs">Awal</th>
                            <th class="py-2 px-2 text-center border-b border-r text-xs bg-green-50">Masuk</th>
                            <th class="py-2 px-2 text-center border-b border-r text-xs bg-red-50">Keluar</th>
                            <th class="py-2 px-2 text-center border-b border-r text-xs">Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($barangs as $index => $barang)
                        <tr class="border-b hover:bg-[#f4faf5] transition">
                            <td class="py-3 px-4 border-r">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 border-r">{{ $barang->nama }}</td>
                            <td class="py-3 px-4 border-r">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($barang->kategori == 'ATK') bg-blue-100 text-blue-800
                                    @elseif($barang->kategori == 'Elektronik') bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $barang->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right border-r">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center border-r">{{ $barang->stok_awal }}</td>
                            <td class="py-3 px-4 text-center border-r bg-green-50">
                                @if($barang->stok_masuk > 0)
                                    <span class="text-green-600 font-semibold">+{{ $barang->stok_masuk }}</span>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center border-r bg-red-50">
                                @if($barang->stok_keluar > 0)
                                    <span class="text-red-600 font-semibold">-{{ $barang->stok_keluar }}</span>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center border-r font-semibold">{{ $barang->stok_akhir }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($barang->nilai_persediaan, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                <p>Belum ada data persediaan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($barangs->count() > 0)
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="7" class="py-3 px-4 text-right border-r">TOTAL</td>
                            <td class="py-3 px-4 text-center border-r">{{ number_format($totalStok, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-[#173720]">
                                Rp {{ number_format($totalNilai, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Alert Stok Rendah -->
            @php
                $stokRendah = $barangs->filter(function($b) { return $b->stok_akhir < 10; });
            @endphp
            @if($stokRendah->count() > 0)
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center gap-2 text-yellow-800">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    <span class="font-semibold">Peringatan Stok Rendah!</span>
                </div>
                <p class="mt-2 text-sm text-yellow-700">
                    Barang berikut memiliki stok kurang dari 10 unit:
                    @foreach($stokRendah as $item)
                        <span class="font-semibold">{{ $item->nama }} ({{ $item->stok_akhir }} unit)</span>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
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