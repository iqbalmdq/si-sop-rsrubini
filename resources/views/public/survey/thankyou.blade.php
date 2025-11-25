@extends('layouts.public')

@section('title', 'Terima Kasih - RS Rubini Mempawah')

@section('content')
<div class="max-w-2xl mx-auto text-center py-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-6">
            <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Terima Kasih!</h1>
            <p class="text-lg text-gray-600 mb-6">
                {{ session('message', 'Jawaban Anda telah berhasil disimpan. Terima kasih atas partisipasi Anda dalam survei ini.') }}
            </p>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-center">
                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                <span class="text-green-800">Feedback Anda sangat berharga untuk meningkatkan kualitas layanan kami.</span>
            </div>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('survey.index') }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-poll mr-2"></i>
                Lihat Survei Lainnya
            </a>

            <button type="button" id="copy-survey-link" 
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors ml-2">
                <i class="fas fa-link mr-2"></i>
                Salin Link Survei
            </button>
            
            <div class="text-center">
                <a href="{{ route('sop.index') }}" 
                   class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-home mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('copy-survey-link');
    if (!btn) return;
    var link = "{{ session('share_link') }}";
    btn.addEventListener('click', async function () {
        if (!link) return;
        try {
            await navigator.clipboard.writeText(link);
            var old = btn.textContent;
            btn.textContent = 'Tersalin';
            setTimeout(function () { btn.textContent = old; }, 1500);
        } catch (e) {}
    });
});
</script>
@endpush
