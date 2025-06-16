@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9] p-8">
  <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md border border-[#ddd] w-full">

    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-[#173720]">Data Barang</h1>
      <a href="{{ route('barang.create') }}" class="bg-[#0F3C1E] hover:bg-[#246342] text-white px-5 py-2 rounded-lg">
        Tambah Barang
      </a>
    </div>

    @if (session('success'))
      <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <table class="min-w-full text-sm border-t border-[#ccc]">
      <thead class="text-[#2a5132] border-b border-[#ccc]">
        <tr>
          <th class="py-2">No</th>
          <th class="py-2">Nama Barang</th>
          <th class="py-2">Kategori</th>
          <th class="py-2">Harga</th>
          <th class="py-2">Stok</th>
          <th class="py-2">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        @foreach ($barangs as $barang)
        <tr class="border-b border-[#eee]">
          <td class="py-2">{{ $loop->iteration }}</td>
          <td class="py-2">{{ $barang->nama }}</td>
          <td class="py-2">{{ $barang->kategori }}</td>
          <td class="py-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
          <td class="py-2">{{ $barang->stok }}</td>
          <td class="py-2 flex gap-2">
            <a href="{{ route('barang.edit', $barang) }}" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
            <form method="POST" action="{{ route('barang.destroy', $barang) }}" onsubmit="return confirm('Yakin hapus barang ini?')">
              @csrf
              @method('DELETE')
              <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
@endsection
