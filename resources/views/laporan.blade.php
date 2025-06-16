@extends('layouts.app')

@section('title', 'Cetak Laporan Keuangan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
@include('layouts.sidebar')
  <!-- Main -->
  <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-[#173720]">Cek & Cetak Riwayat Transaksi Keuangan</h1>
      <p class="text-gray-700 mt-2">Pilih jenis Riwayat laporan yang ingin dicetak.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      @php
        $laporan = [
          ['label' => 'Laporan Laba Rugi', 'desc' => 'Menunjukkan pendapatan, biaya, dan laba/rugi untuk periode tertentu.', 'route' => route('laporan.laba_rugi')],
          ['label' => 'Laporan Neraca', 'desc' => 'Menampilkan posisi aset, kewajiban, dan ekuitas pada tanggal tertentu.', 'route' => route('laporan.neraca')],
          ['label' => 'Laporan Arus Kas', 'desc' => 'Melacak kas masuk dan keluar untuk menganalisa arus keuangan.', 'route' => route('laporan.arus_kas')],
          ['label' => 'Laporan Pengadaan Barang', 'desc' => 'Daftar pembelian barang: item, harga, tanggal, supplier.', 'route' => route('laporan.pengadaan')],
          ['label' => 'Laporan Penjualan', 'desc' => 'Rangkuman penjualan produk/jasa beserta diskon dan pajak.', 'route' => route('laporan.penjualan')],
          ['label' => 'Laporan Persediaan', 'desc' => 'Stok masuk, keluar, dan sisa barang secara lengkap.', 'route' => route('laporan.persediaan')],
          ['label' => 'Laporan Keuntungan & Kerugian Produk', 'desc' => 'Analisa profitabilitas masing-masing produk.', 'route' => route('laporan.produk')],
          ['label' => 'Laporan Transaksi Harian/Bulanan', 'desc' => 'Rekap seluruh transaksi keuangan berdasarkan tanggal.', 'route' => route('laporan.transaksi')],
        ];
      @endphp

      @foreach ($laporan as $item)
      <a href="{{ $item['route'] }}" class="bg-green-100 text-[#173720] p-6 rounded-xl shadow hover:bg-green-200 transition flex flex-col justify-between">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-lg font-semibold">{{ $item['label'] }}</h2>
          <i data-lucide="printer" class="w-5 h-5"></i>
        </div>
        <p class="text-sm text-gray-600">{{ $item['desc'] }}</p>
      </a>
      @endforeach
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  lucide.createIcons();
</script>
@endpush
