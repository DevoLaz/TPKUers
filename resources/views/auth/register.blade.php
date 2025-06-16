@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-[#F9FAF9] px-4">
  <div class="w-full max-w-sm bg-white shadow-md rounded-lg p-8 border border-[#d4e9da]">
    <h2 class="text-2xl font-bold text-center text-[#173720] mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-center text-gray-600 mb-6">Isi data untuk mendaftar</p>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <!-- Nama -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:outline-none" />
        @error('name')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:outline-none" />
        @error('email')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
        <input id="password" name="password" type="password" required
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:outline-none" />
        @error('password')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Konfirmasi Password -->
      <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0F3C1E] focus:border-[#0F3C1E] focus:outline-none" />
      </div>

      <!-- Tombol Daftar -->
      <button type="submit"
        class="w-full bg-[#173720] hover:bg-[#0f3c1e] text-white font-semibold py-2 rounded-md transition">
        Daftar
      </button>

      <!-- Link login -->
      <p class="mt-4 text-sm text-center text-gray-600">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">Login sekarang</a>
      </p>
    </form>
  </div>
</div>
@endsection
