<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI-Dok RS Rubini Mempawah')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('sop.index') }}" class="hover:text-blue-200 transition-colors">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-hospital text-2xl"></i>
                        <div>
                            <h1 class="text-xl font-bold">SI-DOK</h1>
                            <p class="text-sm text-blue-100">Rumah Sakit Rubini Mempawah</p>
                        </div>
                    </div>
                </a>
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('sop.index') }}" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-search mr-2"></i>Pencarian Dokumen
                    </a>
                    <a href="{{ route('survey.index') }}" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-poll mr-2"></i>Survei Publik
                    </a>

                    <!-- Login Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="hover:text-blue-200 transition-colors flex items-center focus:outline-none">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 text-gray-800" 
                             style="display: none;">
                            <a href="https://sisop.teknoborneo.com/public/direktur/login" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-user-tie mr-2 w-5"></i>Login Direktur
                            </a>
                            <a href="https://sisop.teknoborneo.com/public/bidang/login" class="block px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-users mr-2 w-5"></i>Login Bidang
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" x-data @click="$refs.mobileMenu.classList.toggle('hidden')">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-ref="mobileMenu" class="hidden md:hidden mt-4 pb-4">
                <div class="space-y-2">
                    <a href="{{ route('sop.index') }}" class="block py-2 hover:text-blue-200 transition-colors">
                        <i class="fas fa-search mr-2"></i>Pencarian Dokumen
                    </a>
                    <a href="{{ route('survey.index') }}" class="block py-2 hover:text-blue-200 transition-colors">
                        <i class="fas fa-poll mr-2"></i>Survei Publik
                    </a>
                    
                    <!-- Mobile Login Menu -->
                    <div x-data="{ open: false }" class="border-t border-blue-500 pt-2 mt-2">
                        <button @click="open = !open" class="w-full text-left py-2 hover:text-blue-200 transition-colors flex items-center justify-between focus:outline-none">
                            <span><i class="fas fa-sign-in-alt mr-2"></i>Login</span>
                            <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="pl-6 space-y-2 mt-1" 
                             style="display: none;">
                            <a href="https://sisop.teknoborneo.com/public/direktur/login" class="block py-2 text-sm hover:text-blue-200">
                                <i class="fas fa-user-tie mr-2 w-5"></i>Login Direktur
                            </a>
                            <a href="https://sisop.teknoborneo.com/public/bidang/login" class="block py-2 text-sm hover:text-blue-200">
                                <i class="fas fa-users mr-2 w-5"></i>Login Bidang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">SI-DOK RS Rubini</h3>
                    <p class="text-gray-300">Sistem Informasi Dokumen (SI-DOK) untuk mempermudah
                        pencarian dan pengelolaan Dokumen di Rumah Sakit Rubini Mempawah.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="text-gray-300 space-y-2">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Mempawah, Kalimantan Barat</p>
                        <p><i class="fas fa-phone mr-2"></i>(0561) xxx-xxxx</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@rsrubini.com</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Akses Cepat</h3>
                    <div class="space-y-2">
                        <a href="{{ route('sop.index') }}"
                            class="block text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-search mr-2"></i>Pencarian Dokumen
                        </a>
                        <a href="{{ route('survey.index') }}"
                            class="block text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-poll mr-2"></i>Survei Publik
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} RS Rubini Mempawah. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
