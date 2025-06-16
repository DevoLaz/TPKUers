@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F9FAF9] py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div class="text-center">
      <h2 class="text-3xl font-extrabold text-[#173720]">Reset Password Anda</h2>
      <p class="mt-2 text-sm text-gray-600">Masukkan password baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
      @csrf

      <!-- Token -->
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-[#173720]">Email</label>
        <input id="email" name="email" type="email" required autofocus autocomplete="username"
          value="{{ old('email', $request->email) }}"
          class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] sm:text-sm bg-blue-50" />
        @error('email')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password Baru -->
      <div>
        <label for="password" class="block text-sm font-medium text-[#173720]">Password Baru</label>
        <input id="password" name="password" type="password" required autocomplete="new-password"
          class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] sm:text-sm bg-blue-50" />
        @error('password')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Konfirmasi Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-[#173720]">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
          class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-[#0F3C1E] focus:border-[#0F3C1E] sm:text-sm bg-blue-50" />
        @error('password_confirmation')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <button type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#173720] hover:bg-[#155c30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
          Reset Password
        </button>
      </div>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">Kembali ke Login</a>
    </p>
  </div>
</div>
@endsection
