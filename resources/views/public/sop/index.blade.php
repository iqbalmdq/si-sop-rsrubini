@extends('layouts.public')

@section('title', 'Pencarian SOP - RS Rubini Mempawah')

@section('content')
<div x-data="sopSearch()" x-init="init()">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 mb-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-file-medical mr-3"></i>
                Pencarian Dokumen SOP
            </h1>
            <p class="text-xl text-blue-100 mb-6">
                Temukan Standard Operating Procedure (SOP) dengan mudah dan cepat
            </p>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 inline-block">
                <div class="flex items-center justify-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-2xl mr-2"></i>
                        <div>
                            <div class="font-semibold">{{ $totalSop }}</div>
                            <div class="text-blue-200">Total SOP Aktif</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-tags text-2xl mr-2"></i>
                        <div>
                            <div class="font-semibold">{{ $kategoris->count() }}</div>
                            <div class="text-blue-200">Kategori</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="grid md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    Cari SOP
                </label>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    @input.debounce.500ms="search()"
                    placeholder="Masukkan nomor SOP, judul, atau kata kunci..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1"></i>
                    Kategori
                </label>
                <select 
                    x-model="selectedKategori"
                    @change="search()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Bidang Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-building mr-1"></i>
                    Bidang/Bagian
                </label>
                <select 
                    x-model="selectedBidang"
                    @change="search()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Bidang</option>
                    <template x-for="bidang in bidangList" :key="bidang">
                        <option :value="bidang" x-text="bidang"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Loading State -->
    <div x-show="loading" x-cloak class="text-center py-8">
        <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
        <p class="text-gray-600">Mencari SOP...</p>
    </div>
    
    <!-- Search Results -->
    <div x-show="!loading && searchResults.length > 0" x-cloak>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-list mr-2"></i>
                Hasil Pencarian
                <span class="text-sm font-normal text-gray-600" x-text="`(${pagination.total} SOP ditemukan)`"></span>
            </h2>
        </div>
        
        <div class="space-y-4">
            <template x-for="sop in searchResults" :key="sop.id">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full" x-text="sop.nomor_sop"></span>
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full" x-text="sop.kategori.nama_kategori"></span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2" x-text="sop.judul_sop"></h3>
                            <p class="text-gray-600 mb-3" x-text="sop.deskripsi"></p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span><i class="fas fa-building mr-1"></i><span x-text="sop.bidang_bagian"></span></span>
                                <span><i class="fas fa-calendar mr-1"></i><span x-text="formatDate(sop.tanggal_berlaku)"></span></span>
                                <span><i class="fas fa-code-branch mr-1"></i>Versi <span x-text="sop.versi"></span></span>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2 ml-4">
                            <a :href="`/sop/${sop.nomor_sop}`" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center">
                                <i class="fas fa-eye mr-2"></i>Lihat
                            </a>
                            <button x-show="sop.file_path" @click="openPreview(sop)" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-center">
                                <i class="fas fa-file-pdf mr-2"></i>Preview
                            </button>
                            <a x-show="sop.file_path" :href="`/sop/${sop.nomor_sop}/download`" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Pagination -->
        <div x-show="pagination.last_page > 1" class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2">
                <button 
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page <= 1"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <template x-for="page in getPageNumbers()" :key="page">
                    <button 
                        @click="changePage(page)"
                        :class="{
                            'bg-blue-600 text-white': page === pagination.current_page,
                            'bg-white text-gray-700 hover:bg-gray-50': page !== pagination.current_page
                        }"
                        class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md"
                        x-text="page"
                    ></button>
                </template>
                
                <button 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page >= pagination.last_page"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-chevron-right"></i>
                </button>
            </nav>
        </div>
    </div>
    
    <!-- Preview Modal -->
    <div x-show="showPreview" x-cloak class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <div class="text-lg font-semibold">Preview Dokumen SOP</div>
                <button @click="closePreview()" class="text-gray-600 hover:text-gray-800"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-0">
                <iframe :src="previewUrl" class="w-full h-[75vh]" allow="fullscreen"></iframe>
            </div>
        </div>
    </div>

    <!-- No Results -->
    <div x-show="!loading && searchResults.length === 0 && hasSearched" x-cloak class="text-center py-12">
        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada SOP ditemukan</h3>
        <p class="text-gray-500">Coba ubah kata kunci pencarian atau filter yang digunakan.</p>
    </div>
    
    <!-- Categories Grid (when no search) -->
    <div x-show="!loading && !hasSearched" x-cloak>
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">
            <i class="fas fa-th-large mr-2"></i>
            Kategori SOP
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kategoris as $kategori)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6 cursor-pointer" @click="filterByKategori({{ $kategori->id }})">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                            <i class="fas fa-folder text-xl"></i>
                        </div>
                        <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded-full">
                            {{ $kategori->sops_count }} SOP
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $kategori->nama_kategori }}</h3>
                    <p class="text-gray-600 text-sm">{{ $kategori->deskripsi }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function sopSearch() {
    return {
        searchQuery: '',
        selectedKategori: '',
        selectedBidang: '',
        searchResults: [],
        bidangList: [],
        loading: false,
        hasSearched: false,
        showPreview: false,
        previewUrl: '',
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0
        },
        
        async init() {
            await this.loadBidangList();
        },
        
        async loadBidangList() {
            try {
                const response = await fetch('/sop/bidang');
                this.bidangList = await response.json();
            } catch (error) {
                console.error('Error loading bidang list:', error);
            }
        },
        
        async search(page = 1) {
            const query = (this.searchQuery || '').trim();
            const kategori = this.selectedKategori;
            const bidang = this.selectedBidang;

            if (query === '' && !kategori && !bidang) {
                this.hasSearched = false;
                this.searchResults = [];
                this.pagination = {
                    current_page: 1,
                    last_page: 1,
                    per_page: 10,
                    total: 0
                };
                this.loading = false;
                return;
            }

            this.loading = true;
            this.hasSearched = true;
            
            const params = new URLSearchParams({
                q: query,
                kategori: kategori,
                bidang: bidang,
                page: page
            });
            
            try {
                const response = await fetch(`/sop/search?${params}`);
                const data = await response.json();
                
                this.searchResults = data.data;
                this.pagination = data.pagination;
            } catch (error) {
                console.error('Error searching SOPs:', error);
                this.searchResults = [];
            } finally {
                this.loading = false;
            }
        },
        
        filterByKategori(kategoriId) {
            this.selectedKategori = kategoriId;
            this.search();
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.search(page);
            }
        },
        
        getPageNumbers() {
            const pages = [];
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            
            // Show max 5 pages
            let start = Math.max(1, current - 2);
            let end = Math.min(last, start + 4);
            
            if (end - start < 4) {
                start = Math.max(1, end - 4);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        },
        openPreview(sop) {
            this.previewUrl = `/sop/${sop.nomor_sop}/preview`;
            this.showPreview = true;
        },
        closePreview() {
            this.showPreview = false;
        }
    }
}
</script>
@endpush
@endsection
