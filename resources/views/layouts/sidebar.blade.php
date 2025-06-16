<!-- resources/views/partials/sidebar.blade.php -->

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
      <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 hover:bg-[#246342] p-2 rounded-md transition">
        <i data-lucide="printer" class="w-5 h-5 shrink-0"></i>
        <span class="hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">Cetak Riwayat Transaksi</span>
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
