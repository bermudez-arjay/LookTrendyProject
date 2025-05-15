<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looktrendy</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
        .animate-slideIn {
            animation: slideIn 0.2s ease-out forwards;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .page-transition {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .page-leave {
            opacity: 1;
            transform: translateY(0);
        }
        .page-leave-active {
            opacity: 0;
            transform: translateY(10px);
        }
        .page-enter {
            opacity: 0;
            transform: translateY(10px);
        }
        .page-enter-active {
            opacity: 1;
            transform: translateY(0);
        }
        .menu-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .menu-item:hover {
            transform: translateX(5px);
        }
        .gradient-bg {
            background: linear-gradient(135deg, hsl(0, 0%, 100%) 0%, #ffffff 100%);
        }
        .logo-container {
            transition: all 0.3s ease;
          
        }
        .logo-expanded {
            transform: scale(1.1);
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ed3aa8 0%, #d13bf6 100%);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .active-menu-item {
            background: rgba(255, 255, 255, 0.61);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #7c3aed;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar Moderno Integrado con Logo -->
    <div x-data="{ expanded: true, activeMenu: '' }" class="flex">
        <div class="gradient-bg border-r border-purple-100 h-screen p-4 pt-6 relative duration-300 transition-all ease-in-out shadow-lg"
            :class="expanded ? 'w-64' : 'w-20'">
            
            <!-- Toggle Button -->
            <button @click="expanded = !expanded" 
                class="absolute flex items-center justify-center bg-white rounded-full -right-3 top-6 w-7 h-7 border-2 border-purple-200 shadow-md hover:shadow-lg transition-all hover:scale-110 hover:border-purple-300 z-10">
                <i x-show="expanded" class="fas fa-chevron-left text-purple-600 text-xs"></i>
                <i x-show="!expanded" class="fas fa-chevron-right text-purple-600 text-xs"></i>
            </button>
            
            <!-- Logo Integrado con Menú -->
            <div class="flex flex-col items-center justify-center mb-8">
                <div class="relative group">
                    <div class="logo-container w-20 h-20 p-2 rounded-full bg-white shadow-lg transition-all duration-300 flex items-center justify-center"
                         :class="{'logo-expanded': expanded, 'animate-float': expanded}">
                        <img src="/logotipo.png" alt="Logo" 
                            class="transition-all duration-300 ease-in-out rounded-full object-cover border-1 border-white"
                            :class="expanded ? 'w-16 h-16' : 'w-12 h-12'">
                    </div>
                  
                    <h2 x-show="expanded" 
                        class="font-bold text-purple-900 text-lg mt-3 text-center animate-slideIn tracking-tight">
                        LookTrendy
                    </h2>
                    <p x-show="expanded" class="text-xs text-purple-600 mt-1 animate-fadeIn">Fashion & Style</p>
                </div>
            </div>
            <!-- Menú Moderno -->
            <nav class="mt-6 space-y-1">
                <!-- Inicio -->
                <a href="{{ route('inicio') }}" 
                   x-on:click="activeMenu = 'inicio'"
                   class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all group menu-item nav-link overflow-hidden"
                   :class="activeMenu === 'inicio' ? 'active-menu-item' : ''"
                   wire:navigate>
                    <div class="relative flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-all">
                            <i class="fas fa-home text-purple-600 group-hover:text-purple-700"></i>
                        </div>
                        <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 animate-fadeIn">Inicio</span>
                    </div>
                </a>
                
                @auth
                    @if(Auth::user()->User_Role === 'Administrador')
                        <!-- Compras -->
                        <div x-data="{ open: false }">
                            <div @click="open = !open; expanded = true; activeMenu = 'compras'" 
                                class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                                :class="{'active-menu-item': activeMenu === 'compras'}">
                                <div class="w-8 h-8 flex items-center justify-center bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-all">
                                    <i class="fas fa-shopping-cart text-blue-600 group-hover:text-blue-700"></i>
                                </div>
                                <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Compras</span>
                                <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                                :class="{'rotate-180': open}"></i>
                            </div>
                            <div x-show="open && expanded" x-collapse 
                                class="ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('transaction') }}" 
                                class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                                    <span>Nueva Compra</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                
                <!-- Crédito -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu = 'credito'" 
                         class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'credito'}">
                        <div class="w-8 h-8 flex items-center justify-center bg-green-100 rounded-lg group-hover:bg-green-200 transition-all">
                            <i class="fas fa-credit-card text-green-600 group-hover:text-green-700"></i>
                        </div>
                        <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Crédito</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>
                    <div x-show="open && expanded" x-collapse 
                         class="ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                        <a href="#" class="flex items-center p-2 rounded-lg hover:bg-green-50 text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('creditos') }}" 
                           class="flex items-center p-2 rounded-lg hover:bg-green-50 text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-plus text-green-500 mr-2"></i>
                            <span>Nuevo Crédito</span>
                        </a>
                        <a href="{{ route('abonos') }}" 
                           class="flex items-center p-2 rounded-lg hover:bg-green-50 text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-hand-holding-usd text-green-500 mr-2"></i>
                            <span>Abono Crédito</span>
                        </a>
                    </div>
                </div>
                
                @auth
                    @if(Auth::user()->User_Role === 'Administrador')
                        <!-- Gestión de Usuarios -->
                    <div x-data="{ open: false }">
                                <div @click="open = !open; expanded = true; activeMenu = 'usuarios'" 
                                    class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                                    :class="{'active-menu-item': activeMenu === 'usuarios'}">
                                    <div class="w-8 h-8 flex items-center justify-center bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-all">
                                        <i class="fas fa-users text-amber-600 group-hover:text-amber-700"></i>
                                    </div>
                                    <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Usuarios</span>
                                    <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                                    :class="{'rotate-180': open}"></i>
                                </div>
                                <div x-show="open && expanded" x-collapse 
                                    class="ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                    <a href="{{ route('usuarios') }}" 
                                    class="flex items-center p-2 rounded-lg hover:bg-amber-50 text-sm transition-all"
                                    wire:navigate>
                                        <i class="fas fa-user-cog text-amber-500 mr-2"></i>
                                        <span>Usuarios</span>
                                    </a>
                                    <a href="{{ route('clientes') }}" 
                                    class="flex items-center p-2 rounded-lg hover:bg-amber-50 text-sm transition-all"
                                    wire:navigate>
                                        <i class="fas fa-users text-amber-500 mr-2"></i>
                                        <span>Clientes</span>
                                    </a>
                                </div>
                                <div x-show="open && expanded" x-collapse 
                                    class="ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                    <a href="{{ route('proveedores') }}" 
                                    class="flex items-center p-2 rounded-lg hover:bg-amber-50 text-sm transition-all"
                                    wire:navigate>
                                        <i class="fas fa-truck text-amber-500 mr-2"></i>
                                        <span>Proveedores</span>
                                    </a>
                                    
                                </div>
                            </div>
                    @endif
                @endauth
                
                
                <!-- Inventario -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu = 'inventario'" 
                         class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'inventario'}">
                        <div class="w-8 h-8 flex items-center justify-center bg-rose-100 rounded-lg group-hover:bg-rose-200 transition-all">
                            <i class="fas fa-box-open text-rose-600 group-hover:text-rose-700"></i>
                        </div>
                        <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Inventario</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>
                    <div x-show="open && expanded" x-collapse 
                         class="ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                        <a href="{{route('inventario')}}" class="flex items-center p-2 rounded-lg hover:bg-rose-50 text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-clipboard-list text-rose-500 mr-2"></i>
                            <span>Inventario</span>
                        </a>
                        <a href="{{ route('productos') }}" 
                           class="flex items-center p-2 rounded-lg hover:bg-rose-50 text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-boxes text-rose-500 mr-2"></i>
                            <span>Productos</span>
                        </a>
                    </div>
                </div>

                @auth
                        @if(Auth::user()->User_Role === 'Administrador')
                            <!-- Configuración -->
                            <a href="#" 
                            x-on:click="activeMenu = 'configuracion'"
                            class="relative flex items-center p-3 rounded-xl hover:bg-white hover:shadow-md transition-all group menu-item nav-link overflow-hidden"
                            :class="activeMenu === 'configuracion' ? 'active-menu-item' : ''"
                            wire:navigate>
                                <div class="w-8 h-8 flex items-center justify-center bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-all">
                                    <i class="fas fa-cog text-indigo-600 group-hover:text-indigo-700"></i>
                                </div>
                                <span x-show="expanded" class="ml-3 text-sm font-medium text-purple-800 animate-fadeIn">Configuración</span>
                            </a>
                        @endif
                @endauth
                
                
            </nav>
            
            <!-- User Profile (Bottom) -->
            <div x-show="expanded" class="absolute bottom-4 left-0 right-0 px-4 animate-slideIn">
                <div class="flex items-center p-3 bg-white rounded-xl shadow-sm border border-purple-100 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div class="relative">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full border-2 border-white shadow">
                        <div class="absolute -bottom-1 -right-1 bg-green-400 rounded-full w-3 h-3 border-2 border-white"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-800">{{ Auth::user()->User_FirstName }}</p>
                        <p class="text-xs text-purple-600">{{ Auth::user()->User_Role }}</p>
                    </div>
                    <button class="ml-auto text-purple-500 hover:text-purple-700 transition-transform hover:rotate-90">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
 
    <div class="flex-1 flex flex-col overflow-hidden">
   
        <main class="flex-1 overflow-y-auto p-6 page-transition">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    <script>
 
        document.addEventListener('livewire:navigating', () => {
            const main = document.querySelector('main');
            main.style.opacity = '0';
            main.style.transform = 'translateY(10px)';
        });

        document.addEventListener('livewire:navigated', () => {
            const main = document.querySelector('main');
            setTimeout(() => {
                main.style.opacity = '1';
                main.style.transform = 'translateY(0)';
            }, 50);
        });

           document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateX(5px)';
            });
            item.addEventListener('mouseleave', () => {
                item.style.transform = '';
            });
        });

      
        document.addEventListener('DOMContentLoaded', () => {
            const logo = document.querySelector('.logo-container');
            setTimeout(() => {
                logo.classList.add('logo-expanded');
            }, 300);
        });
    </script>
</body>
</html>