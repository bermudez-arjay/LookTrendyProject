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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite('resources/js/app.js')
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

        /* Estilos responsivos añadidos */
        @media (max-width: 768px) {
            .gradient-bg {
                width: 4.5rem !important;
            }
            
            .gradient-bg:not(:hover) {
                overflow: hidden;
            }
            
            .logo-container {
                width: 3rem !important;
                height: 3rem !important;
            }
            
            .logo-container img {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            
            .user-profile-mobile {
                display: flex !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 0.5rem;
                border-top: 1px solid #e9d8fd;
                z-index: 40;
            }
            
            .menu-item {
                padding: 0.5rem !important;
            }
            
            .submenu-item {
                padding-left: 0.5rem !important;
            }

            .sidebar-expanded-mobile {
                width: 16rem !important;
                z-index: 50;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
        }
    </style>
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 font-sans antialiased flex h-screen overflow-hidden">
    <div x-data="{ 
            expanded: window.innerWidth > 768, 
            activeMenu: '',
            isMobile: window.innerWidth <= 768,
            forceExpand: false
        }" 
        class="flex"
        x-init="
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth <= 768;
                if (!isMobile) {
                    expanded = true;
                    forceExpand = false;
                } else if (!forceExpand) {
                    expanded = false;
                }
            });
        ">
        
        <!-- Sidebar mejorado para móviles -->
        <div class="gradient-bg border-r border-purple-100 h-screen p-2 md:p-4 pt-4 md:pt-6 relative duration-300 transition-all ease-in-out shadow-lg"
            :class="{
                'w-64': expanded && !isMobile,
                'w-20': !expanded && !isMobile,
                'sidebar-expanded-mobile': expanded && isMobile,
                'w-18': !expanded && isMobile
            }"
            @click.away="if(isMobile && !forceExpand) expanded = false">
            
            <!-- Botón de toggle mejorado -->
            <button @click="expanded = !expanded; if(isMobile) forceExpand = expanded" 
                class="absolute flex items-center justify-center bg-white rounded-full -right-3 top-6 w-7 h-7 border-2 border-purple-200 shadow-md hover:shadow-lg transition-all hover:scale-110 hover:border-purple-300 z-10">
                <i x-show="expanded" class="fas fa-chevron-left text-purple-600 text-xs"></i>
                <i x-show="!expanded" class="fas fa-chevron-right text-purple-600 text-xs"></i>
            </button>
            
            <!-- Logo -->
            <div class="flex flex-col items-center justify-center mb-6 md:mb-8">
                <div class="relative group">
                    <div class="logo-container w-16 h-16 md:w-20 md:h-20 p-2 rounded-full bg-white shadow-lg transition-all duration-300 flex items-center justify-center"
                         :class="{'logo-expanded': expanded, 'animate-float': expanded}">
                        <img src="/logotipo.png" alt="Logo" 
                            class="transition-all duration-300 ease-in-out rounded-full object-cover border-1 border-white"
                            :class="expanded ? 'w-14 h-14 md:w-16 md:h-16' : 'w-10 h-10 md:w-12 md:h-12'">
                    </div>
                  
                    <h2 x-show="expanded" 
                        class="font-bold text-purple-900 text-lg mt-3 text-center animate-slideIn tracking-tight">
                        LookTrendy
                    </h2>
                    <p x-show="expanded" class="text-xs text-purple-600 mt-1 animate-fadeIn">Fashion & Style</p>
                </div>
            </div>

            <!-- Menú principal -->
            <nav class="mt-4 md:mt-6 space-y-1 overflow-y-auto max-h-[70vh]">
                <!-- Inicio -->
                <a href="{{ route('inicio') }}" 
                   x-on:click="activeMenu = 'inicio'"
                   class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all group menu-item nav-link overflow-hidden"
                   :class="activeMenu === 'inicio' ? 'active-menu-item' : ''"
                   wire:navigate>
                    <div class="relative flex items-center">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-all">
                            <i class="fas fa-home text-purple-600 group-hover:text-purple-700 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 animate-fadeIn">Inicio</span>
                    </div>
                </a>
                
                @auth
                    @if(Auth::user()->User_Role === 'Administrador')
                        <!-- Compras -->
                        <div x-data="{ open: false }">
                            <div @click="open = !open; expanded = true; activeMenu = 'compras'" 
                                class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                                :class="{'active-menu-item': activeMenu === 'compras'}">
                                <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-all">
                                    <i class="fas fa-shopping-cart text-blue-600 group-hover:text-blue-700 text-sm"></i>
                                </div>
                                <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Compras</span>
                                <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                                :class="{'rotate-180': open}"></i>
                            </div>
                            <div x-show="open && expanded" x-collapse 
                                class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                <a href="{{route('dashboard.compras')}}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-blue-50 text-xs md:text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-chart-line text-blue-500 mr-2 text-xs"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('transaction') }}" 
                                class="flex items-center p-1 md:p-2 rounded-lg hover:bg-blue-50 text-xs md:text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-plus-circle text-blue-500 mr-2 text-xs"></i>
                                    <span>Nueva Compra</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                <!-- Ventas -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu ='ventas'" 
                         class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'ventas'}">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-pink-100 rounded-lg group-hover:bg-pink-200 transition-all">
                            <i class="fas fa-shopping-basket text-pink-500 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Ventas</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>

                    <div x-show="open && expanded" x-collapse 
                         class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                         <a href="{{ route('dashboard.ventas') }}"class="flex items-center p-1 md:p-2 rounded-lg hover:bg-pink-100 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <div class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center rounded-full mr-2">
                                <i class="fas fa-chart-pie text-pink-600 text-xs"></i>
                            </div>
                            <span class="text-pink-800">Dashboard</span>
                        </a>
                        <a href="{{ route('ventas') }}" 
                           class="flex items-center p-1 md:p-2 rounded-lg hover:bg-pink-100 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <div class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center rounded-full mr-2">
                                <i class="fas fa-tags text-pink-600 text-xs"></i>
                            </div>
                            <span class="text-pink-800">Nueva Venta</span>
                        </a>
                    </div>
                </div>

                <!-- Crédito -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu = 'credito'" 
                         class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'credito'}">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-green-100 rounded-lg group-hover:bg-green-200 transition-all">
                            <i class="fas fa-credit-card text-green-600 group-hover:text-green-700 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Crédito</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>
                    <div x-show="open && expanded" x-collapse 
                         class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                        <a href="{{route('credit.dashboard')}}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-green-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-chart-pie text-green-500 mr-2 text-xs"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('creditos') }}" 
                           class="flex items-center p-1 md:p-2 rounded-lg hover:bg-green-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-plus text-green-500 mr-2 text-xs"></i>
                            <span>Nuevo Crédito</span>
                        </a>
                        <a href="{{ route('abonos') }}" 
                           class="flex items-center p-1 md:p-2 rounded-lg hover:bg-green-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-hand-holding-usd text-green-500 mr-2 text-xs"></i>
                            <span>Abono Crédito</span>
                        </a>
                    </div>
                </div>
                
                @auth
                    @if(Auth::user()->User_Role === 'Administrador')
                        <!-- Gestión de Usuarios -->
                        <div x-data="{ open: false }">
                            <div @click="open = !open; expanded = true; activeMenu = 'usuarios'" 
                                class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                                :class="{'active-menu-item': activeMenu === 'usuarios'}">
                                <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-all">
                                    <i class="fas fa-address-book text-amber-600 group-hover:text-amber-700 text-sm"></i>
                                </div>
                                <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Registros</span>
                                <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                                :class="{'rotate-180': open}"></i>
                            </div>
                            <div x-show="open && expanded" x-collapse 
                                class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                <a href="{{ route('usuarios') }}" 
                                class="flex items-center p-1 md:p-2 rounded-lg hover:bg-amber-50 text-xs md:text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-user-cog text-amber-500 mr-2 text-xs"></i>
                                    <span>Usuarios</span>
                                </a>
                                <a href="{{ route('clientes') }}" 
                                class="flex items-center p-1 md:p-2 rounded-lg hover:bg-amber-50 text-xs md:text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-users text-amber-500 mr-2 text-xs"></i>
                                    <span>Clientes</span>
                                </a>
                                <a href="{{ route('proveedores') }}" 
                                class="flex items-center p-1 md:p-2 rounded-lg hover:bg-amber-50 text-xs md:text-sm transition-all"
                                wire:navigate>
                                    <i class="fas fa-truck text-amber-500 mr-2 text-xs"></i>
                                    <span>Proveedores</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                <!-- Inventario -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu = 'inventario'" 
                         class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'inventario'}">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-rose-100 rounded-lg group-hover:bg-rose-200 transition-all">
                            <i class="fas fa-box-open text-rose-600 group-hover:text-rose-700 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Inventario</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>
                    <div x-show="open && expanded" x-collapse 
                         class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                              <a href="{{route('dashboard.inventario')}}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-green-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-chart-pie text-rose-500 mr-2 text-xs"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{route('inventario')}}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-rose-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-clipboard-list text-rose-500 mr-2 text-xs"></i>
                            <span>Inventario</span>
                        </a>
                        <a href="{{ route('productos') }}" 
                           class="flex items-center p-1 md:p-2 rounded-lg hover:bg-rose-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-boxes text-rose-500 mr-2 text-xs"></i>
                            <span>Productos</span>
                        </a>
                        <a href="{{ route('categorias') }}" 
                           class="flex items-center p-1 md:p-2 rounded-lg hover:bg-rose-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-tags text-rose-500 mr-2 text-xs"></i>
                            <span>Categorias</span>
                        </a>
                    </div>
                </div>

                <!-- Reportes -->
                <div x-data="{ open: false }">
                    <div @click="open = !open; expanded = true; activeMenu = 'reportes'" 
                         class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all cursor-pointer group menu-item nav-link overflow-hidden"
                         :class="{'active-menu-item': activeMenu === 'reportes'}">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-cyan-100 rounded-lg group-hover:bg-cyan-200 transition-all">
                            <i class="fas fa-chart-bar text-cyan-600 group-hover:text-cyan-700 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 flex-1 animate-fadeIn">Reportes</span>
                        <i x-show="expanded" class="fas fa-chevron-down text-xs transition-transform duration-200 text-purple-500"
                           :class="{'rotate-180': open}"></i>
                    </div>
                    <div x-show="open && expanded" x-collapse 
                         class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                        <a href="{{ route('reporte.creditos') }}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-cyan-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-file-invoice-dollar text-cyan-500 mr-2 text-xs"></i>
                            <span>Reporte de Crédito</span>
                        </a>
                        <a href="{{ route('reporte.abonos') }}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-cyan-50 text-xs md:text-sm transition-all"
                           wire:navigate>
                            <i class="fas fa-file-invoice text-cyan-500 mr-2 text-xs"></i>
                            <span>Reporte de Abonos</span>
                        </a>
                        <a href="{{ route('reporte.ventas') }}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-cyan-50 text-xs md:text-sm transition-all"
                           wire:navigate> 
                            <i class="fas fa-file-alt text-cyan-500 mr-2 text-xs"></i>
                            <span>Reporte de Ventas</span>
                        </a>
                        <a href="{{ route('reporte.compra') }}" class="flex items-center p-1 md:p-2 rounded-lg hover:bg-cyan-50 text-xs md:text-sm transition-all"
                           wire:navigate> 
                            <i class="fas fa-file-alt text-cyan-500 mr-2 text-xs"></i>
                            <span>Reporte de Compras</span>
                        </a>
                    </div>
                </div>

                @auth
                    @if(Auth::user()->User_Role === 'Administrador')
                        <div x-data="{ open: activeMenu === 'configuracion' }" class="relative">
                            <a href="#" 
                                x-on:click="open = !open; activeMenu = 'configuracion'; if(isMobile) expanded = true"
                                class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all group menu-item nav-link overflow-hidden"
                                :class="activeMenu === 'configuracion' ? 'active-menu-item' : ''">
                                <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-all">
                                    <i class="fas fa-cog text-indigo-600 group-hover:text-indigo-700 text-sm"></i>
                                </div>
                                <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 animate-fadeIn">Configuración</span>
                                <i x-show="expanded" class="fas fa-chevron-down ml-auto text-xs text-purple-800 transition-transform duration-200" 
                                    :class="{'transform rotate-180': open}"></i>
                            </a>
            
                            <!-- Submenú con mejor espaciado en móviles -->
                            <div x-show="open && expanded" x-collapse 
                                class="ml-8 md:ml-10 pl-2 space-y-1 mt-1 animate-fadeIn">
                                <a href="{{ route('backup') }}" 
                                    x-on:click="activeMenu = 'backup'; if(isMobile) expanded = false"
                                    class="flex items-center p-1 md:p-2 text-xs md:text-sm font-medium text-purple-700 rounded-lg hover:bg-indigo-50 transition-all submenu-item"
                                    :class="activeMenu === 'backup' ? 'bg-indigo-100' : ''"
                                    wire:navigate>
                                    <i class="fas fa-cloud-upload-alt text-xs mr-2 md:mr-3 ml-0 md:ml-1"></i>
                                    <span>Respaldar Archivos</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                <!-- Manual de Usuario -->
                <a href="{{ route('manual-usuario') }}" 
                   x-on:click="activeMenu = 'manual'"
                   class="relative flex items-center p-2 md:p-3 rounded-xl hover:bg-white hover:shadow-md transition-all group menu-item nav-link overflow-hidden"
                   :class="activeMenu === 'manual' ? 'active-menu-item' : ''"
                   wire:navigate>
                    <div class="relative flex items-center">
                        <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-all">
                            <i class="fas fa-book-open text-purple-600 group-hover:text-purple-700 text-sm"></i>
                        </div>
                        <span x-show="expanded" class="ml-2 md:ml-3 text-xs md:text-sm font-medium text-purple-800 animate-fadeIn">Manual de Usuario</span>
                    </div>
                </a>
            </nav>
            
            {{-- <!-- Perfil de usuario -->
            <div x-show="expanded" class="absolute bottom-2 md:bottom-4 left-0 right-0 px-2 md:px-4 animate-slideIn">
                <div class="flex items-center p-2 md:p-3 bg-white rounded-xl shadow-sm border border-purple-100 hover:shadow-md transition-all">
                    <div class="relative">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-7 h-7 md:w-8 md:h-8 rounded-full border-2 border-white shadow">
                        <div class="absolute -bottom-1 -right-1 bg-green-400 rounded-full w-2 h-2 md:w-3 md:h-3 border-2 border-white"></div>
                    </div>
                    <div class="ml-2 md:ml-3 overflow-hidden">
                        <p class="text-xs md:text-sm font-medium text-purple-800 truncate">{{ Auth::user()->User_FirstName }}</p>
                        <p class="text-xxs md:text-xs text-purple-600 truncate">{{ Auth::user()->User_Role }}</p>
                    </div>
                </div>
            </div> --}}
            
            <!-- Versión móvil colapsada del perfil -->
            <div x-show="!expanded && isMobile" class="user-profile-mobile hidden">
                <div class="flex items-center justify-center w-full">
                    <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full border-2 border-white shadow">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenido principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4 md:p-6 page-transition">
            {{ $slot ?? '' }}
            @yield('content')
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
            
            // Colapsar el sidebar después de navegar en móviles
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('[x-data]');
                if (sidebar && sidebar.__x && sidebar.__x.$data) {
                    sidebar.__x.$data.expanded = false;
                    sidebar.__x.$data.forceExpand = false;
                }
            }
        });

        // Manejar clics fuera del sidebar en móviles
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.gradient-bg');
            const toggleBtn = document.querySelector('[x-on\\:click*="expanded"]');
            
            if (window.innerWidth <= 768 && sidebar && toggleBtn) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggleBtn = toggleBtn.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggleBtn) {
                    const sidebarData = sidebar.parentElement.__x.$data;
                    if (sidebarData) {
                        sidebarData.expanded = false;
                        sidebarData.forceExpand = false;
                    }
                }
            }
        });

        // Inicializar el menú activo basado en la ruta actual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebar = document.querySelector('[x-data]');
            
            if (!sidebar || !sidebar.__x || !sidebar.__x.$data) return;
            
            const data = sidebar.__x.$data;
            
            // Mapeo de rutas a menús activos
            const routeMap = {
                '/inicio': 'inicio',
                '/transaction': 'compras',
                '/ventas': 'ventas',
                '/dashboard/ventas': 'ventas',
                '/creditos': 'credito',
                '/abonos': 'credito',
                '/usuarios': 'usuarios',
                '/clientes': 'usuarios',
                '/proveedores': 'usuarios',
                '/inventario': 'inventario',
                '/productos': 'inventario',
                '/categorias': 'inventario',
                '/reporte/creditos': 'reportes',
                '/reporte/abonos': 'reportes',
                '/reporte/ventas': 'reportes',
                '/reporte/compra': 'reportes',
                '/backup': 'configuracion',
                '/manual-usuario': 'manual'
            };
            
            for (const [path, menu] of Object.entries(routeMap)) {
                if (currentPath.startsWith(path)) {
                    data.activeMenu = menu;
                    break;
                }
            }
            
            // Expandir automáticamente el submenú si está activo
            if (data.activeMenu) {
                const activeItem = document.querySelector(`[x-on\\:click*="activeMenu = '${data.activeMenu}'"]`);
                if (activeItem) {
                    const parentDropdown = activeItem.closest('[x-data]');
                    if (parentDropdown && parentDropdown.__x) {
                        parentDropdown.__x.$data.open = true;
                    }
                }
            }
        });
    </script>
    
    @if(session('toast'))
    <div class="toast-notification" data-type="{{ session('toast.type') }}">
        {{ session('toast.message') }}
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastData = @json(session('toast'));
        if(toastData) {
            showToast(toastData.type, toastData.message);
        }
    });

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    </script>

    <style>
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: white;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast.success { background: #28a745; }
    .toast.error { background: #dc3545; }
    .toast.warning { background: #fd7e14; }
    .toast.info { background: #17a2b8; }
    </style>
    @endif
</body>
</html>