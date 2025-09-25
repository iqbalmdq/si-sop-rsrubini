@extends('layouts.public')

@section('title', $survey->judul . ' - RS Rubini Mempawah')

@section('content')
<div class="max-w-4xl mx-auto" x-data="surveyForm()">
    <!-- Survey Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $survey->judul }}</h1>
            <p class="text-gray-600 mb-4">{{ $survey->deskripsi }}</p>
            
            <div class="flex items-center justify-center space-x-6 text-sm">
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-question-circle mr-1"></i>
                    {{ $survey->questions->count() }} Pertanyaan
                </div>
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    Estimasi: {{ $survey->questions->count() * 2 }} menit
                </div>
                @if($survey->anonim)
                    <div class="flex items-center text-blue-600">
                        <i class="fas fa-user-secret mr-1"></i>
                        Survei Anonim
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Progress</span>
                <span x-text="`${currentStep} dari {{ $survey->questions->count() }}`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                     :style="`width: ${(currentStep / {{ $survey->questions->count() }}) * 100}%`"></div>
            </div>
        </div>
    </div>

    <!-- Survey Form -->
    <form method="POST" action="{{ route('survey.submit', $survey) }}" @submit="validateForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($survey->questions as $index => $question)
                <div class="bg-white rounded-lg shadow-lg p-6" 
                     x-show="currentStep === {{ $index + 1 }}" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Pertanyaan {{ $index + 1 }}
                            @if($question->wajib_diisi)
                                <span class="text-red-500">*</span>
                            @endif
                        </h3>
                        <p class="text-gray-700">{{ $question->teks_pertanyaan }}</p>
                    </div>

                    <div class="space-y-3">
                        @switch($question->tipe_pertanyaan)
                            @case('teks')
                                <input type="text" 
                                       name="answers[{{ $question->id }}]" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Masukkan jawaban Anda..."
                                       @if($question->wajib_diisi) required @endif>
                                @break

                            @case('textarea')
                                <textarea name="answers[{{ $question->id }}]" 
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Masukkan jawaban Anda..."
                                          @if($question->wajib_diisi) required @endif></textarea>
                                @break

                            @case('radio')
                                @if($question->pilihan)
                                    @foreach($question->pilihan as $option)
                                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $option['value'] }}"
                                                   class="text-green-600 focus:ring-green-500"
                                                   @if($question->wajib_diisi) required @endif>
                                            <span class="text-gray-700">{{ $option['value'] }}</span>
                                        </label>
                                    @endforeach
                                @endif
                                @break

                            @case('checkbox')
                                @if($question->pilihan)
                                    @foreach($question->pilihan as $option)
                                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="answers[{{ $question->id }}][]" 
                                                   value="{{ $option['value'] }}"
                                                   class="text-green-600 focus:ring-green-500">
                                            <span class="text-gray-700">{{ $option['value'] }}</span>
                                        </label>
                                    @endforeach
                                @endif
                                @break

                            @case('select')
                                <select name="answers[{{ $question->id }}]" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        @if($question->wajib_diisi) required @endif>
                                    <option value="">Pilih jawaban...</option>
                                    @if($question->pilihan)
                                        @foreach($question->pilihan as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['value'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @break

                            @case('rating')
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $i }}" 
                                                   class="sr-only"
                                                   @if($question->wajib_diisi) required @endif>
                                            <i class="fas fa-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors rating-star" 
                                               data-rating="{{ $i }}"></i>
                                        </label>
                                    @endfor
                                    <span class="ml-4 text-sm text-gray-600">(1 = Sangat Buruk, 5 = Sangat Baik)</span>
                                </div>
                                @break

                            @case('tanggal')
                                <input type="date" 
                                       name="answers[{{ $question->id }}]" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       @if($question->wajib_diisi) required @endif>
                                @break

                            @case('angka')
                                <input type="number" 
                                       name="answers[{{ $question->id }}]" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Masukkan angka..."
                                       @if($question->wajib_diisi) required @endif>
                                @break
                        @endswitch
                    </div>

                    @error("answers.{$question->id}")
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <div class="flex justify-between items-center">
                <button type="button" 
                        @click="previousStep()" 
                        x-show="currentStep > 1"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Sebelumnya
                </button>
                
                <div class="flex space-x-4">
                    <button type="button" 
                            @click="nextStep()" 
                            x-show="currentStep < {{ $survey->questions->count() }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Selanjutnya
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    
                    <button type="submit" 
                            x-show="currentStep === {{ $survey->questions->count() }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Jawaban
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function surveyForm() {
    return {
        currentStep: 1,
        
        nextStep() {
            if (this.currentStep < {{ $survey->questions->count() }}) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        validateForm(event) {
            // Validasi tambahan jika diperlukan
            return true;
        }
    }
}

// Rating stars interaction
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const questionStars = this.parentElement.parentElement.querySelectorAll('.rating-star');
            
            questionStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            const questionStars = this.parentElement.parentElement.querySelectorAll('.rating-star');
            
            questionStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });
});
</script>
@endpush
@endsection