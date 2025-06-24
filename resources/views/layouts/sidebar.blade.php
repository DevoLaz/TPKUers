{{-- resources/views/layouts/sidebar.blade.php --}}

<style>
    /* Menyembunyikan scrollbar untuk browser berbasis WebKit (Chrome, Safari, Edge) */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    /* Menyembunyikan scrollbar untuk Firefox */
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<aside class="flex flex-col w-20 hover:w-64 bg-[#173720] text-white shadow-md border-r border-[#2a5132] fixed top-0 left-0 h-screen transition-all duration-300 overflow-hidden z-50 group/sidebar">
    
    <!-- Bagian Header (Tidak di-scroll) -->
    <div class="flex items-center gap-3 p-6 border-b border-[#2a5132] h-[69px] shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            {{-- <img src="/logo.png" alt="Logo" class="w-8 h-8 shrink-0"> --}}
            <span class="text-lg font-bold hidden group-hover/sidebar:inline-block transition-all duration-300 whitespace-nowrap">TPKU Finance</span>
        </a>
    </div>

    <!-- Area Navigasi (Bisa di-scroll) -->
    <div class="flex-1 overflow-y-auto no-scrollbar">
        <nav class="flex flex-col px-4 py-6 space-y-2 text-base">
            
            {{-- MENU UTAMA --}}
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('dashboard') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="home" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Dashboard</span>
            </a>
            
            <!-- Judul Grup Manajemen -->
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">MANAJEMEN DATA</span>
            </div>

            {{-- 🔥 MENU BARU UNTUK PENGADAAN BAHAN BAKU --}}
            <a href="{{ route('pengadaan.index') }}" 
               class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('pengadaan.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="shopping-basket" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Pengadaan Bahan</span>
            </a>

            <a href="{{ route('karyawan.index') }}" 
               class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('karyawan.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Kelola Karyawan</span>
            </a>

            <!-- <a href="{{ route('transaksi.index') }}"
               class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('transaksi.*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="shopping-cart" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Input Transaksi</span>
            </a> -->

            <!-- Judul Grup Laporan -->
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">LAPORAN AKUNTANSI</span>
            </div>
            
            {{-- MENU LAPORAN --}}
            <a href="{{ route('laporan.index') }}" 
               class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.index') || request()->routeIs('laporan.transaksi') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="book-copy" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Riwayat Transaksi</span>
            </a>
            <a href="{{ route('laporan.laba_rugi') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.laba_rugi') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laba Rugi</span>
            </a>
            <a href="{{ route('laporan.neraca') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.neraca') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="scale" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laporan Neraca</span>
            </a>
            <a href="{{ route('laporan.arus_kas') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.arus_kas') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="repeat" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Arus Kas</span>
            </a>
            <a href="{{ route('laporan.utang_piutang') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.utang_piutang*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="arrow-left-right" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Utang & Piutang</span>
            </a>
            <a href="{{ route('laporan.penggajian') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.penggajian*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="wallet" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Penggajian</span>
            </a>
            <a href="{{ route('laporan.perpajakan') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.perpajakan*') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="landmark" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Perpajakan</span>
            </a>
             
            <div class="px-2 pt-4 pb-1">
                <span class="text-xs font-bold text-gray-400 hidden group-hover/sidebar:inline-block">ANALISIS & DETAIL</span>
            </div>
            <a href="{{ route('laporan.penjualan') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.penjualan') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="trending-up" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Analisis Penjualan</span>
            </a>
            <a href="{{ route('laporan.produk') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.produk') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="package" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Analisis Produk</span>
            </a>
            <a href="{{ route('laporan.persediaan') }}" class="flex items-center gap-3 p-2 rounded-md transition {{ request()->routeIs('laporan.persediaan') ? 'bg-[#246342] font-semibold' : 'hover:bg-[#246342]' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap">Laporan Persediaan</span>
            </a>
        </nav>
    </div>

    <!-- Bagian Logout (Tidak di-scroll) -->
    <div class="p-4 border-t border-[#2a5132] shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full hover:bg-[#e74c3c] p-2 rounded-md text-white transition">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span class="hidden group-hover/sidebar:inline-block whitespace-nowrap font-semibold">Logout</span>
            </button>
        </form>
    </div>
</aside>
