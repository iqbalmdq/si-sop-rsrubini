@extends('layouts.public')

@section('title', 'Survei Publik - RS Rubini Mempawah')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 text-white rounded-lg p-8 mb-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-poll mr-3"></i>
                Survei Publik
            </h1>
            <p class="text-xl text-green-100 mb-6">
                Berpartisipasilah dalam survei untuk membantu meningkatkan kualitas layanan kami
            </p>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 inline-block">
                <div class="flex items-center justify-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list text-2xl mr-2"></i>
                        <div>
                            <div class="font-semibold">{{ $surveys->total() }}</div>
                            <div class="text-green-200">Survei Tersedia</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-2xl mr-2"></i>
                        <div>
                            <div class="font-semibold">Publik</div>
                            <div class="text-green-200">Dapat Berpartisipasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey List -->
    @if($surveys->count() > 0)
        <div class="space-y-6">
            @foreach($surveys as $survey)
                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-poll mr-1"></i>
                                    Survei Aktif
                                </span>
                                @if($survey->anonim)
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                        <i class="fas fa-user-secret mr-1"></i>
                                        Anonim
                                    </span>
                                @endif
                                @if($survey->target_bidang)
                                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $survey->target_bidang }}
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $survey->judul }}</h3>
                            <p class="text-gray-600 mb-4">{{ $survey->deskripsi }}</p>
                            
                            <div class="flex items-center space-x-6 text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    Dibuat oleh: {{ $survey->creator->name }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-question-circle mr-1"></i>
                                    {{ $survey->questions->count() }} Pertanyaan
                                </div>
                                @if($survey->tanggal_berakhir)
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-times mr-1"></i>
                                        Berakhir: {{ $survey->tanggal_berakhir->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('survey.show', $survey) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-play mr-2"></i>
                                    Mulai Survei
                                </a>
                                
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Estimasi: {{ $survey->questions->count() * 2 }} menit
                                </div>
                            </div>
                        </div>
                        
                        <div class="ml-6 text-center">
                            <div class="bg-gray-100 rounded-lg p-4">
                                <div class="text-2xl font-bold text-gray-800">{{ $survey->responses->count() }}</div>
                                <div class="text-sm text-gray-600">Responden</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $surveys->links() }}
        </div>
    @else
        <!-- No Surveys -->
        <div class="text-center py-12">
            <i class="fas fa-poll text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Survei Tersedia</h3>
            <p class="text-gray-500">Saat ini belum ada survei yang dapat diikuti. Silakan kembali lagi nanti.</p>
        </div>
    @endif
</div>
@endsection