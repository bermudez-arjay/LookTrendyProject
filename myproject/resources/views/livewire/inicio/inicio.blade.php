<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Bienvenido {{ Auth::user()->User_FirstName }} {{ Auth::user()->User_LastName }}</h2>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <p class="text-gray-500 text-sm">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Sección de Resumen de Créditos -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Resumen de Créditos Hoy</h3>
                <span class="text-2xl font-bold text-blue-600">
                    ${{ number_format($this->totalCreditosHoy ?? 0, 2) }}
                </span>
            </div>
            <livewire:charts.credit-chart />
        </div>

        <!-- Módulos del Sistema -->
        <h2 class="text-xl font-semibold text-purple-900 text-center mb-8">Módulos del Sistema</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @auth
                @if(Auth::user()->User_Role === 'Administrador')
                <!-- Módulo de Compras -->
                <a href="{{ route('transaction') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Compras</h3>
                            <p class="text-sm text-gray-500 mt-1">Gestión de compras y proveedores</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                        <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </a>
                @endif
            @endauth

            <!-- Módulo de Ventas al Crédito -->
            <a href="{{ route('creditos') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-credit-card text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Ventas al Crédito</h3>
                        <p class="text-sm text-gray-500 mt-1">Administración de créditos y pagos</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                    <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </a>

            <!-- Módulo de Inventario -->
            <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                <div class="flex items-center">
                    <div class="bg-rose-100 p-3 rounded-full mr-4">
                        <i class="fas fa-box-open text-rose-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Inventario</h3>
                        <p class="text-sm text-gray-500 mt-1">Control de stock y productos</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                    <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </a>

            @auth
                @if(Auth::user()->User_Role === 'Administrador')
                <!-- Módulo de Clientes -->
                <a href="{{ route('clientes') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Clientes</h3>
                            <p class="text-sm text-gray-500 mt-1">Gestión de clientes y contactos</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                        <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </a>
                @endif
            @endauth

            <!-- Módulo de Ayuda -->
            <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <i class="fas fa-question text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Ayuda</h3>
                        <p class="text-sm text-gray-500 mt-1">Manual de usuario y soporte</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                    <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                </div>
            </a>

            @auth
                @if(Auth::user()->User_Role === 'Administrador')
                <!-- Módulo de Configuración -->
                <a href="#" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all hover:scale-[1.02] hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-cog text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Configuración</h3>
                            <p class="text-sm text-gray-500 mt-1">Ajustes del sistema y usuarios</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-center">
                        <span class="text-xs text-blue-500 font-medium">Acceder <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </a>
                @endif
            @endauth
        </div>
    </div>
</div>