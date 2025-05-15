<div wire:ignore class="h-40 bg-gray-50 rounded-lg relative border">
    <div id="chartStatus" class="absolute inset-0 flex flex-col items-center justify-center gap-2 p-4">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500"></div>
        <p class="text-gray-600">Cargando gráfico...</p>
        <p id="chartDebug" class="text-xs text-gray-500"></p>
        <button id="retryButton" class="mt-2 px-3 py-1 bg-indigo-500 text-white rounded text-sm hidden">
            Reintentar
        </button>
    </div>
    
    <canvas id="creditChart" width="600" height="200" style="display: none;"></canvas>
</div>

@push('scripts')
<script>
const CHART_JS_URL = 'https://cdn.jsdelivr.net/npm/chart.js@3.7.1';
let chartInstance = null;

const statusEl = document.getElementById('chartStatus');
const debugEl = document.getElementById('chartDebug');
const retryBtn = document.getElementById('retryButton');
const canvas = document.getElementById('creditChart');

function updateStatus(message, isError = false) {
    if (debugEl) {
        debugEl.textContent = message;
        debugEl.className = isError ? 'text-xs text-red-500' : 'text-xs text-gray-500';
    }
}

function loadChartJS() {
    return new Promise((resolve, reject) => {
        if (window.Chart) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = CHART_JS_URL;
        script.onload = function() {
            if (window.Chart) {
                updateStatus('Chart.js cargado correctamente');
                resolve();
            } else {
                reject(new Error('Chart.js no se inicializó'));
            }
        };
        script.onerror = function() {
            reject(new Error('Error al cargar Chart.js'));
        };

        document.head.appendChild(script);
    });
}

function createChart() {
    try {
        if (!canvas) {
            throw new Error('No se encontró el elemento canvas');
        }

        if (chartInstance) {
            chartInstance.destroy();
        }

        const ctx = canvas.getContext('2d');
        if (!ctx) {
            throw new Error('No se pudo obtener el contexto del canvas');
        }

        const chartData = {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            data: [0, 0, 0, 1686.61, 0, 9350.28, 0]
        };

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Ventas',
                    data: chartData.data,
                    backgroundColor: chartData.data.map(val => 
                        val > 0 ? 'rgba(79, 70, 229, 0.7)' : 'rgba(200, 200, 200, 0.2)'
                    ),
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        canvas.style.display = 'block';
        if (statusEl) statusEl.style.display = 'none';
        updateStatus('Gráfico creado exitosamente');

    } catch (error) {
        console.error('Error al crear gráfico:', error);
        updateStatus('Error: ' + error.message, true);
        if (retryBtn) retryBtn.classList.remove('hidden');
    }
}

function initChart() {
    updateStatus('Iniciando carga...');
    if (retryBtn) retryBtn.classList.add('hidden');

    loadChartJS()
        .then(createChart)
        .catch(error => {
            console.error('Error:', error);
            updateStatus(error.message, true);
            if (retryBtn) retryBtn.classList.remove('hidden');
        });
}

if (retryBtn) {
    retryBtn.addEventListener('click', initChart);
}

if (document.readyState === 'complete') {
    initChart();
} else {
    document.addEventListener('DOMContentLoaded', initChart);
}

if (window.Livewire) {
    Livewire.hook('morph.updated', function() {
        if (!chartInstance) {
            initChart();
        }
    });
}
</script>
@endpush