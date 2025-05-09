<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Looktrendy</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="//unpkg.com/alpinejs" ></script>
    @livewireStyles
</head>
<body class="bg-gray-100 font-sans flex w-screen h-screen">

    <!-- Sidebar -->
    <div class="flex">
        <div id="sidebar" class="bg-[#cea29c] text-white h-screen p-4 pt-8 relative duration-300 w-64">
                        <div class="absolute flex items-center justify-center bg-[#C89F9C] rounded-full -right-3 top-9 w-7 h-7 border border-white cursor-pointer"
                onclick="toggleSidebar()">
                <i id="toggle-icon" class="fas fa-chevron-left"></i>
            </div>
            
            <!-- Logo -->
            <div class="flex flex-col items-center justify-center mt-3 mb-6">
                <img src="/logotipo.png" alt="Logo" class="w-35 h-35 max-w-full max-h-full transition-all duration-300 ease-in-out" id="logo-img">
                <h2 class="text-sm font-semibold text-white text-center truncate" id="logo-text">
                    {{ Auth::user()->User_FirstName }}
                </h2>
            </div>
            <!-- Menú -->
            <nav class="mt-10">
                <div class="space-y-2">
                    <!-- Inicio -->
                    <a href="{{ route('inicio') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86]" wire:navigate>
                        <i class="fas fa-home text-xl"></i>
                        <span id="menu-text-1" class="ml-4 duration-200">Inicio</span>
                    </a>
                    
                    <!-- Compras -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] cursor-pointer">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="menu-text-2" class="ml-4 duration-200 flex-1">Compras</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2" style="border-color: #CC8B86;">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-list text-lg"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                            <a href="{{ route('transaction') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Nueva Compra</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Crédito -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] cursor-pointer">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="menu-text-7" class="ml-4 duration-200 flex-1">Credito</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2" style="border-color: #CC8B86;">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-list text-lg"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                            <a href="{{ route('creditos') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Nuevo Credito</span>
                            </a>
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span class="ml-3">Abono Credito</span>
                            </a>
                        </div>
                    </a>
                    
                    <!-- Gestión de Usuarios -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] cursor-pointer">
                            <i class="fas fa-users text-xl"></i>
                            <span id="menu-text-4" class="ml-4 duration-200 flex-1">Gestión Usuarios</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2" style="border-color: #CC8B86;">
                            <a href="{{ route('usuarios') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm">
                                <i class="fas fa-user text-lg"></i>
                                <span class="ml-3">Usuarios</span>
                            </a>
                            <a href="{{ route('clientes') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-truck text-lg"></i>
                                <span class="ml-3">Clientes</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Inventario -->
                    <div x-data="{ open: false }">
                        <div @click="open = !open" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] cursor-pointer">
                            <i class="fas fa-boxes text-xl"></i>
                            <span id="menu-text-5" class="ml-4 duration-200 flex-1">Inventario</span>
                            <i class="fas fa-chevron-down text-xs duration-200 transform" :class="{'rotate-180': open}"></i>
                        </div>
                        <div x-show="open" x-collapse class="ml-8 pl-2 border-l-2" style="border-color: #CC8B86;">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-clipboard-list text-lg"></i>
                                <span class="ml-3">Inventario</span>
                            </a>
                            <a href="{{ route('productos') }}" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86] text-sm" wire:navigate>
                                <i class="fas fa-box-open text-lg"></i>
                                <span class="ml-3">Productos</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Configuración -->
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-[#CC8B86]" wire:navigate>
                        <i class="fas fa-cog text-xl"></i>
                        <span id="menu-text-6" class="ml-4 duration-200">Configuración</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
    
    <div id="page-transition" class="flex-1 p-8 opacity-0 transition-opacity duration-500">
        <main class="flex-1 p-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @livewireScripts

    <script>
         window.addEventListener('DOMContentLoaded', () => {
        const page = document.getElementById('page-transition');
        if (page) {
            page.classList.remove('opacity-0');
            page.classList.add('opacity-100');
        }
    });

    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            const page = document.getElementById('page-transition');
            if (page) {
                e.preventDefault();
                page.classList.add('opacity-0');
                setTimeout(() => {
                    window.location.href = this.href;
                }, 500); 
            }
        });
    });
        function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggle-icon');
    const logoImg = document.getElementById('logo-img');
    const logoText = document.getElementById('logo-text');

    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('w-20');

    toggleIcon.classList.toggle('fa-chevron-left');
    toggleIcon.classList.toggle('fa-chevron-right');

    if (sidebar.classList.contains('w-20')) {
        logoImg.classList.remove('w-35', 'h-35');
        logoImg.classList.add('w-16', 'h-16');
        logoText.classList.add('hidden');
    } else {
        logoImg.classList.remove('w-16', 'h-16');
        logoImg.classList.add('w-35', 'h-35');
        logoText.classList.remove('hidden');
    }

    const elementsToToggle = [
        'menu-text-1', 'menu-text-2', 'menu-text-3', 
        'menu-text-4', 'menu-text-5', 'menu-text-6', 'menu-text-7'
    ];

    elementsToToggle.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.classList.toggle('hidden');
    });
}

    </script>
</body>
</html>