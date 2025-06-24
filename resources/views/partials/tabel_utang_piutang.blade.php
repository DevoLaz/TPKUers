{{-- resources/views/partials/laporan/tabel_utang_piutang.blade.php --}}
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="{{ $headerColor }} border-b">
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor }}">Tanggal</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor }}">No. Invoice</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor }}">{{ $type == 'piutang' ? 'Pelanggan' : 'Pemasok' }}</th>
                <th class="py-3 px-4 text-left text-sm font-semibold {{ $headerTextColor }}">Jatuh Tempo</th>
                <th class="py-3 px-4 text-right text-sm font-semibold {{ $headerTextColor }}">Jumlah</th>
                <th class="py-3 px-4 text-center text-sm font-semibold {{ $headerTextColor }}">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr class="{{ $rowHoverColor }} border-b transition-colors">
                    <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="py-3 px-4 font-medium">{{ $item->invoice_no ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $item->contact_name ?? '-' }}</td>
                    <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-center">
                        @if($item->status == 'lunas')
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Lunas</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>Belum ada data {{ $title }}</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($items) > 0)
            <tfoot>
                <tr class="{{ $totalBgColor }} font-bold">
                    <td colspan="4" class="py-3 px-4">Total {{ $title }}</td>
                    <td class="py-3 px-4 text-right {{ $totalColor }}">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4"></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>