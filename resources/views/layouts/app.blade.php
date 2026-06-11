<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soto Mba Ratih - POS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'Segoe UI', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            background-color: #f7f4e9;
        }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex flex-col md:flex-row font-sans" x-data="{ mobileSidebarOpen: false }">
    
    <div class="md:hidden bg-[#c5cb9f] text-[#6a4f21] px-4 py-3 flex items-center justify-between fixed top-0 left-0 right-0 z-40 shadow-sm">
        <div class="flex flex-col">
            <h1 class="font-bold text-base">Soto Mba Ratih</h1>
            <p class="text-[10px] text-[#6a4f21]/80 font-medium">Point of Sales</p>
        </div>
        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-1 text-[#6a4f21] hover:bg-black/5 rounded-lg transition focus:outline-none">
            <span class="material-icons-outlined !text-[26px]" x-text="mobileSidebarOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <div x-show="mobileSidebarOpen" 
         @click="mobileSidebarOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/40 z-40 md:hidden"
         x-cloak>
    </div>
    
    <x-sidebar />

    <main class="flex-1 p-4 md:p-8 pt-20 md:pt-8 md:ml-64 w-full min-w-0 transition-all duration-300">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jam', () => ({
                waktu: '',
                init() {
                    this.updateTime();
                    setInterval(() => {
                        this.updateTime();
                    }, 1000);
                },
                updateTime() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    this.waktu = hours + ':' + minutes;
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>