@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade {
  animation: fadeInUp 0.8s ease-out both;
}

/* Card lift saat hover */
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

/* Tombol glow saat hover */
.button-glow:hover {
  box-shadow: 0 0 10px rgba(34, 197, 94, 0.7);
  transform: scale(1.02);
  transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-[#F9FAF9]">

  @include('layouts.sidebar')

  <!-- Main -->
  <main class="flex-1 ml-20 group-hover:ml-64 transition-all duration-300 animate-fade">
  @php
    $hour = now()->format('H');
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
@endphp

    <!-- Header Hero -->
<section class="relative bg-gradient-to-r from-green-900 to-green-700 text-white p-8 shadow-md">
  <h1 class="text-4xl font-bold">{{ $greeting }}, {{ Auth::user()->name ?? 'Guest' }}</h1>
  <p class="text-sm mt-2 opacity-80">Allah Bersama Orang-Orang Yang Sabar : Al-Baqarah 153</p>
</section>

    <!-- Main Content -->
    <div class="p-8 space-y-8">
      <!-- Grafik Penjualan & Laba/Rugi -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-[#ddd] hover-lift">
          <h2 class="text-xl font-semibold mb-4 text-[#173720]">Grafik Penjualan</h2>
          <canvas id="salesChart" height="150"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-[#ddd] hover-lift">
          <h2 class="text-xl font-semibold mb-4 text-[#173720]">Laba/Rugi Tahun Ini</h2>
          <div class="flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2">
              <canvas id="profitChart" width="100" height="100"></canvas>
            </div>
            <div class="w-full md:w-1/2 mt-6 md:mt-0 md:pl-8 space-y-3 text-sm text-[#2a5132]">
              <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-[#4ade80] rounded-full"></span>
                <p><strong>Pendapatan:</strong> Rp 780.000.000</p>
              </div>
              <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-[#fbbf24] rounded-full"></span>
                <p><strong>Nilai HPP:</strong> Rp 300.000.000</p>
              </div>
              <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-[#f87171] rounded-full"></span>
                <p><strong>Pengeluaran:</strong> Rp 35.000.000</p>
              </div>
              <div class="flex items-center space-x-2 mt-2">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <p class="text-green-600 font-bold">Laba: Rp 445.000.000</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Invoices -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-[#ddd] hover-lift">
        <h2 class="text-xl font-semibold mb-4 text-[#173720]">Recent Invoices</h2>
        <table class="min-w-full text-sm border-t border-[#ccc]">
          <thead class="text-[#2a5132] border-b border-[#ccc]">
            <tr>
              <th class="py-2">No</th>
              <th class="py-2">Recipient</th>
              <th class="py-2">Date</th>
              <th class="py-2">Amount</th>
              <th class="py-2">Status</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            @php
              $invoices = $invoices ?? [
                ['no' => 'SG-4156', 'recipient' => 'Starbucks', 'date' => 'May 5, 8:14 AM', 'amount' => '$15.00', 'status' => 'Paid'],
                ['no' => 'SG-4157', 'recipient' => 'Netflix', 'date' => 'May 5, 7:30 PM', 'amount' => '$20.00', 'status' => 'Paid'],
              ];
            @endphp
            @foreach ($invoices as $invoice)
            <tr class="border-b border-[#eee]">
              <td class="py-2">{{ $invoice['no'] }}</td>
              <td class="py-2">{{ $invoice['recipient'] }}</td>
              <td class="py-2">{{ $invoice['date'] }}</td>
              <td class="py-2">{{ $invoice['amount'] }}</td>
              <td class="py-2 text-green-600">{{ $invoice['status'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>lucide.createIcons();</script>

<script>
const salesCtx = document.getElementById('salesChart');
new Chart(salesCtx, {
  type: 'line',
  data: {
    labels: ['5 Des', '6 Des', '7 Des', '8 Des', '9 Des', '10 Des', '11 Des'],
    datasets: [{
      label: 'Penjualan',
      data: [5000000, 7000000, 9000000, 10250000, 9800000, 9200000, 13000000],
      borderColor: '#15803d',
      backgroundColor: '#34d399',
      tension: 0.3,
      fill: false,
      pointBackgroundColor: '#15803d'
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { ticks: { callback: val => 'Rp ' + val.toLocaleString(), color: '#173720' }},
      x: { ticks: { color: '#173720' }}
    }
  }
});

const profitCtx = document.getElementById('profitChart');
new Chart(profitCtx, {
  type: 'doughnut',
  data: {
    labels: ['Pendapatan', 'Nilai HPP', 'Pengeluaran'],
    datasets: [{
      data: [780000000, 300000000, 35000000],
      backgroundColor: ['#4ade80', '#fbbf24', '#f87171'],
      borderColor: '#ffffff',
      borderWidth: 2
    }]
  },
  options: {
    cutout: '70%',
    plugins: { legend: { display: false } }
  }
});
</script>
@endpush