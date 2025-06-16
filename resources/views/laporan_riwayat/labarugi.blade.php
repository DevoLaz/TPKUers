@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        <h1 class="text-3xl font-bold text-[#173720] mb-4">Laporan Laba Rugi</h1>

        <form method="GET" action="{{ route('laporan.laba_rugi') }}" class="flex flex-wrap gap-4 mb-6">

    <div class="inline-block">
        <select name="tahun" onchange="this.form.submit()"
            class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
            <option value="">Pilih Tahun</option>
            @foreach($daftarTahun as $thn)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
            @endforeach
        </select>
    </div>

    @if($tahun)
    <div class="inline-block">
        <select name="bulan" onchange="this.form.submit()"
            class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
            <option value="">Pilih Bulan</option>
            @foreach($daftarBulan as $bln)
                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>Bulan {{ $bln }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if($tahun && $bulan)
    <div class="inline-block">
        <select name="tanggal" onchange="this.form.submit()"
            class="pl-4 pr-8 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition bg-white">
            <option value="">Pilih Tanggal</option>
            @foreach($daftarTanggal as $tgl)
                <option value="{{ $tgl }}" {{ request('tanggal') == $tgl ? 'selected' : '' }}>Tanggal {{ $tgl }}</option>
            @endforeach
        </select>
    </div>
    @endif
</form>

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div>
    <h2 class="text-lg font-semibold text-[#173720] mb-2">Pendapatan</h2>
    <div class="text-sm border border-[#e0e0e0] rounded-lg overflow-hidden">
        <div class="grid grid-cols-2 bg-[#dff2e1] text-[#173720] font-bold">
            <div class="py-2 px-4 text-left">Sumber</div> 
            <div class="py-2 px-4 text-left">Jumlah</div>
        </div>
        <div>
            @forelse ($pendapatan as $item)
                <div class="grid grid-cols-2 border-t border-gray-200">
                    <div class="py-2 px-4">{{ $item->keterangan ?? '—' }}</div>
                    <div class="py-2 px-4 text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="py-2 px-4 text-center text-gray-500 border-t border-gray-200">Belum ada data pendapatan</div>
            @endforelse
            @if(count($pendapatan) > 0)
                <div class="grid grid-cols-2 font-bold border-t border-gray-300">
                    <div class="py-2 px-4">Total Pendapatan</div>
                    <div class="py-2 px-4 text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

<div>
    <h2 class="text-lg font-semibold text-[#a94442] mb-2">Pengeluaran</h2>
    <div class="text-sm border border-[#e0e0e0] rounded-lg overflow-hidden">
        <div class="grid grid-cols-2 bg-[#fcefe9] text-[#a94442] font-bold">
            <div class="py-2 px-4 text-left">Jenis Beban</div>
            <div class="py-2 px-4 text-left">Jumlah</div>
        </div>
        <div>
            @forelse ($pengeluaran as $item)
                <div class="grid grid-cols-2 border-t border-gray-200">
                    <div class="py-2 px-4">{{ $item->keterangan ?? '—' }}</div>
                    <div class="py-2 px-4 text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="py-2 px-4 text-center text-gray-500 border-t border-gray-200">Belum ada data pengeluaran</div>
            @endforelse
            @if(count($pengeluaran) > 0)
                <div class="grid grid-cols-2 font-bold border-t border-gray-300 text-[#a94442]">
                    <div class="py-2 px-4">Total Pengeluaran</div>
                    <div class="py-2 px-4 text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

            @php
                $selisih = $totalPendapatan - $totalPengeluaran;
            @endphp
            <div class="p-4 rounded-xl {{ $selisih >= 0 ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-800' }} font-bold text-lg">
                {{ $selisih >= 0 ? 'Laba Bersih' : 'Kerugian Bersih' }}:
                Rp {{ number_format(abs($selisih), 0, ',', '.') }}
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('laporan.cetak.laba_rugi') }}" target="_blank" class="bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    Cetak PDF
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<style>
    /* CSS Kustom Dihapus */
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide-static@latest"></script>
<script>
    lucide.createIcons();
</script>
@endpush