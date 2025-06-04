<div>
    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-blue-50 p-3 rounded-lg">
            <p class="text-sm text-blue-800">Hoy</p>
            <p class="text-xl font-bold text-blue-600">${{ number_format($totalCreditosHoy, 2) }}</p>
        </div>
        <div class="bg-green-50 p-3 rounded-lg">
            <p class="text-sm text-green-800">Últimos 7 días</p>
            <p class="text-xl font-bold text-green-600">${{ number_format($totalUltimos7Dias, 2) }}</p>
        </div>
        <div class="bg-purple-50 p-3 rounded-lg">
            <p class="text-sm text-purple-800">Total General</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($totalGeneral, 2) }}</p>
        </div>
    </div>
    
    <!-- Contenedor del gráfico -->
    <!-- <div wire:ignore style="width: 100%; height: 400px;">
        <canvas id="creditChart" class="w-full h-80 bg-blue"></canvas>
    </div> -->
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('creditChart');
    if (!ctx) {
        console.error('Canvas no encontrado');
        return;
    }

    let chartInstance = null;

    function initChart(data) {
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    initChart(@json($chartData));

    Livewire.on('chartUpdated', function (data) {
        console.log(data);
        initChart(data);
    });
});

</script>
@endpush
