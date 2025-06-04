<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\Credit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreditChart extends Component
{
    public $chartData = [];
    public $totalCreditosHoy = 0;
    public $totalUltimos7Dias = 0;
    public $totalGeneral = 0;
    
    protected $listeners = ['refreshChart' => 'updateChartData'];

    public function mount()
    {
        $this->updateChartData();
    }

    public function render()
{
     //dd('Componente renderizado');
    // $this->emit('chartData', $this->chartData);
   // dd($this->chartData); // Verifica que los datos estén siendo pasados correctamente
    return view('livewire.charts.credit-chart', [
        'chartData' => $this->chartData,
    ])->layout('layouts.forgotPassword');
}

    public function updateChartData()
    {
        date_default_timezone_set('America/Managua');
        
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);
        
        $this->totalGeneral = Credit::sum('Total_Amount');
        
        $recentCreditsExist = Credit::whereBetween(DB::raw('DATE(Start_Date)'), [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->exists();

        if (!$recentCreditsExist) {
            $this->prepareFallbackData();
            return;
        }
        
        $this->totalCreditosHoy = Credit::whereDate('Start_Date', $endDate->format('Y-m-d'))
            ->sum('Total_Amount');
            
        $this->totalUltimos7Dias = Credit::whereBetween(DB::raw('DATE(Start_Date)'), [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->sum('Total_Amount');
        
        $dailyData = Credit::whereBetween(DB::raw('DATE(Start_Date)'), [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->selectRaw('DATE(Start_Date) as date, SUM(Total_Amount) as total')
            ->groupBy(DB::raw('DATE(Start_Date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
            $labels = [];
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = $endDate->copy()->subDays($i);
                $dateString = $date->format('Y-m-d');
                $labels[] = $date->isoFormat('ddd');
                $data[] = $dailyData->has($dateString) ? (float)$dailyData[$dateString]->total : 0;
            }


        $this->chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Créditos',
                'data' => $data,
                'backgroundColor' => array_map(function($value) {
                    return $value > 0 ? 'rgba(79, 70, 229, 0.7)' : 'rgba(200, 200, 200, 0.2)';
                }, $data),
                'borderColor' => 'rgba(79, 70, 229, 1)',
                'borderWidth' => 1
            ]
        ]
    ];
    $this->dispatch('chartUpdated', $this->chartData);
    }

    protected function prepareChartData($dailyData, $startDate, $endDate)
    {
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $endDate->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->isoFormat('ddd');
            $data[] = $dailyData->has($dateString) ? (float)$dailyData[$dateString]->total : 0;
        }
        
        $this->chartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Créditos',
                'data' => $data,
                'backgroundColor' => array_map(function($value) {
                    return $value > 0 ? 'rgba(79, 70, 229, 0.7)' : 'rgba(200, 200, 200, 0.2)';
                }, $data),
                'borderColor' => 'rgba(79, 70, 229, 1)',
                'borderWidth' => 1
            ]
        ]
    ];
    //$this->dispatch('chartUpdated', $this->chartData);
    }

   
}