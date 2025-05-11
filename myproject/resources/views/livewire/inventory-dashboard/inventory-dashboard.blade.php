<div class="container mx-auto px-4 py-6">
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                    <input 
                        type="date" 
                        wire:model="startDate" 
                        class="border rounded p-2 w-full"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                    <input 
                        type="date" 
                        wire:model="endDate" 
                        class="border rounded p-2 w-full"
                    >
                </div>
                <button 
                    wire:click="loadData" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-6"
                >
                    <i class="fas fa-sync-alt mr-2"></i> Actualizar
                </button>
            </div>
            <div wire:poll.30s="checkLowStock" class="text-sm text-gray-500">
                Última actualización: {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Ingresos del Período -->
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ingresos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($incoming) }}</p>
                    <p class="text-xs text-gray-400">{{ $startDate }} a {{ $endDate }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-arrow-down text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Salidas del Período -->
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Salidas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($outgoing) }}</p>
                    <p class="text-xs text-gray-400">{{ $startDate }} a {{ $endDate }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Bajo Stock -->
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Bajo Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($lowStock) }}</p>
                    <p class="text-xs text-gray-400">Productos por reponer</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Ingresos -->
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Ingresos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalIncoming) }}</p>
                    <p class="text-xs text-gray-400">Acumulado histórico</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-boxes text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Salidas -->
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Salidas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalOutgoing) }}</p>
                    <p class="text-xs text-gray-400">Acumulado histórico</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-shipping-fast text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico ApexCharts -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Movimiento de Inventario</h2>
        <div id="inventoryMovementChart" wire:ignore></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:load', function() {
        // Inicializar el gráfico
        const initChart = () => {
            const options = {
                series: [{
                    name: 'Ingresos',
                    data: @json($chartData['incoming'] ?? []),
                    color: '#3B82F6'
                }, {
                    name: 'Salidas',
                    data: @json($chartData['outgoing'] ?? []),
                    color: '#EF4444'
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 4,
                        columnWidth: '70%',
                    },
                },
                xaxis: {
                    categories: @json($chartData['dates'] ?? []),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Cantidad de Productos'
                    },
                    labels: {
                        formatter: function(val) {
                            return Math.round(val)
                        }
                    }
                },
                legend: {
                    position: 'top',
                    offsetY: 0
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " unidades"
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                }
            };

            const chart = new ApexCharts(document.querySelector("#inventoryMovementChart"), options);
            chart.render();
            
            return chart;
        };

        let chart = initChart();

        // Actualizar el gráfico cuando cambien los datos
        Livewire.on('updateChart', () => {
            chart.destroy();
            chart = initChart();
        });
    });
</script>
@endpush