<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Local Government Unit 2')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        /* Custom styles for better navigation */
        .nav-item {
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        .nav-item:hover {
            transform: translateY(-2px);
        }
        .mobile-menu {
            transition: max-height 0.3s ease-out;
            overflow: hidden;
        }
    </style>

    @stack('styles')
</head>
<body class="font-montserrat text-gray-900 bg-gray-100 min-h-screen">
    
    <!-- Simple Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container max-w-full px-4 py-3">
            <div class="flex justify-between items-center">
                
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center mr-3">
                        <img
                            src="{{ asset('images/tamo.jpg') }}"
                            alt="Tamo"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-blue-700">Pasong Tamo Disaster Alert System</h1>
                        <p class="text-xs text-gray-600">Local Government Unit</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <!-- Home -->
                    <a href="/" class="nav-item px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg font-medium">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    
                    <!-- Report Incident -->
                    <a href="{{route('report.create')}}" class="nav-item px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Report Incident
                    </a>
                    
                    <!-- Evacuation Area -->
                    <a href="{{route('map')}}" class="nav-item px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg font-medium">
                        <i class="fas fa-map-marker-alt mr-2"></i>Evacuation Area
                    </a>
                    
                    <!-- Emergency Lists -->
                    <a href="{{ route('emergency') }}" class="nav-item px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg font-medium">
                        <i class="fas fa-phone-alt mr-2"></i>Emergency Lists
                    </a>
                    <a href="{{ route('login') }}" class="nav-item px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu md:hidden max-h-0">
                <div class="pt-4 pb-3 space-y-1">
                    <a href="/" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg font-medium">
                        <i class="fas fa-home mr-3"></i>Home
                    </a>
                    <a href="/report" class="block px-4 py-3 text-gray-700 hover:bg-red-50 rounded-lg font-medium">
                        <i class="fas fa-exclamation-triangle mr-3"></i>Report Incident
                    </a>
                    <a href="/evacuation" class="block px-4 py-3 text-gray-700 hover:bg-green-50 rounded-lg font-medium">
                        <i class="fas fa-map-marker-alt mr-3"></i>Evacuation Area
                    </a>
                    <a href="{{ route('emergency') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 rounded-lg font-medium">
                        <i class="fas fa-phone-alt mr-3"></i>Emergency Lists
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Emergency Alert Banner (Optional) -->
    <div class="bg-red-600 text-white">
        <div class="container mx-auto px-4 py-2 text-center">
            <i class="fas fa-bell mr-2"></i>
                <span class="font-medium">Emergency Hotline: 911 | LGU Hotline: 122</span>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Simple Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
                <p class="mb-2">© 2026 Pasong Tamo Management. All rights reserved.</p>
            <p class="text-gray-400 text-sm">Serving our community with care and dedication.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            if (menu.classList.contains('max-h-0')) {
                menu.classList.remove('max-h-0');
                menu.style.maxHeight = menu.scrollHeight + 'px';
                this.innerHTML = '<i class="fas fa-times text-xl"></i>';
            } else {
                menu.classList.add('max-h-0');
                menu.style.maxHeight = '0';
                this.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            const isClickInsideMenu = menu.contains(event.target);
            const isClickButton = button.contains(event.target);
            
            if (!isClickInsideMenu && !isClickButton && !menu.classList.contains('max-h-0')) {
                menu.classList.add('max-h-0');
                menu.style.maxHeight = '0';
                button.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        });

        // Highlight current page in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('nav a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    // Desktop styling
                    if (link.classList.contains('nav-item')) {
                        link.classList.remove('text-gray-700', 'hover:bg-blue-50', 'hover:text-blue-700');
                        link.classList.add('bg-blue-100', 'text-blue-700');
                    }
                    // Mobile styling
                    else {
                        link.classList.remove('text-gray-700', 'hover:bg-blue-50');
                        link.classList.add('bg-blue-100', 'text-blue-700');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
