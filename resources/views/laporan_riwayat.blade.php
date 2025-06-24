@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
  <!-- Sidebar -->
  <aside class="flex flex-col justify-between w-20 hover:w-64 bg-[#173720] text-white shadow-md border-r border-[#2a5132] fixed top-0 left-0 h-screen transition-all duration-300 overflow-hidden z-50 group/sidebar">
    <div>
      <div class="flex items-center gap-3 p-6 border-b border-[#2a5132]">
        <span class="text-lg font-bold hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">TPKU Finance App</span>
      </div>
      <nav class="flex flex-col px-4 py-6 space-y-4 text-base">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:bg-[#246342] p-2 rounded-md transition">
          <i data-lucide="home" class="w-5 h-5 shrink-0"></i>
          <span class="hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">Dashboard</span>
        </a>
        <a href="{{ route('barang.index') }}" class="flex items-center gap-3 hover:bg-[#246342] p-2 rounded-md transition">
          <i data-lucide="square-plus" class="w-5 h-5 shrink-0"></i>
          <span class="hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">Pengadaan Barang</span>
        </a>
        <a href="{{ route('riwayat.index') }}" class="flex items-center gap-3 hover:bg-[#246342] p-2 rounded-md transition">
          <i data-lucide="history" class="w-5 h-5 shrink-0"></i>
          <span class="hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">Riwayat Transaksi</span>
        </a>
      </nav>
    </div>
    <div class="relative p-4 border-t border-[#2a5132]">
      <form method="POST" action="{{ route('logout') }}" class="absolute bottom-4 left-4 right-4">
        @csrf
        <button type="submit" class="flex items-center gap-3 w-full bg-[#c0392b] hover:bg-[#e74c3c] p-2 rounded-md text-white text-sm transition">
          <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
          <span class="hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">Logout</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-[#173720]">Riwayat Transaksi</h1>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg p-6">
      <table class="min-w-full text-sm border border-[#e0e0e0]">
        <thead class="bg-[#dff2e1] text-[#173720]">
          <tr>
            <th class="py-2 px-4 text-left">No</th>
            <th class="py-2 px-4 text-left">Tanggal</th>
            <th class="py-2 px-4 text-left">Deskripsi</th>
            <th class="py-2 px-4 text-left">Jumlah</th>
            <th class="py-2 px-4 text-left">Status</th>
          </tr>
        </thead>
        <tbody class="text-gray-800">
          @forelse($transactions as $transaction)
            <tr class="border-t border-gray-200 hover:bg-[#f4faf5]">
              <td class="py-2 px-4">{{ $loop->iteration }}</td>
              <td class="py-2 px-4">{{ $transaction->date }}</td>
              <td class="py-2 px-4">{{ $transaction->description }}</td>
              <td class="py-2 px-4">{{ number_format($transaction->amount, 2) }}</td>
              <td class="py-2 px-4">{{ $transaction->status }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada riwayat transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  lucide.createIcons();
</script>
@endpush
