<?php

namespace App\Livewire\Reports\ReportPurchase;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportPurchase extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;
    public $supplier_id;
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
    public function getTotalPurchasesTodayProperty()
    {
        return Purchase::whereHas('time', function($query) {
            $query->whereDate('Date', today());
        })->sum('Total_Amount');
    }

    public function getTotalQuantityTodayProperty()
    {
        return DB::table('purchase_details')
            ->join('purchases', 'purchase_details.Purchase_ID', '=', 'purchases.Purchase_ID')
            ->join('transactions', 'purchases.Purchase_ID', '=', 'transactions.Purchase_ID')
            ->join('times', 'transactions.Time_ID', '=', 'times.Time_ID')
            ->whereDate('times.Date', today())
            ->sum('purchase_details.Quantity');
    }

    public function getTotalPurchasesPeriodProperty()
    {
        return $this->getFilteredPurchasesQuery()->sum('Total_Amount');
    }

    public function getTotalQuantityPeriodProperty()
    {
        return DB::table('purchase_details')
            ->join('purchases', 'purchase_details.Purchase_ID', '=', 'purchases.Purchase_ID')
            ->join('transactions', 'purchases.Purchase_ID', '=', 'transactions.Purchase_ID')
            ->join('times', 'transactions.Time_ID', '=', 'times.Time_ID')
            ->when($this->start_date, fn($q) => $q->where('times.Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('times.Date', '<=', $this->end_date))
            ->sum('purchase_details.Quantity');
    }

    public function getTopSuppliersProperty()
    {
        return Purchase::selectRaw('purchases.Supplier_ID, sum(purchases.Total_Amount) as total_amount')
            ->with('supplier')
            ->join('transactions', 'purchases.Purchase_ID', '=', 'transactions.Purchase_ID')
            ->join('times', 'transactions.Time_ID', '=', 'times.Time_ID')
            ->when($this->start_date, fn($q) => $q->where('times.Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('times.Date', '<=', $this->end_date))
            ->groupBy('purchases.Supplier_ID')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'supplier' => $item->supplier,
                    'total_amount' => $item->total_amount
                ];
            });
    }

    public function getTopProductsProperty()
    {
        return DB::table('purchase_details')
            ->selectRaw('Product_ID, sum(Quantity) as total_quantity')
            ->join('purchases', 'purchase_details.Purchase_ID', '=', 'purchases.Purchase_ID')
            ->join('transactions', 'purchases.Purchase_ID', '=', 'transactions.Purchase_ID')
            ->join('times', 'transactions.Time_ID', '=', 'times.Time_ID')
            ->when($this->start_date, fn($q) => $q->where('times.Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('times.Date', '<=', $this->end_date))
            ->groupBy('Product_ID')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'product' => Product::find($item->Product_ID),
                'total_quantity' => $item->total_quantity
            ]);
    }

    protected function getFilteredPurchasesQuery()
    {
        return Purchase::with(['supplier', 'purchaseDetails.product', 'time'])
            ->join('transactions', 'purchases.Purchase_ID', '=', 'transactions.Purchase_ID')
            ->join('times', 'transactions.Time_ID', '=', 'times.Time_ID')
            ->when($this->start_date, fn($q) => $q->where('times.Date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->where('times.Date', '<=', $this->end_date))
            ->when($this->supplier_id, fn($q) => $q->where('purchases.Supplier_ID', $this->supplier_id))
            ->when($this->min_amount, fn($q) => $q->where('purchases.Total_Amount', '>=', $this->min_amount))
            ->when($this->max_amount, fn($q) => $q->where('purchases.Total_Amount', '<=', $this->max_amount))
            ->when($this->keyWord, function($q) {
                $q->where(function($query) {
                    $query->where('purchases.Purchase_ID', 'like', '%'.$this->keyWord.'%')
                        ->orWhereHas('supplier', function($q2) {
                            $q2->where('Supplier_Name', 'like', '%'.$this->keyWord.'%');
                        });
                });
            })
            ->orderBy('times.Date', 'desc')
            ->select('purchases.*');
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function exportPDF()
    {
        $purchases = $this->getFilteredPurchasesQuery()->get();
        
        $totalAmount = $purchases->sum('Total_Amount');
        $totalVAT = $purchases->sum('Purchase_VAT');
        $totalQuantity = $purchases->sum(function($purchase) {
            return $purchase->purchaseDetails->sum('Quantity');
        });

        $pdf = Pdf::loadView('livewire.reports.report-purchase.report-pdf', [
            'purchases' => $purchases,
            'totalAmount' => $totalAmount,
            'totalVAT' => $totalVAT,
            'totalQuantity' => $totalQuantity,
            'filters' => [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'supplier' => $this->supplier_id ? Supplier::find($this->supplier_id)->Supplier_Name : 'Todos',
                'amount_range' => ($this->min_amount || $this->max_amount) ? 
                    ($this->min_amount ?? '0') . ' - ' . ($this->max_amount ?? '∞') : 'Todos',
            ]
        ])->setPaper('a4', 'landscape');
        
        return response()->streamDownload(
            fn () => print($pdf->output()),
            "reporte-compras-".now()->format('Y-m-d').".pdf"
        );
    }

    public function render()
    {
        $compras = $this->getFilteredPurchasesQuery()->paginate($this->perPage);

        return view('livewire.reports.report-purchase.report-purchase', [
            'compras' => $compras,
            'proveedores' => Supplier::orderBy('Supplier_Name')->get(),
            'totalPurchasesToday' => $this->totalPurchasesToday,
            'totalQuantityToday' => $this->totalQuantityToday,
            'totalPurchasesPeriod' => $this->totalPurchasesPeriod,
            'totalQuantityPeriod' => $this->totalQuantityPeriod,
            'topSuppliers' => $this->topSuppliers,
            'topProducts' => $this->topProducts,
        ])->layout('layouts.app');
    }
}