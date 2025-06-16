@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F9FAF9] py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div class="text-center">
      <h2 class="text-3xl font-extrabold text-[#173720]">Masuk ke Akun Anda</h2>
      <p class="mt-2 text-sm text-gray-600">Selamat datang kembali!</p>
    </div>

    @if (session('status'))
      <div class="mb-4 text-sm text-green-600">
        {{ session('status') }}
      </div>
    @endif

    <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-[#173720]">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" required
          class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:z-10 sm:text-sm"
          placeholder="you@example.com" value="{{ old('email') }}" autofocus>
        @error('email')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

     <!-- Password -->
<div class="relative mt-4">
  <label for="password" class="block text-sm font-medium text-[#173720] mb-1">Password</label>
  
  <input id="password" name="password" type="password" autocomplete="current-password" required
    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-md bg-blue-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0F3C1E] focus:border-[#0F3C1E]"
    placeholder="********">

  <!-- Tombol mata -->
  <button type="button" onclick="togglePassword()"
    class="absolute top-[38px] right-3 text-gray-500 hover:text-gray-700">
    <svg id="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
      viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>

  @error('password')
    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
  @enderror
</div>


      <!-- Remember Me & Forgot -->
      <div class="flex items-center justify-between">
        <label class="flex items-center">
          <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
          <span class="ml-2 text-sm text-gray-600">Tetap Masuk?</span>
        </label>
        <div class="text-sm">
          <a href="{{ route('password.request') }}" class="font-medium text-green-600 hover:text-green-500">Lupa Password?</a>
        </div>
      </div>

      <!-- Submit -->
      <div>
        <button type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#173720] hover:bg-[#155c30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
          Masuk
        </button>
      </div>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      Belum punya akun?
      <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">Daftar sekarang</a>
    </p>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon = document.getElementById('eye');

  if (input.type === 'password') {
    input.type = 'text';
    icon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.664-3.044M6.42 6.42A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.18 5.346M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
    `;
  } else {
    input.type = 'password';
    icon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    `;
  }
}
</script>

@endsection
