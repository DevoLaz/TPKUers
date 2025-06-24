@extends('layouts.app')

@section('title', 'Laporan Perpajakan')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')

    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Laporan Perpajakan</h1>
                    <p class="text-green-100">Ringkasan kewajiban pajak perusahaan (PPh & PPN).</p>
                </div>
                <a href="{{ route('laporan.perpajakan.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Catat Pajak Baru</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
             <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-[#173720] rounded"></div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
            </div>
            <form method="GET" action="{{ route('laporan.perpajakan') }}" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i> Periode
                    </label>
                    <input type="month" name="periode" value="{{ request('periode', date('Y-m')) }}"
                           class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#173720] transition-all bg-white shadow-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#173720] hover:bg-[#2a5a37] text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Tampilkan</span>
                    </button>
                    <a href="{{ route('laporan.perpajakan') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all transform hover:scale-105 shadow-md flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Asumsi dari controller: $totalPphTerutang, $totalPpnDisetor, $totalPajakDisetor --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total PPh Terutang</p>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPphTerutang ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full"><i data-lucide="file-text" class="w-8 h-8 text-red-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">PPN Kurang/Lebih Bayar</p>
                        <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($totalPpnDisetor ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full"><i data-lucide="landmark" class="w-8 h-8 text-indigo-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pajak Disetor</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPajakDisetor ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full"><i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i></div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto p-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Jenis Pajak</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">No. Referensi</th>
                            <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700">DPP</th>
                            <th class="py-3 px-4 text-right text-sm font-semibold text-gray-700">Jumlah Pajak</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Status</th>
                            {{-- 🔥 Kolom Aksi Ditambahkan --}}
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pajaks as $pajak)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($pajak->tanggal_transaksi)->isoFormat('DD MMM YYYY') }}</td>
                                <td class="py-3 px-4 font-medium">{{ $pajak->jenis_pajak }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $pajak->no_referensi ?? '-' }}</td>
                                <td class="py-3 px-4 text-right text-gray-600">Rp {{ number_format($pajak->dasar_pengenaan_pajak, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right font-semibold text-[#173720]">Rp {{ number_format($pajak->jumlah_pajak, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if($pajak->status == 'sudah_dibayar')
                                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Dibayar</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Belum Dibayar</span>
                                    @endif
                                </td>
                                {{-- 🔥 Tombol untuk membuka popup detail --}}
                                <td class="py-3 px-4 text-center">
                                    <button 
                                        class="open-detail-modal text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-100 rounded-full transition"
                                        data-jenis="{{ $pajak->jenis_pajak }}"
                                        data-referensi="{{ $pajak->no_referensi }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($pajak->tanggal_transaksi)->isoFormat('DD MMMM YYYY') }}"
                                        data-dpp="Rp {{ number_format($pajak->dasar_pengenaan_pajak, 0, ',', '.') }}"
                                        data-tarif="{{ $pajak->tarif_pajak }}%"
                                        data-jumlah="Rp {{ number_format($pajak->jumlah_pajak, 0, ',', '.') }}"
                                        data-keterangan="{{ $pajak->keterangan }}"
                                        data-status="{{ $pajak->status }}">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                    <i data-lucide="file-text" class="w-12 h-12 mx-auto text-gray-300"></i>
                                    <p class="mt-2">Belum ada data perpajakan yang dicatat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="p-6 bg-gradient-to-r from-gray-600 to-gray-700">
                <div class="flex items-center justify-end text-white">
                    <a href="{{ route('laporan.cetak.perpajakan', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/30 rounded-lg transition-all transform hover:scale-105 backdrop-blur">
                        <i data-lucide="download" class="w-5 h-5"></i>
                        <span class="font-semibold">Ekspor Laporan Pajak</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- 🔥 POPUP / MODAL UNTUK DETAIL PAJAK --}}
<div id="detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="modal-panel">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800" id="modal-title">Detail Pajak</h2>
            <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Jenis Pajak</p>
                    <p class="text-lg font-bold text-gray-900" id="modal-jenis">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">No. Referensi</p>
                    <p class="text-lg font-bold text-gray-900" id="modal-referensi">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Tanggal Transaksi</p>
                    <p class="text-lg text-gray-700" id="modal-tanggal">-</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Tarif Pajak</p>
                    <p class="text-lg text-gray-700" id="modal-tarif">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-sm font-semibold text-gray-500">Dasar Pengenaan Pajak (DPP)</p>
                    <p class="text-lg text-gray-700" id="modal-dpp">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-sm font-semibold text-gray-500">Keterangan</p>
                    <p class="text-base text-gray-700 bg-gray-50 p-3 rounded-lg" id="modal-keterangan">-</p>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Jumlah Pajak</p>
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

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('detail-modal');
        const modalPanel = document.getElementById('modal-panel');
        const openModalButtons = document.querySelectorAll('.open-detail-modal');
        const closeModalButtons = document.querySelectorAll('#close-modal-btn, #close-modal-btn-2');

        openModalButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const data = this.dataset;

                document.getElementById('modal-jenis').textContent = data.jenis || '-';
                document.getElementById('modal-referensi').textContent = data.referensi || '-';
                document.getElementById('modal-tanggal').textContent = data.tanggal || '-';
                document.getElementById('modal-dpp').textContent = data.dpp || 'Rp 0';
                document.getElementById('modal-tarif').textContent = data.tarif || '-';
                document.getElementById('modal-jumlah').textContent = data.jumlah || 'Rp 0';
                document.getElementById('modal-keterangan').textContent = data.keterangan || 'Tidak ada keterangan.';
                
                const statusBadge = document.getElementById('modal-status-badge');
                if (data.status === 'sudah_dibayar') {
                    statusBadge.innerHTML = `<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Dibayar</span>`;
                } else {
                    statusBadge.innerHTML = `<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Belum Dibayar</span>`;
                }
                
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
