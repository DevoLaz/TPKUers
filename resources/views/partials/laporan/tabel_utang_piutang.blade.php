{{-- resources/views/partials/laporan/tabel_utang_piutang.blade.php --}}

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="{{ $headerColor ?? 'bg-gray-50' }} border-b">
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">Tanggal</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">No. Invoice</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">{{ ($type ?? 'piutang') == 'piutang' ? 'Pelanggan' : 'Pemasok' }}</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">Jatuh Tempo</th>
                <th class="py-3 px-4 text-right text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">Jumlah</th>
                <th class="py-3 px-4 text-center text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">Status</th>
                {{-- 🔥 KOLOM AKSI DITAMBAHKAN --}}
                <th class="py-3 px-4 text-center text-sm font-semibold {{ $headerTextColor ?? 'text-gray-700' }}">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr class="{{ $rowHoverColor ?? 'hover:bg-gray-50' }} border-b transition-colors">
                    <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="py-3 px-4 font-medium">{{ $item->no_invoice ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $item->nama_kontak ?? 'N/A' }}</td>
                    <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-center">
                        @if(isset($item->status) && $item->status == 'lunas')
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Lunas</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Belum Lunas</span>
                        @endif
                    </td>
                    {{-- 🔥 TOMBOL UNTUK MEMBUKA POPUP DETAIL --}}
                    <td class="py-3 px-4 text-center">
                        <button 
                            class="open-detail-modal text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-100 rounded-full transition"
                            data-tipe="{{ $type }}"
                            data-kontak="{{ $item->nama_kontak }}"
                            data-invoice="{{ $item->no_invoice }}"
                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY') }}"
                            data-jatuh-tempo="{{ \Carbon\Carbon::parse($item->jatuh_tempo)->isoFormat('DD MMMM YYYY') }}"
                            data-akun="{{ $item->akun }}"
                            data-keterangan="{{ $item->keterangan }}"
                            data-jumlah="Rp {{ number_format($item->jumlah, 0, ',', '.') }}"
                            data-status="{{ $item->status }}">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- colspan diubah menjadi 7 karena ada tambahan kolom Aksi --}}
                    <td colspan="7" class="py-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>Belum ada data {{ $title ?? 'ini' }}</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(isset($items) && count($items) > 0)
            <tfoot>
                <tr class="{{ $totalBgColor ?? 'bg-gray-100' }} font-bold">
                    <td colspan="4" class="py-3 px-4">Total {{ $title ?? '' }}</td>
                    <td class="py-3 px-4 text-right {{ $totalColor ?? 'text-gray-800' }}">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</td>
                    {{-- colspan diubah menjadi 2 agar sejajar --}}
                    <td colspan="2" class="py-3 px-4"></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
