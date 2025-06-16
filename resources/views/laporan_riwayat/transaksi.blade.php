@extends('layouts.app')

@section('title', 'Laporan Transaksi Harian/Bulanan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Transaksi Harian/Bulanan</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('laporan.transaksi') }}" class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Pilih Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode" onchange="togglePeriodeFilter(this.value)" 
                        class="w-full pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 bg-white">
                        <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <!-- Filter Harian -->
                <div id="filter-harian" class="{{ $periode == 'harian' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Filter Bulanan -->
                <div id="filter-bulanan" class="{{ $periode == 'bulanan' ? '' : 'hidden' }} grid grid-cols-2 gap-2 col-span-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" class="w-full pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 bg-white">
                            @foreach($daftarTahun as $thn)
                                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" class="w-full pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 bg-white">
                            @php
                                $namaBulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded transition">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                        Tampilkan
                    </button>
                    <a href="{{ route('laporan.transaksi') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center transition">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-bold text-[#173720]">{{ $transaksi->count() }}</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <i data-lucide="receipt" class="w-8 h-8 text-gray-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Masuk</p>
                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i data-lucide="arrow-down-circle" class="w-8 h-8 text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Keluar</p>
                        <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i data-lucide="arrow-up-circle" class="w-8 h-8 text-red-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Saldo</p>
                        <p class="text-xl font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($saldo), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="{{ $saldo >= 0 ? 'bg-blue-100' : 'bg-red-100' }} p-3 rounded-lg">
                        <i data-lucide="wallet" class="w-8 h-8 {{ $saldo >= 0 ? 'text-blue-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Harian (untuk periode bulanan) -->
        @if($periode == 'bulanan' && $rekapHarian->count() > 0)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-[#173720] mb-4">Rekap Harian - {{ $judulPeriode }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-[#e0e0e0]">
                    <thead class="bg-[#dff2e1] text-[#173720]">
                        <tr>
                            <th class="py-2 px-4 text-left border-b">Tanggal</th>
                            <th class="py-2 px-4 text-center border-b">Jumlah Transaksi</th>
                            <th class="py-2 px-4 text-right border-b">Total Masuk</th>
                            <th class="py-2 px-4 text-right border-b">Total Keluar</th>
                            <th class="py-2 px-4 text-right border-b">Saldo Harian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapHarian as $rekap)
                        @php
                            $saldoHarian = $rekap['total_masuk'] - $rekap['total_keluar'];
                        @endphp
                        <tr class="border-b hover:bg-[#f4faf5]">
                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($rekap['tanggal'])->format('d F Y') }}</td>
                            <td class="py-2 px-4 text-center">{{ $rekap['jumlah_transaksi'] }}</td>
                            <td class="py-2 px-4 text-right text-green-600">Rp {{ number_format($rekap['total_masuk'], 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-right text-red-600">Rp {{ number_format($rekap['total_keluar'], 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-right font-semibold {{ $saldoHarian >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($saldoHarian), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Detail Transaksi -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-[#173720]">Detail Transaksi - {{ $judulPeriode }}</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow flex items-center gap-2 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print
                    </button>
                    <a href="{{ route('laporan.cetak.transaksi', request()->all()) }}" 
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
                            <th class="py-3 px-4 text-left border-b">Tanggal & Waktu</th>
                            <th class="py-3 px-4 text-center border-b">Jenis</th>
                            <th class="py-3 px-4 text-left border-b">Kategori</th>
                            <th class="py-3 px-4 text-left border-b">Keterangan</th>
                            <th class="py-3 px-4 text-left border-b">Barang</th>
                            <th class="py-3 px-4 text-center border-b">Qty</th>
                            <th class="py-3 px-4 text-right border-b">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @php $saldoBerjalan = 0; @endphp
                        @forelse($transaksi as $index => $item)
                        @php
                            if($item->jenis == 'masuk') {
                                $saldoBerjalan += $item->jumlah;
                            } else {
                                $saldoBerjalan -= $item->jumlah;
                            }
                        @endphp
                        <tr class="border-b hover:bg-[#f4faf5] transition">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">
                                <div>
                                    <p>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->jenis == 'masuk')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">MASUK</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">KELUAR</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="capitalize">{{ $item->kategori ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4">{{ $item->keterangan }}</td>
                            <td class="py-3 px-4">{{ $item->nama_barang ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">{{ $item->qty ?? '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                <div>
                                    <p class="font-semibold {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">Saldo: Rp {{ number_format($saldoBerjalan, 0, ',', '.') }}</p>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                                <p>Tidak ada transaksi pada periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($transaksi->count() > 0)
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="7" class="py-3 px-4 text-right">TOTAL</td>
                            <td class="py-3 px-4 text-right">
                                <div>
                                    <p class="text-green-600">Masuk: Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                                    <p class="text-red-600">Keluar: Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                                    <p class="text-[#173720] text-lg">Saldo: Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide-static@latest"></script>
<script>
    lucide.createIcons();
    
    function togglePeriodeFilter(value) {
        const filterHarian = document.getElementById('filter-harian');
        const filterBulanan = document.getElementById('filter-bulanan');
        
        if (value === 'harian') {
            filterHarian.classList.remove('hidden');
            filterBulanan.classList.add('hidden');
        } else {
            filterHarian.classList.add('hidden');
            filterBulanan.classList.remove('hidden');
        }
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