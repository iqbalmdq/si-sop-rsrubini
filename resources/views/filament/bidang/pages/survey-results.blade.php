<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->surveyInfolist }}
        
        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('analysis')" id="analysis-tab" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Analisis Hasil
                    </button>
                    <button onclick="showTab('responses')" id="responses-tab" 
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Detail Respons
                    </button>
                </nav>
            </div>
            
            <!-- Analysis Tab Content -->
            <div id="analysis-content" class="tab-content p-6">
                <h2 class="text-xl font-semibold mb-4">Analisis Hasil Survei</h2>
                
                @php
                    $resultsData = $this->getResultsData();
                @endphp
                
                @if(count($resultsData) === 0)
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data untuk Dianalisis</h3>
                        <p class="text-gray-500">Survei ini belum memiliki respons atau pertanyaan untuk dianalisis.</p>
                    </div>
                @else
                    @foreach($resultsData as $index => $question)
                        <div class="mb-8 p-4 border rounded-lg bg-gray-50">
                            <h3 class="text-lg font-medium mb-4 text-gray-800">
                                Pertanyaan {{ $index + 1 }}: {{ $question['question'] }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                <span class="font-medium">Total Respons:</span> {{ $question['total_responses'] }} orang
                            </p>
                            
                            @if($question['data']['chart_type'] === 'text')
                                <div class="space-y-2">
                                    <h4 class="font-medium text-gray-700 mb-3">Jawaban Responden:</h4>
                                    @if(count($question['data']['answers']) > 0)
                                        @foreach($question['data']['answers'] as $answerIndex => $answer)
                                            <div class="p-3 bg-white rounded border-l-4 border-blue-500 shadow-sm">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-xs text-gray-500 font-medium">{{ $answer['user'] }}</span>
                                                    <span class="text-xs text-gray-400">{{ $answer['date'] }}</span>
                                                </div>
                                                <p class="text-gray-800">{{ $answer['text'] }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-500 italic">Belum ada jawaban untuk pertanyaan ini.</p>
                                    @endif
                                </div>
                            @elseif($question['data']['chart_type'] === 'number')
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="bg-white p-4 rounded border">
                                        <h4 class="font-medium mb-3 text-gray-700">Statistik Angka:</h4>
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Rata-rata:</span>
                                                <span class="font-semibold text-blue-600">{{ $question['data']['average'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Nilai Minimum:</span>
                                                <span class="font-semibold text-green-600">{{ $question['data']['min'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Nilai Maksimum:</span>
                                                <span class="font-semibold text-red-600">{{ $question['data']['max'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total Respons:</span>
                                                <span class="font-semibold text-gray-800">{{ count($question['data']['values']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded border">
                                        <h4 class="font-medium mb-3 text-gray-700">Semua Jawaban:</h4>
                                        <div class="max-h-48 overflow-y-auto space-y-1">
                                            @foreach($question['data']['values'] as $value)
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-sm mr-1 mb-1">{{ $value }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="bg-white p-4 rounded border">
                                        <h4 class="font-medium mb-3 text-gray-700">Grafik Hasil:</h4>
                                        <div class="relative h-72 w-full">
                                            <canvas id="chart-{{ $index }}"></canvas>
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded border">
                                        <h4 class="font-medium mb-3 text-gray-700">Rincian Jawaban:</h4>
                                        <div class="space-y-2">
                                            @foreach($question['data']['labels'] as $labelIndex => $label)
                                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-medium text-blue-600">{{ $question['data']['values'][$labelIndex] }}</span>
                                                        <span class="text-xs text-gray-500">
                                                            ({{ $question['total_responses'] > 0 ? round(($question['data']['values'][$labelIndex] / $question['total_responses']) * 100, 1) : 0 }}%)
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if(isset($question['data']['average']))
                                            <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-200">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-blue-800">Rata-rata Penilaian:</span>
                                                    <span class="text-xl font-bold text-blue-600">{{ $question['data']['average'] }}/5</span>
                                                </div>
                                                <div class="mt-2 flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $question['data']['average'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- Responses Tab Content -->
            <div id="responses-content" class="tab-content p-6 hidden">
                <h2 class="text-xl font-semibold mb-4">Detail Respons Individu</h2>
                
                @php
                    $detailedResponses = $this->getDetailedResponses();
                @endphp
                
                @if(count($detailedResponses) === 0)
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Respons</h3>
                        <p class="text-gray-500">Survei ini belum memiliki respons dari responden.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($detailedResponses as $response)
                            <div class="bg-white border rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $response['user'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $response['user_bidang'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Respons #{{ $response['id'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $response['submit_time'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    @foreach($response['answers'] as $answer)
                                        <div class="border-l-4 border-blue-200 pl-4">
                                            <p class="font-medium text-gray-800 text-sm">{{ $answer['question'] }}</p>
                                            <p class="text-gray-700 mt-1">{{ $answer['answer'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            showTab('analysis');
            
            @foreach($resultsData as $index => $question)
                @if($question['data']['chart_type'] !== 'text' && $question['data']['chart_type'] !== 'number')
                    const ctx{{ $index }} = document.getElementById('chart-{{ $index }}').getContext('2d');
                    new Chart(ctx{{ $index }}, {
                        type: '{{ $question['data']['chart_type'] }}',
                        data: {
                            labels: @json($question['data']['labels']),
                            datasets: [{
                                label: 'Jumlah Respons',
                                data: @json($question['data']['values']),
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(34, 197, 94, 0.8)',
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(168, 85, 247, 0.8)',
                                    'rgba(236, 72, 153, 0.8)',
                                    'rgba(20, 184, 166, 0.8)',
                                    'rgba(251, 146, 60, 0.8)',
                                ],
                                borderColor: [
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(34, 197, 94, 1)',
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(168, 85, 247, 1)',
                                    'rgba(236, 72, 153, 1)',
                                    'rgba(20, 184, 166, 1)',
                                    'rgba(251, 146, 60, 1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            },
                            @if($question['data']['chart_type'] === 'bar')
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                            @endif
                        }
                    });
                @endif
            @endforeach
        });
    </script>
    @endpush
</x-filament-panels::page>