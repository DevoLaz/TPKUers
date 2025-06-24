@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInLeft {
  from { opacity: 0; transform: translateX(-30px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

@keyframes shimmer {
  0% { background-position: -468px 0; }
  100% { background-position: 468px 0; }
}

.animate-fade { animation: fadeInUp 0.8s ease-out both; }
.animate-slide { animation: slideInLeft 0.6s ease-out both; }
.animate-pulse { animation: pulse 2s infinite; }

/* ===== CARDS EFFECTS ===== */
.card-hover {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(10px);
}

.card-hover:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.metric-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
  border: 1px solid rgba(255,255,255,0.2);
  transition: all 0.3s ease;
}

.metric-card:hover {
  background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0.9) 100%);
  border-color: rgba(34, 197, 94, 0.3);
}

/* ===== GRADIENTS ===== */
.gradient-green { background: linear-gradient(135deg, #059669 0%, #34d399 100%); }
.gradient-blue { background: linear-gradient(135deg, #0369a1 0%, #38bdf8 100%); }
.gradient-purple { background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); }
.gradient-orange { background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%); }
.gradient-pink { background: linear-gradient(135deg, #be185d 0%, #f472b6 100%); }

/* ===== PROGRESS BARS ===== */
.progress-bar {
  height: 8px;
  background: rgba(0,0,0,0.1);
  border-radius: 10px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 2s ease-in-out;
  animation: shimmer 2s infinite linear;
  background: linear-gradient(90deg, 
    rgba(34, 197, 94, 0.8) 25%, 
    rgba(34, 197, 94, 1) 50%, 
    rgba(34, 197, 94, 0.8) 75%);
  background-size: 200% 100%;
}

/* ===== ALERTS ===== */
.alert-slide {
  animation: slideInLeft 0.5s ease-out;
}

.alert-danger { 
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
  border-left: 4px solid #ef4444;
}

.alert-warning { 
  background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
  border-left: 4px solid #f59e0b;
}

.alert-success { 
  background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
  border-left: 4px solid #22c55e;
}

/* ===== CHARTS ===== */
.chart-container {
  position: relative;
  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}

/* ===== ICONS ===== */
.icon-bounce {
  transition: transform 0.3s ease;
}

.icon-bounce:hover {
  transform: translateY(-2px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .card-hover:hover {
    transform: translateY(-4px) scale(1.01);
  }
}

/* ===== SPECIAL EFFECTS ===== */
.glow-effect {
  position: relative;
}

.glow-effect::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: inherit;
  border-radius: inherit;
  filter: blur(20px);
  opacity: 0.3;
  z-index: -1;
}
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
  @include('layouts.sidebar')

  <main class="flex-1 ml-20 group-hover/sidebar:ml-64 transition-all duration-300">
    @php
      // Menggunakan timezone 'Asia/Jakarta' agar sesuai dengan waktu di Indonesia (WIB)
      $now = now('Asia/Jakarta');
      $hour = $now->format('H');
      
      if ($hour >= 5 && $hour < 12) {
          $greeting = 'Selamat Pagi';
          $greetingIcon = 'sunrise';
      } elseif ($hour >= 12 && $hour < 15) {
          $greeting = 'Selamat Siang';
          $greetingIcon = 'sun';
      } elseif ($hour >= 15 && $hour < 18) {
          $greeting = 'Selamat Sore';
          $greetingIcon = 'sunset';
      } else {
          $greeting = 'Selamat Malam';
          $greetingIcon = 'moon';
      }
    @endphp

    <!-- Header Hero dengan Stats -->
    <section class="relative bg-gradient-to-r from-green-900 via-green-800 to-green-700 text-white p-8 overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white rounded-full"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white rounded-full"></div>
      </div>
      
      <div class="relative z-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
          <div class="animate-fade">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2 flex items-center gap-3">
              <i data-lucide="{{ $greetingIcon }}" class="w-12 h-12"></i>
              <span>{{ $greeting }}, {{ Auth::user()->name ?? 'Guest' }}</span>
            </h1>
            <p class="text-lg opacity-90 mb-4">
              "Allah Bersama Orang-Orang Yang Sabar" - Al-Baqarah 153
            </p>
            <div class="text-sm opacity-80 flex items-center gap-4">
              <span class="flex items-center gap-2"><i data-lucide="calendar" class="w-4 h-4"></i>{{ $now->translatedFormat('l, d F Y') }}</span>
              <span class="flex items-center gap-2"><i data-lucide="clock" class="w-4 h-4"></i>{{ $now->format('H:i') }} WIB</span>
            </div>
          </div>
          
          <div class="mt-6 lg:mt-0 animate-slide">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 min-w-[280px]">
              <h3 class="text-lg font-semibold mb-4 flex items-center gap-2"><i data-lucide="dollar-sign" class="w-5 h-5"></i> Ringkasan Hari Ini</h3>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span>Pendapatan:</span>
                  <span class="font-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Transaksi:</span>
                  <span class="font-bold">{{ $transaksiHariIni }} kali</span>
                </div>
                <div class="flex justify-between">
                  <span>Total Kas:</span>
                  <span class="font-bold text-green-300">Rp {{ number_format($totalKas, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="p-8 space-y-8">
      
      <!-- Alerts Section -->
      @if(isset($alerts) && count($alerts) > 0)
      <div class="space-y-3">
        @foreach($alerts as $index => $alert)
        <div class="alert-{{ $alert['type'] }} alert-slide p-4 rounded-xl flex items-center justify-between" 
             style="animation-delay: {{ $index * 0.1 }}s">
          <div class="flex items-center space-x-3">
            <i data-lucide="{{ $alert['icon'] }}" class="w-6 h-6"></i>
            <span class="font-medium">{{ $alert['message'] }}</span>
          </div>
          <a href="{{ $alert['url'] }}" class="px-4 py-2 bg-white/20 rounded-lg text-sm hover:bg-white/30 transition">
            {{ $alert['action'] }}
          </a>
        </div>
        @endforeach
      </div>
      @endif

      <!-- Metrics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- 🔥 FIXED: Warna ikon diubah menjadi lebih kontras --}}
        <!-- Total Piutang Card -->
        <a href="{{ route('laporan.utang_piutang') }}" class="metric-card card-hover rounded-2xl p-6 glow-effect animate-fade" style="animation-delay: 0.1s">
          <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 rounded-xl p-3"><i data-lucide="arrow-down-circle" class="w-8 h-8 text-blue-600"></i></div>
          </div>
          <h3 class="text-gray-600 font-medium mb-2">Total Piutang</h3>
          <p class="text-3xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</p>
          <p class="text-sm text-gray-500">Tagihan yang belum dibayar pelanggan</p>
        </a>

        <!-- Total Utang Card -->
        <a href="{{ route('laporan.utang_piutang') }}" class="metric-card card-hover rounded-2xl p-6 glow-effect animate-fade" style="animation-delay: 0.2s">
          <div class="flex items-center justify-between mb-4">
            <div class="bg-orange-100 rounded-xl p-3"><i data-lucide="arrow-up-circle" class="w-8 h-8 text-orange-600"></i></div>
          </div>
          <h3 class="text-gray-600 font-medium mb-2">Total Utang</h3>
          <p class="text-3xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalUtang ?? 0, 0, ',', '.') }}</p>
          <p class="text-sm text-gray-500">Kewajiban yang harus dibayar</p>
        </a>

        <!-- Kas & Bank Card -->
        <div class="metric-card card-hover rounded-2xl p-6 glow-effect animate-fade" style="animation-delay: 0.3s">
          <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 rounded-xl p-3"><i data-lucide="wallet" class="w-8 h-8 text-green-600"></i></div>
          </div>
          <h3 class="text-gray-600 font-medium mb-2">Kas & Bank</h3>
          <p class="text-3xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</p>
          <p class="text-sm text-gray-500">Posisi kas & bank saat ini</p>
        </div>

        <!-- Nilai Persediaan Card -->
        <a href="{{ route('laporan.persediaan') }}" class="metric-card card-hover rounded-2xl p-6 glow-effect animate-fade" style="animation-delay: 0.4s">
          <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 rounded-xl p-3"><i data-lucide="archive" class="w-8 h-8 text-purple-600"></i></div>
          </div>
          <h3 class="text-gray-600 font-medium mb-2">Nilai Persediaan</h3>
          <p class="text-3xl font-bold text-gray-800 mb-1">Rp {{ number_format($totalPersediaan ?? 0, 0, ',', '.') }}</p>
          <p class="text-sm text-gray-500">Total nilai stok barang</p>
        </a>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Grafik Penjualan 7 Hari -->
        <div class="chart-container card-hover animate-fade" style="animation-delay: 0.5s">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2"><i data-lucide="line-chart"></i> Penjualan 7 Hari</h2>
            <div class="text-sm text-gray-500">Dalam Jutaan</div>
          </div>
          <canvas id="salesChart" height="200"></canvas>
        </div>

        <!-- Grafik Laba Rugi Bulanan -->
        <div class="chart-container card-hover animate-fade" style="animation-delay: 0.6s">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2"><i data-lucide="candlestick-chart"></i> Laba Rugi {{ date('Y') }}</h2>
            <div class="text-sm text-gray-500">Per Bulan</div>
          </div>
          <canvas id="profitChart" height="200"></canvas>
        </div>
      </div>

      <!-- Analisis & Insights -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Top Produk Terlaris -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 card-hover animate-fade" style="animation-delay: 0.7s">
          <div class="flex items-center mb-6">
            <div class="gradient-pink rounded-xl p-3 mr-4"><i data-lucide="trophy" class="w-6 h-6 text-white"></i></div>
            <h2 class="text-xl font-bold text-gray-800">Top Produk Terlaris</h2>
          </div>
          <div class="space-y-4">
            @forelse($produkTerlaris as $index => $produk)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
              <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                  {{ $index + 1 }}
                </div>
                <div>
                  <p class="font-semibold text-gray-800">{{ $produk->nama }}</p>
                  <p class="text-sm text-gray-500">{{ $produk->total_terjual }} terjual</p>
                </div>
              </div>
              <div class="text-right">
                <p class="font-bold text-green-600">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</p>
              </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
              <i data-lucide="package-x" class="w-12 h-12 mx-auto text-gray-300"></i>
              <p class="mt-2">Belum ada data penjualan bulan ini</p>
            </div>
            @endforelse
          </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 card-hover animate-fade" style="animation-delay: 0.8s">
          <div class="flex items-center mb-6">
            <div class="gradient-orange rounded-xl p-3 mr-4"><i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i></div>
            <h2 class="text-xl font-bold text-gray-800">Stok Menipis</h2>
          </div>
          <div class="space-y-4">
            @forelse($stokMenipis as $barang)
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
              <div>
                <p class="font-semibold text-gray-800">{{ $barang->nama }}</p>
                <p class="text-sm text-gray-500">{{ $barang->kategori }}</p>
              </div>
              <div class="text-right">
                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-bold">{{ $barang->stok }}</span>
                <p class="text-xs text-red-600 mt-1">Perlu restok!</p>
              </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
              <i data-lucide="package-check" class="w-12 h-12 mx-auto text-gray-300"></i>
              <p class="mt-2">Semua stok aman!</p>
            </div>
            @endforelse
          </div>
        </div>

        <!-- Kategori Penjualan -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 card-hover animate-fade" style="animation-delay: 0.9s">
          <div class="flex items-center mb-6">
            <div class="gradient-blue rounded-xl p-3 mr-4"><i data-lucide="pie-chart" class="w-6 h-6 text-white"></i></div>
            <h2 class="text-xl font-bold text-gray-800">Penjualan per Kategori</h2>
          </div>
          <div class="space-y-4">
            @forelse($penjualanKategori as $kategori)
            @php
              $maxPenjualan = $penjualanKategori->max('total_penjualan');
              $percentage = $maxPenjualan > 0 ? ($kategori->total_penjualan / $maxPenjualan) * 100 : 0;
            @endphp
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">{{ ucfirst($kategori->kategori) }}</span>
                <span class="text-sm font-bold text-gray-600">Rp {{ number_format($kategori->total_penjualan/1000000, 1) }}M</span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
              </div>
              <div class="flex justify-between text-xs text-gray-500">
                <span>{{ $kategori->jumlah_transaksi }} transaksi</span>
                <span>{{ number_format($percentage, 1) }}%</span>
              </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
              <i data-lucide="folder-x" class="w-12 h-12 mx-auto text-gray-300"></i>
              <p class="mt-2">Belum ada data kategori</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Quick Stats & Insights -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 text-center card-hover animate-fade" style="animation-delay: 1s">
          <i data-lucide="package" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
          <p class="text-2xl font-bold text-gray-800">{{ $totalBarang }}</p>
          <p class="text-sm text-gray-600">Total Produk</p>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 text-center card-hover animate-fade" style="animation-delay: 1.1s">
          <i data-lucide="tags" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
          <p class="text-2xl font-bold text-gray-800">{{ $totalKategori }}</p>
          <p class="text-sm text-gray-600">Kategori</p>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 text-center card-hover animate-fade" style="animation-delay: 1.2s">
          <i data-lucide="wallet" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
          <p class="text-2xl font-bold text-gray-800">{{ number_format($rataRataPenjualanHarian/1000000, 1) }}M</p>
          <p class="text-sm text-gray-600">Rata-rata/Hari</p>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 text-center card-hover animate-fade" style="animation-delay: 1.3s">
          <i data-lucide="repeat" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
          <p class="text-2xl font-bold text-gray-800">{{ $totalTransaksi }}</p>
          <p class="text-sm text-gray-600">Total Transaksi</p>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 card-hover animate-fade" style="animation-delay: 1.4s">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center">
            <div class="gradient-green rounded-xl p-3 mr-4"><i data-lucide="clipboard-list" class="w-6 h-6 text-white"></i></div>
            <h2 class="text-2xl font-bold text-gray-800">Transaksi Terbaru</h2>
          </div>
          <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-2 font-semibold text-gray-700">Tanggal</th>
                <th class="text-left py-3 px-2 font-semibold text-gray-700">Jenis</th>
                <th class="text-left py-3 px-2 font-semibold text-gray-700">Kategori</th>
                <th class="text-left py-3 px-2 font-semibold text-gray-700">Barang</th>
                <th class="text-left py-3 px-2 font-semibold text-gray-700">Qty</th>
                <th class="text-right py-3 px-2 font-semibold text-gray-700">Jumlah</th>
                <th class="text-center py-3 px-2 font-semibold text-gray-700">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($transaksiTerbaru as $transaksi)
              <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="py-3 px-2 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m H:i') }}</td>
                <td class="py-3 px-2">
                  @if($transaksi->jenis == 'masuk')
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium flex items-center gap-1.5"><i data-lucide="arrow-down" class="w-3 h-3"></i> Masuk</span>
                  @else
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium flex items-center gap-1.5"><i data-lucide="arrow-up" class="w-3 h-3"></i> Keluar</span>
                  @endif
                </td>
                <td class="py-3 px-2 text-sm text-gray-600">{{ ucfirst($transaksi->kategori) }}</td>
                <td class="py-3 px-2 text-sm text-gray-800 font-medium">{{ $transaksi->nama_barang ?? 'Transaksi Umum' }}</td>
                <td class="py-3 px-2 text-sm text-gray-600">{{ $transaksi->qty ?? '-' }}</td>
                <td class="py-3 px-2 text-right font-semibold">
                  <span class="{{ $transaksi->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                </td>
                <td class="py-3 px-2 text-center">
                  <span class="w-3 h-3 bg-green-500 rounded-full inline-block animate-pulse"></span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                  <i data-lucide="ghost" class="w-12 h-12 mx-auto text-gray-300"></i>
                  <p class="mt-2">Belum ada transaksi</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Proyeksi & Insight -->
      <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white animate-fade" style="animation-delay: 1.5s">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <i data-lucide="crystal-ball" class="w-10 h-10 mx-auto mb-4 opacity-75"></i>
            <h3 class="text-xl font-bold mb-2">Proyeksi Akhir Bulan</h3>
            <p class="text-3xl font-bold">Rp {{ number_format($proyeksiAkhirBulan, 0, ',', '.') }}</p>
            <p class="text-sm opacity-80 mt-2">Berdasarkan tren saat ini</p>
          </div>
          <div class="text-center">
            <i data-lucide="zap" class="w-10 h-10 mx-auto mb-4 opacity-75"></i>
            <h3 class="text-xl font-bold mb-2">Velocity Penjualan</h3>
            <p class="text-3xl font-bold">{{ number_format($rataRataPenjualanHarian/1000000, 1) }}M</p>
            <p class="text-sm opacity-80 mt-2">Per hari rata-rata</p>
          </div>
          <div class="text-center">
            <i data-lucide="trending-up" class="w-10 h-10 mx-auto mb-4 opacity-75"></i>
            <h3 class="text-xl font-bold mb-2">Growth Rate</h3>
            <p class="text-3xl font-bold">@if($perubahanPendapatan >= 0)+@endif{{ number_format($perubahanPendapatan, 1) }}%</p>
            <p class="text-sm opacity-80 mt-2">vs bulan sebelumnya</p>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide-static@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Menjalankan skrip untuk membuat ikon
  lucide.createIcons();

  // Pastikan variabel ada sebelum digunakan untuk mencegah error
  const tanggal7Hari = @json($tanggal7Hari ?? []);
  const penjualan7Hari = @json($penjualan7Hari ?? []);
  const dataBulanan = @json($dataBulanan ?? []);

  // ===== GRAFIK PENJUALAN 7 HARI =====
  const salesCtx = document.getElementById('salesChart');
  if (salesCtx && tanggal7Hari.length > 0) {
    new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: tanggal7Hari,
        datasets: [{
          label: 'Penjualan',
          data: penjualan7Hari,
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: '#10b981',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 3,
          pointRadius: 6,
          pointHoverRadius: 8,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            borderColor: '#10b981',
            borderWidth: 2,
            callbacks: {
              label: function(context) {
                return 'Rp ' + parseFloat(context.raw).toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: { 
            ticks: { 
              callback: val => 'Rp ' + (val/1000000).toFixed(1) + 'M',
              color: '#6b7280'
            },
            grid: { color: 'rgba(0, 0, 0, 0.1)' }
          },
          x: { 
            ticks: { color: '#6b7280' },
            grid: { display: false }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index'
        }
      }
    });
  }
  
  // ===== GRAFIK LABA RUGI BULANAN =====
  const profitCtx = document.getElementById('profitChart');
  if (profitCtx && dataBulanan.length > 0) {
    new Chart(profitCtx, {
      type: 'bar',
      data: {
        labels: dataBulanan.map(d => d.bulan),
        datasets: [
          {
            label: 'Pendapatan',
            data: dataBulanan.map(d => d.pendapatan),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: '#22c55e',
            borderWidth: 2,
            borderRadius: 8,
          },
          {
            label: 'Pengeluaran',
            data: dataBulanan.map(d => d.pengeluaran),
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: '#ef4444',
            borderWidth: 2,
            borderRadius: 8,
          },
          {
            label: 'Laba',
            data: dataBulanan.map(d => d.laba),
            type: 'line',
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            tension: 0.4,
            fill: false,
            pointBackgroundColor: '#8b5cf6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 5,
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { 
            display: true,
            position: 'top',
            labels: { color: '#6b7280' }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            callbacks: {
              label: function(context) {
                return context.dataset.label + ': Rp ' + parseFloat(context.raw).toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: { 
            ticks: { 
              callback: val => 'Rp ' + (val/1000000).toFixed(0) + 'M',
              color: '#6b7280'
            },
            grid: { color: 'rgba(0, 0, 0, 0.1)' }
          },
          x: { 
            ticks: { color: '#6b7280' },
            grid: { display: false }
          }
        }
      }
    });
  }

  // ===== ANIMASI COUNTER =====
  function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
      start += increment;
      element.textContent = Math.floor(start).toLocaleString('id-ID');
      
      if (start >= target) {
        element.textContent = target.toLocaleString('id-ID');
        clearInterval(timer);
      }
    }, 16);
  }

  // ===== INTERSECTION OBSERVER UNTUK ANIMASI =====
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animationPlayState = 'running';
      }
    });
  }, observerOptions);

  document.querySelectorAll('.animate-fade, .animate-slide').forEach(el => {
    el.style.animationPlayState = 'paused';
    observer.observe(el);
  });

  // ===== REFRESH DATA SETIAP 5 MENIT =====
  setInterval(() => {
    // Auto refresh halaman setiap 5 menit untuk data real-time
    console.log('🔄 Checking for updates...');
    // Bisa ditambahkan AJAX call untuk update data tanpa refresh
  }, 300000); // 5 menit

});
</script>
@endpush
