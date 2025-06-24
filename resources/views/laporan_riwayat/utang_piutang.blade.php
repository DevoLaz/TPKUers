@extends('layouts.app')

@section('title', 'Laporan Utang Piutang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Laporan Utang & Piutang</h1>
                    <p class="text-green-100">Manajemen utang (payable) dan piutang (receivable) perusahaan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('laporan.utang_piutang.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Input Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-[#173720] rounded"></div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Laporan</h3>
            </div>
            
            <form method="GET" action="{{ route('laporan.utang_piutang') }}" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Dari Tanggal
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Sampai Tanggal
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                        Status
                    </label>
                    <select name="status" class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] hover:bg-[#2a5a37] text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Terapkan</span>
                    </button>
                    <a href="{{ route('laporan.utang_piutang') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Piutang</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full"><i data-lucide="trending-up" class="w-8 h-8 text-blue-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Utang</p>
                        <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalUtang ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full"><i data-lucide="trending-down" class="w-8 h-8 text-orange-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Sisa Piutang (Belum Lunas)</p>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($sisaPiutang ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full"><i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i></div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex border-b">
                <button onclick="switchTab('piutang')" id="tab-piutang" class="flex-1 px-6 py-4 font-semibold text-[#173720] border-b-2 border-[#173720] bg-green-50 transition-all">
                    <i data-lucide="arrow-down-circle" class="w-5 h-5 inline mr-2"></i> Piutang Usaha
                </button>
                <button onclick="switchTab('utang')" id="tab-utang" class="flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-[#173720] transition-all">
                    <i data-lucide="arrow-up-circle" class="w-5 h-5 inline mr-2"></i> Utang Usaha
                </button>
            </div>
            <div class="p-6">
                <div id="content-piutang" class="space-y-4">
                    @include('partials.laporan.tabel_utang_piutang', ['items' => $piutang, 'type' => 'piutang', 'title' => 'Piutang', 'headerColor' => 'bg-blue-50', 'headerTextColor' => 'text-blue-900', 'rowHoverColor' => 'hover:bg-blue-50', 'total' => $piutang->sum('jumlah'), 'totalColor' => 'text-blue-700', 'totalBgColor' => 'bg-blue-100'])
                </div>
                <div id="content-utang" class="space-y-4 hidden">
                    @include('partials.laporan.tabel_utang_piutang', ['items' => $utang, 'type' => 'utang', 'title' => 'Utang', 'headerColor' => 'bg-orange-50', 'headerTextColor' => 'text-orange-900', 'rowHoverColor' => 'hover:bg-orange-50', 'total' => $utang->sum('jumlah'), 'totalColor' => 'text-orange-700', 'totalBgColor' => 'bg-orange-100'])
                </div>
            </div>
             <div class="p-6 bg-gradient-to-r from-gray-600 to-gray-700">
                <div class="flex items-center justify-end text-white">
                    <a href="{{ route('laporan.cetak.utang_piutang', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/30 rounded-lg transition-all transform hover:scale-105 backdrop-blur">
                        <i data-lucide="printer" class="w-5 h-5"></i>
                        <span class="font-semibold">Cetak Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- POPUP / MODAL UNTUK DETAIL --}}
<div id="detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="modal-panel">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800" id="modal-title">Detail Transaksi</h2>
            <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500" id="modal-kontak-label">Pelanggan</p>
                    <p class="text-lg font-bold text-gray-900" id="modal-kontak">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">No. Invoice</p>
                    <p class="text-lg font-bold text-gray-900" id="modal-invoice">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Tanggal Transaksi</p>
                    <p class="text-lg text-gray-700" id="modal-tanggal">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Tanggal Jatuh Tempo</p>
                    <p class="text-lg text-gray-700" id="modal-jatuh-tempo">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-sm font-semibold text-gray-500">Akun</p>
                    <p class="text-lg text-gray-700" id="modal-akun">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-sm font-semibold text-gray-500">Keterangan</p>
                    <p class="text-base text-gray-700 bg-gray-50 p-3 rounded-lg" id="modal-keterangan">-</p>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Jumlah</p>
                    <p class="text-3xl font-extrabold text-[#173720]" id="modal-jumlah">Rp 0</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 text-right">Status</p>
                    <div id="modal-status-badge"></div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end">
            <button id="close-modal-btn-2" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();
    
    function switchTab(tab) {
        document.getElementById('tab-piutang').classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
        document.getElementById('tab-utang').classList.remove('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
        document.getElementById('content-piutang').classList.add('hidden');
        document.getElementById('content-utang').classList.add('hidden');
        if(tab === 'piutang') {
            document.getElementById('tab-piutang').classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
            document.getElementById('content-piutang').classList.remove('hidden');
        } else {
            document.getElementById('tab-utang').classList.add('border-b-2', 'border-[#173720]', 'text-[#173720]', 'bg-green-50');
            document.getElementById('content-utang').classList.remove('hidden');
        }
    }

    // JAVASCRIPT UNTUK POPUP/MODAL
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detail-modal');
        const modalPanel = document.getElementById('modal-panel');
        const openModalButtons = document.querySelectorAll('.open-detail-modal');
        const closeModalButtons = document.querySelectorAll('#close-modal-btn, #close-modal-btn-2');

        openModalButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const data = this.dataset;

                // Mengisi data ke modal
                document.getElementById('modal-title').textContent = 'Detail ' + (data.tipe.charAt(0).toUpperCase() + data.tipe.slice(1));
                document.getElementById('modal-kontak-label').textContent = data.tipe === 'piutang' ? 'Pelanggan' : 'Pemasok';
                document.getElementById('modal-kontak').textContent = data.kontak || '-';
                document.getElementById('modal-invoice').textContent = data.invoice || '-';
                document.getElementById('modal-tanggal').textContent = data.tanggal || '-';
                document.getElementById('modal-jatuh-tempo').textContent = data.jatuhTempo || '-';
                document.getElementById('modal-akun').textContent = data.akun || '-';
                document.getElementById('modal-keterangan').textContent = data.keterangan || 'Tidak ada keterangan.';
                document.getElementById('modal-jumlah').textContent = data.jumlah || 'Rp 0';
                
                const statusBadge = document.getElementById('modal-status-badge');
                // 🔥 FIXED: Logika badge disamakan dengan yang ada di tabel
                if (data.status === 'lunas') {
                    statusBadge.innerHTML = `<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Lunas</span>`;
                } else {
                    statusBadge.innerHTML = `<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Belum Lunas</span>`;
                }
                
                // Menampilkan modal dengan animasi
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modalPanel.classList.remove('scale-95', 'opacity-0');
                    modalPanel.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        });

        const hideModal = () => {
            modalPanel.classList.add('scale-95', 'opacity-0');
            modalPanel.classList.remove('scale-100', 'opacity-100');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        closeModalButtons.forEach(button => {
            button.addEventListener('click', hideModal);
        });

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideModal();
            }
        });
    });
</script>
@endpush
