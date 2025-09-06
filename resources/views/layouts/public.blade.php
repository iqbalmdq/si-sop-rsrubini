<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SI-SOP RS Rubini Mempawah')</title>

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
                <div class="flex items-center space-x-4">
                    <i class="fas fa-hospital text-2xl"></i>
                    <div>
                        <h1 class="text-xl font-bold">SI-SOP</h1>
                        <p class="text-sm text-blue-100">Rumah Sakit Rubini Mempawah</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('sop.index') }}" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('filament.direktur.auth.login') }}" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-user-tie mr-2"></i>Login Direktur
                    </a>
                    <a href="{{ route('filament.bidang.auth.login') }}" class="hover:text-blue-200 transition-colors">
                        <i class="fas fa-user mr-2"></i>Login Bidang
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">SI-SOP RS Rubini</h3>
                    <p class="text-gray-300">Sistem Informasi Dokumen Standard Operating Procedure untuk mempermudah
                        pencarian dan pengelolaan SOP di Rumah Sakit Rubini Mempawah.</p>
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
                            <i class="fas fa-search mr-2"></i>Pencarian SOP
                        </a>
                        <a href="{{ route('filament.direktur.auth.login') }}"
                            class="block text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>Dashboard Direktur
                        </a>
                        <a href="{{ route('filament.bidang.auth.login') }}"
                            class="block text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-user mr-2"></i>Portal Bidang
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
