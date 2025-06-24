@extends('layouts.app')

@section('title', 'Daftar Aset Tetap')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
    @include('layouts.sidebar')
    
    <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#173720] to-[#2a5a37] rounded-lg p-6 mb-6 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Daftar Aset Tetap</h1>
                    <p class="text-green-100">Kelola semua aset tetap perusahaan.</p>
                </div>
                <a href="{{ route('aset-tetap.create') }}" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all transform hover:scale-105 backdrop-blur font-semibold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Tambah Aset</span>
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#173720] text-white">
                            <th class="py-3 px-4 text-left text-sm font-bold uppercase">Nama Aset</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Harga Perolehan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Akm. Penyusutan</th>
                            <th class="py-3 px-4 text-right text-sm font-bold uppercase">Nilai Buku</th>
                            <th class="py-3 px-4 text-center text-sm font-bold uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($asets as $aset)
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 font-medium">{{ $aset->nama_aset }}</td>
                                <td class="py-3 px-4 text-right">Rp {{ number_format($aset->harga_perolehan, 2, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right text-red-600">(Rp {{ number_format($aset->akumulasi_penyusutan, 2, ',', '.') }})</td>
                                <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($aset->nilai_buku, 2, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('aset-tetap.edit', $aset->id) }}" class="inline-flex w-8 h-8 items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('aset-tetap.destroy', $aset->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus aset ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex w-8 h-8 items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <i data-lucide="building" class="w-16 h-16 text-gray-300"></i>
                                        <p class="text-gray-500 text-lg">Belum ada data aset tetap.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if($asets->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $asets->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
@endpush
