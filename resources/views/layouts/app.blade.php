<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard')</title>

  <!-- Vite: CSS & JS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Flatpickr (Date Picker) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Untuk tambahan script lain -->
  <script src="https://unpkg.com/lucide@latest"></script>
  @stack('head') 
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen">

  <!-- 🔥 Loading Screen -->
  <div id="loadingScreen" class="fixed inset-0 bg-[#F9FAF9] flex items-center justify-center z-50 hidden">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-green-700"></div>
  </div>

  <!-- 🔥 Main Content -->
  <div id="pageContent" class="opacity-0">
    @yield('content')
  </div>

  <!-- 🔥 Script untuk handle loading animasi -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const loadingScreen = document.getElementById('loadingScreen');
    const pageContent = document.getElementById('pageContent');

    // Saat halaman awal selesai load
    loadingScreen.classList.add('hidden');
    pageContent.classList.remove('opacity-0');
    pageContent.classList.add('opacity-100');

    // Saat user klik link atau submit form --> tampilkan loading lagi
    window.addEventListener('beforeunload', function () {
      loadingScreen.classList.remove('hidden');
      pageContent.classList.add('opacity-0');
    });
  });
</script>

  <style>
    #pageContent {
      transition: opacity 0.5s ease-in-out;
    }
  </style>
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

/* Hover Card Effect */
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

/* Hover Button Glow */
.button-glow:hover {
  box-shadow: 0 0 10px rgba(34, 197, 94, 0.7);
  transform: scale(1.02);
  transition: all 0.3s ease;
}
</style>
@endpush

  <!-- Stack script tambahan -->
  @stack('scripts')

</body>
</html>
