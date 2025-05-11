<?php

namespace App\Livewire\InventoryDashboard;

use Livewire\Component;
use App\Models\Time;
use App\Models\Purchase;
use App\Models\Credit;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseDetail;

use Carbon\Carbon;

class InventoryDashboard extends Component
{
    public $startDate;
    public $endDate;
    public $chartData = [];
    public $incoming = 0;
    public $outgoing = 0;
    public $lowStock = 0;
    public $totalIncoming = 0;
    public $totalOutgoing = 0;

    protected $listeners = ['dateRangeSelected'];

    public function mount()
    {
        // Establecer rango por defecto (últimos 30 días)
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        
        $this->loadData();
        $this->checkLowStock();
    }

    public function dateRangeSelected($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->loadData();
    }

    public function loadData()
    {
        // Obtener IDs de tiempo para el rango seleccionado
        $timeIds = Time::whereBetween('Date', [$this->startDate, $this->endDate])
                      ->orderBy('Date')
                      ->pluck('Time_ID');

        // Datos para el gráfico
        $movements = [];
        $categories = [];
        
        // Obtener compras (entradas)
        $purchases = Purchase::select(
                'times.Date',
                DB::raw('SUM(purchase_details.Quantity) as total')
            )
            ->join('times', 'purchases.Time_ID', '=', 'times.Time_ID')
            ->join('purchase_details', 'purchases.Purchase_ID', '=', 'purchase_details.Purchase_ID')
            ->whereIn('purchases.Time_ID', $timeIds)
            ->groupBy('times.Date')
            ->orderBy('times.Date')
            ->get();

        // Obtener créditos (salidas)
        // $credits = Credit::select(
        //         'times.Date',
        //         DB::raw('SUM(credit_details.Quantity) as total')
        //     )
        //     ->join('times', 'credits.Time_ID', '=', 'times.Time_ID')
        //     ->join('credit_details', 'credits.Credit_ID', '=', 'credit_details.Credit_ID')
        //     ->whereIn('credits.Start_Date', $timeIds)
        //     ->groupBy('times.Date')
        //     ->orderBy('times.Date')
        //     ->get();

        // Preparar datos para el gráfico
        $dates = [];
        $incomingData = [];
        $outgoingData = [];

        foreach ($timeIds as $timeId) {
            $time = Time::find($timeId);
            $date = Carbon::parse($time->Date)->format('d M');
            
            $purchase = $purchases->where('Date', $time->Date)->first();
           // $credit = $credits->where('Date', $time->Date)->first();
            
            $dates[] = $date;
            $incomingData[] = $purchase ? $purchase->total : 0;
          //  $outgoingData[] = $credit ? $credit->total : 0;
        }

        $this->chartData = [
            'dates' => $dates,
            'incoming' => $incomingData,
            'outgoing' => $outgoingData
        ];

        // Totales para el período seleccionado
        $this->incoming = array_sum($incomingData);
        $this->outgoing = array_sum($outgoingData);

        // Totales generales
        $this->totalIncoming = DB::table('purchase_details')->sum('Quantity');
        $this->totalOutgoing = DB::table('credit_details')->sum('Quantity');
    }

    public function checkLowStock()
    {
        $this->lowStock = Inventory::whereColumn('Current_Stock', '<=', 'Minimum_Stock')->count();
    }

    public function render()
    {
            return view('livewire.inventory-dashboard.inventory-dashboard')->layout('layouts.app');

    }
}