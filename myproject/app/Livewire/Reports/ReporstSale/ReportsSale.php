<?php

namespace App\Livewire\Reports\ReporstSale;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsSale extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;
    public $client_id;
    public $min_amount;
    public $max_amount;
    public $showFilters = true;
    public $keyWord = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->toDateString();
    }

    // Computed properties
    public function getTotalSalesTodayProperty()
    {
        return Sale::whereDate('Sale_Date', today())->sum('Total_Amount');
    }

    public function getTotalQuantityTodayProperty()
    {
        return DB::table('sale_details')
            ->join('sales', 'sale_details.Sale_ID', '=', 'sales.Sale_ID')
            ->whereDate('sales.Sale_Date', today())
            ->sum('sale_details.Quantity');
    }

    public function getTotalSalesPeriodProperty()
    {
        return $this->getFilteredSalesQuery()->sum('Total_Amount');
    }

    public function getTotalQuantityPeriodProperty()
    {
        return DB::table('sale_details')
            ->join('sales', 'sale_details.Sale_ID', '=', 'sales.Sale_ID')
            ->when($this->start_date, fn($q) => $q->where('sales.Sale_Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('sales.Sale_Date', '<=', $this->end_date))
            ->sum('sale_details.Quantity');
    }

public function getTopClientsProperty()
{
    return Sale::selectRaw('Client_ID, sum(Total_Amount) as total_amount')
        ->with('client')
        ->when($this->start_date, fn($q) => $q->where('Sale_Date', '>=', $this->start_date))
        ->when($this->end_date, fn($q) => $q->where('Sale_Date', '<=', $this->end_date))
        ->groupBy('Client_ID')
        ->orderBy('total_amount', 'desc') // Ordenar por la columna agregada
        ->limit(5)
        ->get()
        ->map(function($item) {
            return [
                'client' => $item->client,
                'total_amount' => $item->total_amount
            ];
        });
}

    public function getTopProductsProperty()
    {
        return DB::table('sale_details')
            ->selectRaw('Product_ID, sum(Quantity) as total_quantity')
            ->join('sales', 'sale_details.Sale_ID', '=', 'sales.Sale_ID')
            ->when($this->start_date, fn($q) => $q->where('sales.Sale_Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('sales.Sale_Date', '<=', $this->end_date))
            ->groupBy('Product_ID')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'product' => Product::find($item->Product_ID),
                'total_quantity' => $item->total_quantity
            ]);
    }

    protected function getFilteredSalesQuery()
    {
        return Sale::with(['client', 'saleDetails.product'])
            ->when($this->start_date, fn($q) => $q->where('Sale_Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('Sale_Date', '<=', $this->end_date))
            ->when($this->client_id, fn($q) => $q->where('Client_ID', $this->client_id))
            ->when($this->min_amount, fn($q) => $q->where('Total_Amount', '>=', $this->min_amount))
            ->when($this->max_amount, fn($q) => $q->where('Total_Amount', '<=', $this->max_amount))
            ->when($this->keyWord, function($q) {
                $q->where(function($query) {
                    $query->where('Sale_ID', 'like', '%'.$this->keyWord.'%')
                        ->orWhereHas('client', function($q2) {
                            $q2->where('Client_FirstName', 'like', '%'.$this->keyWord.'%')
                               ->orWhere('Client_LastName', 'like', '%'.$this->keyWord.'%');
                        });
                });
            })
            ->orderBy('Sale_Date', 'desc');
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

public function exportPDF()
{
    // Obtener todas las ventas filtradas
    $sales = $this->getFilteredSalesQuery()->get();
    
    // Calcular totales
    $totalAmount = $sales->sum('Total_Amount');
    $totalVAT = $sales->sum('Sale_VAT');
    $totalQuantity = $sales->sum(function($sale) {
        return $sale->saleDetails->sum('Quantity');
    });

    // Generar PDF
    $pdf = Pdf::loadView('livewire.reports.reporst-sale.report-pdf', [
        'sales' => $sales,
        'totalAmount' => $totalAmount,
        'totalVAT' => $totalVAT,
        'totalQuantity' => $totalQuantity,
        'filters' => [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'client' => $this->client_id ? Client::find($this->client_id)->Client_FirstName : 'Todos',
            'amount_range' => ($this->min_amount || $this->max_amount) ? 
                ($this->min_amount ?? '0') . ' - ' . ($this->max_amount ?? '∞') : 'Todos',
        ]
    ])->setPaper('a4', 'landscape');
    
    // Descargar PDF
    return response()->streamDownload(
        fn () => print($pdf->output()),
        "reporte-ventas-".now()->format('Y-m-d').".pdf"
    );
}

    public function render()
    {
        $ventas = $this->getFilteredSalesQuery()->paginate($this->perPage);

        return view('livewire.reports.reporst-sale.reports-sale', [
            'ventas' => $ventas,
            'clientes' => Client::orderBy('Client_FirstName')->get(),
            'totalSalesToday' => $this->totalSalesToday,
            'totalQuantityToday' => $this->totalQuantityToday,
            'totalSalesPeriod' => $this->totalSalesPeriod,
            'totalQuantityPeriod' => $this->totalQuantityPeriod,
            'topClients' => $this->topClients,
            'topProducts' => $this->topProducts,
        ])->layout('layouts.app');
    }
}