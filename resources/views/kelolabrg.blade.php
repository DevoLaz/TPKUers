@extends('layouts.app')

@section('title', 'Kelola Data Pembelian Barang')

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">
@include('layouts.sidebar')
  <!-- Main -->
  <main class="flex-1 p-8 overflow-y-auto transition-all duration-300 ml-20 group-hover/sidebar:ml-64">
    <div class="flex flex-wrap justify-between items-center mb-6">
      <div class="flex items-center gap-4">
        <h1 class="text-3xl font-bold text-[#173720]">Kelola Data Pengadaan Barang</h1>
        @if(request()->has('nama') || request()->has('kategori') || request()->has('tanggal'))
          <a href="{{ route('barang.index') }}" class="text-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow-sm transition">
            ← Kembali
          </a>
        @endif
      </div>
      <button onclick="openCreateModal()" class="bg-[#0F3C1E] hover:bg-[#155c30] text-white px-4 py-2 rounded-lg transition">
        + Tambah Barang
      </button>
    </div>

    <!-- Filter Form -->
    <form id="searchForm" method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <input id="searchNama" name="nama" placeholder="Cari Nama Barang..." class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition" />
      <select id="searchKategori" name="kategori" class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition">
        <option value="">Semua Kategori</option>
        <option value="ATK">ATK</option>
        <option value="Peralatan">Peralatan</option>
        <option value="Elektronik">Elektronik</option>
      </select>
      <div class="relative">
        <input id="tanggal-transaksi" name="tanggal" placeholder="Tanggal Transaksi" class="pl-4 pr-10 py-2 w-full rounded border border-gray-300 focus:ring-2 focus:ring-green-500 hover:ring-2 hover:ring-green-400 transition" />
        <button type="button" id="calendar-icon" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-green-600">
          <i data-lucide="calendar"></i>
        </button>
      </div>
      <button type="submit" class="bg-[#173720] hover:bg-green-800 text-white px-4 py-2 rounded transition">Cari</button>
    </form>

    <!-- Tabel Barang -->
    <div class="bg-white shadow rounded-lg p-6">
      <table class="min-w-full text-sm border border-[#e0e0e0]">
        <thead class="bg-[#dff2e1] text-[#173720]">
          <tr>
            <th class="py-2 px-4 text-left">No</th>
            <th class="py-2 px-4 text-left">Nama Barang</th>
            <th class="py-2 px-4 text-left">Kategori</th>
            <th class="py-2 px-4 text-left">Jumlah</th>
            <th class="py-2 px-4 text-left">Waktu & Tanggal</th>
            <th class="py-2 px-4 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-gray-800">
          @forelse ($barangs as $barang)
          <tr class="border-t border-gray-200 hover:bg-[#f4faf5]">
            <td class="py-2 px-4">{{ $loop->iteration }}</td>
            <td class="py-2 px-4">{{ $barang->nama }}</td>
            <td class="py-2 px-4">{{ $barang->kategori }}</td>
            <td class="py-2 px-4">{{ $barang->stok }}</td>
            <td class="py-2 px-4">{{ $barang->created_at->format('Y-m-d H:i:s') }}</td>
            <td class="py-2 px-4">
              <button onclick="openEditModal({{ $barang->id }}, '{{ $barang->nama }}', '{{ $barang->kategori }}', '{{ $barang->harga }}', '{{ $barang->stok }}')" class="text-blue-600 hover:underline mr-2">Edit</button>
              <button onclick="openModal({{ $barang->id }})" class="text-red-600 hover:underline">Hapus</button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">Data barang belum ada.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Modal Hapus -->
    <div id="confirmModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl w-80 animate-fade">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Yakin ingin menghapus data ini?</h2>
        <div class="flex justify-end gap-4">
          <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
          <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Hapus</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div id="createModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
      <div class="bg-white w-full max-w-xl p-6 rounded-lg shadow-xl animate-fade">
        <h2 class="text-xl font-semibold text-[#173720] mb-4">Tambah Barang</h2>
        <form action="{{ route('barang.store') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label for="nama" class="block text-sm font-medium text-[#173720]">Nama Barang</label>
            <input type="text" name="nama" required class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500">
          </div>
          <div>
            <label for="kategori" class="block text-sm font-medium text-[#173720]">Kategori</label>
            <select name="kategori" required class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500">
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="ATK">ATK</option>
              <option value="Elektronik">Elektronik</option>
              <option value="Peralatan">Peralatan</option>
            </select>
          </div>
          <div>
            <label for="harga" class="block text-sm font-medium text-[#173720]">Harga</label>
            <input type="text" name="harga" id="harga" required class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500">
          </div>
          <div>
            <label for="stok" class="block text-sm font-medium text-[#173720]">Stok</label>
            <input type="number" name="stok" required class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-green-500">
          </div>
          <div class="flex justify-end gap-4">
            <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#173720] text-white rounded hover:bg-green-700 transition">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Edit Barang -->
    <div id="editModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-[#173720]">Edit Barang</h2>
        <form method="POST" id="editForm">
          @csrf
          @method('PUT')
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="nama" id="editNama" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori" id="editKategori" class="w-full border border-gray-300 rounded px-3 py-2" required>
              <option value="ATK">ATK</option>
              <option value="Elektronik">Elektronik</option>
              <option value="Peralatan">Peralatan</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
            <input type="number" name="harga" id="editHarga" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
            <input type="number" name="stok" id="editStok" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          <div class="flex justify-end gap-3 mt-4">
            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#173720] text-white rounded hover:bg-green-700 transition">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  lucide.createIcons();

  function openModal(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/barang/${id}`;
    document.getElementById('confirmModal').classList.remove('hidden');
  }

  function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
  }

  function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
  }

  function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
  }

  function openEditModal(id, nama, kategori, harga, stok) {
    const form = document.getElementById('editForm');
    form.action = `/barang/${id}`;
    document.getElementById('editNama').value = nama;
    document.getElementById('editKategori').value = kategori;
    document.getElementById('editHarga').value = harga;
    document.getElementById('editStok').value = stok;
    document.getElementById('editModal').classList.remove('hidden');
  }

  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
  }

  document.getElementById("searchForm").addEventListener("submit", function (e) {
    const nama = document.getElementById("searchNama").value.trim();
    const kategori = document.getElementById("searchKategori").value.trim();
    const tanggal = document.getElementById("tanggal-transaksi").value.trim();

    if (!nama && !kategori && !tanggal) {
      e.preventDefault();
      alert("Minimal isi satu filter sebelum mencari.");
    }
  });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  const picker = flatpickr("#tanggal-transaksi", {
    dateFormat: "Y-m-d",
    allowInput: true,
  });

  document.getElementById("calendar-icon").addEventListener("click", () => {
    picker.open();
  });

  // Harga auto-format ribuan
  document.getElementById('harga')?.addEventListener('input', function (e) {
    let value = this.value.replace(/\D/g, '');
    value = new Intl.NumberFormat('id-ID').format(value);
    this.value = value;
  });
</script>
@endpush
