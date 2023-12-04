@extends('layouts.main')
@section('body')
   <div class="h-screen flex items-center justify-center bg-gray-50">
      <div class="rounded-lg p-6 bg-white shadow-lg w-1/4">
         <form class="grid gap-4" method="post" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="text-sm">
               <label for="email" class="text-gray-500 font-medium pb-2">
                  Email
               </label>
               <input type="email" id="email" name="email" class="form-input w-full px-2 py-1.5 text-sm rounded-md border-gray-300">
            </div>
            <div class="text-sm">
               <label for="password" class="text-gray-500 font-medium pb-2">
                  Kata Sandi Baru
               </label>
               <input type="password" id="password" name="password" class="form-input w-full px-2 py-1.5 text-sm rounded-md border-gray-300">
            </div>
            <div class="text-sm">
               <label for="password_confirmation" class="text-gray-500 font-medium pb-2">
                  Konfirmasi Kata Sandi
               </label>
               <input type="password" id="password_confirmation" name="password_confirmation" class="form-input w-full px-2 py-1.5 text-sm rounded-md border-gray-300">
            </div>
            <div class="flex justify-end">
               <button type="submit" class="bg-blue-500 text-white text-sm px-2.5 py-1.5 rounded-md hover:bg-blue-600">
                  Atur Ulang Kata Sandi
               </button>
            </div>
         </form>
      </div>
   </div>
@endsection
