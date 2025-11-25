@extends('layouts.public')

@section('title', 'Halaman tidak ditemukan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-4 text-red-600">
            <i class="fas fa-exclamation-triangle text-5xl"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Halaman tidak ditemukan</h1>
        <p class="text-gray-600 mb-6">Dokumen atau halaman yang Anda cari tidak tersedia.</p>
    </div>
</div>
@endsection
