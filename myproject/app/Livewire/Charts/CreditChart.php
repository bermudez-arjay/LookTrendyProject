<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\Credit;
use Carbon\Carbon;

class CreditChart extends Component
{
    public $chartData = [];
    public $totalCreditosHoy;
    
    protected $listeners = ['refreshChart' => 'updateChartData'];

    public function mount()
    {
        $this->updateChartData();
    }

    public function updateChartData()
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);
        
        $this->totalCreditosHoy = Credit::whereDate('Start_Date', $endDate)
            ->sum('Total_Amount');

        $creditsByDay = Credit::whereBetween('Start_Date', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(Start_Date) as date, SUM(Total_Amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dayName = $date->isoFormat('ddd');
            
            $labels[] = $dayName;
            $data[] = $creditsByDay->has($dateString) ? (float)$creditsByDay[$dateString]->total : 0;
        }
        
        $this->chartData = [
            'labels' => $labels,
            'data' => $data
        ];
        
        $this->dispatch('chartUpdated', data: $this->chartData);
    }

    public function render()
    {
        return view('livewire.charts.credit-chart');
    }
}