@extends('layouts.public')

@section('title', $sop->judul_sop . ' - RS Rubini Mempawah')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('sop.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">{{ $sop->nomor_sop }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- SOP Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $sop->nomor_sop }}
                        </span>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $sop->kategori->nama_kategori }}
                        </span>
                        <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                            Versi {{ $sop->versi }}
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $sop->judul_sop }}</h1>
                    <p class="text-gray-600 mb-4">{{ $sop->deskripsi }}</p>

                    <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-building text-blue-600 mr-2"></i>
                            <span class="font-medium">Bidang/Bagian:</span>
                            <span class="ml-2">{{ $sop->bidang_bagian }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-green-600 mr-2"></i>
                            <span class="font-medium">Berlaku:</span>
                            <span class="ml-2">{{ $sop->tanggal_berlaku->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user text-purple-600 mr-2"></i>
                            <span class="font-medium">Dibuat oleh:</span>
                            <span class="ml-2">{{ $sop->creator->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-orange-600 mr-2"></i>
                            <span class="font-medium">Terakhir update:</span>
                            <span class="ml-2">{{ $sop->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-2 ml-6">
                    @php
                        $hasFile =
                            \Illuminate\Support\Facades\Storage::disk('public')->exists($sop->file_path) ||
                            \Illuminate\Support\Facades\Storage::disk('local')->exists($sop->file_path);
                    @endphp
                    @if ($sop->file_path && $hasFile)
                        <a href="{{ route('sop.preview', $sop->nomor_sop) }}"
                            target="_blank"
                            class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-center">
                            <i class="fas fa-file-pdf mr-2"></i>Preview
                        </a>
                        <a href="{{ route('sop.download', $sop->nomor_sop) }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- SOP Content -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                Isi SOP
            </h2>
            <div class="prose max-w-none">
                {!! $sop->isi_sop !!}
            </div>
        </div>

        <!-- Related SOPs -->
        @if ($relatedSops->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-link text-blue-600 mr-2"></i>
                    SOP Terkait
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach ($relatedSops as $relatedSop)
                        <a href="{{ route('sop.show', $relatedSop->nomor_sop) }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-md transition-all">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                    {{ $relatedSop->nomor_sop }}
                                </span>
                            </div>
                            <h3 class="font-medium text-gray-900 mb-1">{{ $relatedSop->judul_sop }}</h3>
                            <p class="text-sm text-gray-600">{{ Str::limit($relatedSop->deskripsi, 80) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function shareSOP() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $sop->judul_sop }}',
                        text: '{{ $sop->deskripsi }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        alert('Link SOP telah disalin ke clipboard!');
                    });
                }
            }

            // Print styles
            const printStyles = `
    @media print {
        body * { visibility: hidden; }
        .container, .container * { visibility: visible; }
        .container { position: absolute; left: 0; top: 0; width: 100%; }
        header, footer, nav, .no-print { display: none !important; }
    }
`;

            const styleSheet = document.createElement('style');
            styleSheet.textContent = printStyles;
            document.head.appendChild(styleSheet);
        </script>
    @endpush
@endsection
