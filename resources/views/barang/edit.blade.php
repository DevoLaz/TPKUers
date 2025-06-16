@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9] p-8">
  <div class="max-w-3xl w-full mx-auto bg-white p-8 rounded-xl shadow-md border border-[#ddd]">

    <h1 class="text-3xl font-bold mb-8 text-[#173720]">Edit Barang</h1>

    <form method="POST" action="{{ route('barang.update', $barang) }}">
      @csrf
      @method('PUT')

      <div class="mb-5">
        <label for="nama" class="block text-sm font-medium text-[#173720] mb-1">Nama Barang</label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $barang->nama) }}" required
          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#173720] focus:border-[#173720]">
      </div>

      <div class="mb-5">
        <label for="kategori" class="block text-sm font-medium text-[#173720] mb-1">Kategori</label>
        <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $barang->kategori) }}" required
          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#173720] focus:border-[#173720]">
      </div>

      <div class="mb-5">
        <label for="harga" class="block text-sm font-medium text-[#173720] mb-1">Harga</label>
        <input type="number" name="harga" id="harga" value="{{ old('harga', $barang->harga) }}" required
          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#173720] focus:border-[#173720]">
      </div>

      <div class="mb-5">
        <label for="stok" class="block text-sm font-medium text-[#173720] mb-1">Stok</label>
        <input type="number" name="stok" id="stok" value="{{ old('stok', $barang->stok) }}" required
          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#173720] focus:border-[#173720]">
      </div>

      <div class="flex justify-end mt-8 space-x-4">
        <a href="{{ route('barang.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg">Batal</a>
        <button type="submit" class="bg-[#0F3C1E] hover:bg-[#246342] text-white px-5 py-2 rounded-lg">Update</button>
      </div>

    </form>

  </div>
</div>
@endsection
