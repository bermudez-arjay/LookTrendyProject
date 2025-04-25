<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Looktrendy</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans flex w-screen h-screen">

    <!-- Sidebar -->
    <div class="flex">
        <div id="sidebar" class="bg-[#2b3147] text-white h-screen p-4 pt-8 relative duration-300 w-64">
                        <div class="absolute flex items-center justify-center bg-blue-800 rounded-full -right-3 top-9 w-7 h-7 border border-white cursor-pointer"
                onclick="toggleSidebar()">
                <i id="toggle-icon" class="fas fa-chevron-left"></i>
            </div>
            
            <!-- Logo -->
            <div class="flex flex-col items-center justify-center mt-3 mb-6">
                <i class="fas fa-user-circle text-5xl text-white mb-2"></i>
                <h2 class="font-semibold text-white text-center" id="logo-text">
             {{-- {{ Auth::user()->User_FirstName }} --}}
                </h2>
            </div>
            
            <!-- Menú -->
            <nav class="mt-10">
                <div class="space-y-2">
                    <!-- Inicio -->
                    <a href="{{ route('inicio') }}" class="flex items-center p-2 rounded-lg hover:bg-blue-700" wire:navigate>
                        <i class="fas fa-home text-xl"></i>
                        <span id="menu-text-1" class="ml-4 duration-200">Inicio</span>
                    </a>
                    
                    <!-- Compras -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="menu-text-2" class="ml-4 duration-200 flex-1">Compras</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2 border-blue-600">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-list text-lg"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                            <a href="{{ route('transaction') }}" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Nueva Compra</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Crédito -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="menu-text-7" class="ml-4 duration-200 flex-1">Credito</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2 border-blue-600">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-list text-lg"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Nuevo Credito</span>
                            </a>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Abono Credito</span>
                            </a>
                        </div>
                    </a>
                    
                    <!-- Gestión de Usuarios -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                            <i class="fas fa-users text-xl"></i>
                            <span id="menu-text-4" class="ml-4 duration-200 flex-1">Gestión Usuarios</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2 border-blue-600">
                            <a href="{{ route('usuarios') }}" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm">
                                <i class="fas fa-user text-lg"></i>
                                <span class="ml-3">Usuarios</span>
                            </a>
                            <a href="{{ route('clientes') }}" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-truck text-lg"></i>
                                <span class="ml-3">Clientes</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Inventario -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                            <i class="fas fa-boxes text-xl"></i>
                            <span id="menu-text-5" class="ml-4 duration-200 flex-1">Inventario</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2 border-blue-600">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-clipboard-list text-lg"></i>
                                <span class="ml-3">Inventario</span>
                            </a>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700 text-sm" wire:navigate>
                                <i class="fas fa-box-open text-lg"></i>
                                <span class="ml-3">Productos</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Configuración -->
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-700" wire:navigate>
                        <i class="fas fa-cog text-xl"></i>
                        <span id="menu-text-6" class="ml-4 duration-200">Configuración</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
    
    <div class="flex-1 p-8">
        <main class="flex-1 p-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @livewireScripts

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            
            if (toggleIcon.classList.contains('fa-chevron-left')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
            }
            
            const elementsToToggle = [
                'logo-text', 'menu-text-1', 'menu-text-2', 'menu-text-3', 
                'menu-text-4', 'menu-text-5', 'menu-text-6','menu-text-7'
            ];
            
            elementsToToggle.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.toggle('hidden');
                    element.classList.toggle('opacity-0');
                }
            });
        }
    </script>
</body>
</html>