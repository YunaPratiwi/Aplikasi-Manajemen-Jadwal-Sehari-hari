@php($title = '404 - Halaman tidak ditemukan')
@extends('layouts.app')

@section('content')
  <div class="text-center py-12">
    <h1 class="text-3xl font-semibold mb-4">404 - Halaman tidak ditemukan</h1>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Maaf, halaman yang Anda cari tidak tersedia.</p>
    <a href="{{ route('dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
  </div>
@endsection

