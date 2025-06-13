<?php

namespace App\Livewire\InventoryDashboard;

use App\Models\Credit;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Time;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\CreditDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryExport;
use App\Exports\LowStockExport;

class InventoryDashboard extends Component
{
    use WithPagination;
    public $incomingToday = 0;
    public $outgoingToday = 0;
    public $totalIncoming = 0;
    public $totalOutgoing = 0;
    public $lowStockCount = 0;
    public $showLowStockModal = false;
    public $lowStockProducts = [];
    public $keyWord = '';
    public $selectedDate;
    
    protected $listeners = ['closeModal'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadSummaryData();
        $this->checkLowStock();
    }

    public function loadSummaryData()
    {
        $date = Carbon::parse($this->selectedDate);
        $month = $date->month;
        $year = $date->year;
        
        // Incoming today (purchases)
        $this->incomingToday = Time::whereDate('Date', $date)
            ->whereHas('purchases.transactions')
            ->with(['purchases.purchaseDetails' => function($query) {
                $query->select('Purchase_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($time) {
                return $time->purchases->sum(function($purchase) {
                    return $purchase->purchaseDetails->sum('Quantity');
                });
            });

        // Outgoing today (credits + sales)
        $creditOutgoing = Credit::whereDate('Start_Date', $date)
            ->whereHas('transactions.time', function($query) use ($date) {
                $query->whereDate('Date', $date);
            })
            ->with(['creditDetails' => function($query) {
                $query->select('Credit_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($credit) {
                return $credit->creditDetails->sum('Quantity');
            });

        $salesOutgoing = Sale::whereDate('Sale_Date', $date)
            ->with(['saleDetails' => function($query) {
                $query->select('Sale_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($sale) {
                return $sale->saleDetails->sum('Quantity');
            });

        $this->outgoingToday = $creditOutgoing + $salesOutgoing;

        // Total incoming this month (purchases)
        $this->totalIncoming = Time::whereMonth('Date', $month)
            ->whereYear('Date', $year)
            ->whereHas('purchases.transactions')
            ->with(['purchases.purchaseDetails' => function($query) {
                $query->select('Purchase_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($time) {
                return $time->purchases->sum(function($purchase) {
                    return $purchase->purchaseDetails->sum('Quantity');
                });
            });

        // Total outgoing this month (credits + sales)
        $totalCreditOutgoing = Credit::whereMonth('Start_Date', $month)
            ->whereYear('Start_Date', $year)
            ->whereHas('transactions.time', function($query) use ($month, $year) {
                $query->whereMonth('Date', $month)
                      ->whereYear('Date', $year);
            })
            ->with(['creditDetails' => function($query) {
                $query->select('Credit_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($credit) {
                return $credit->creditDetails->sum('Quantity');
            });

        $totalSalesOutgoing = Sale::whereMonth('Sale_Date', $month)
            ->whereYear('Sale_Date', $year)
            ->with(['saleDetails' => function($query) {
                $query->select('Sale_ID', 'Quantity');
            }])
            ->get()
            ->sum(function($sale) {
                return $sale->saleDetails->sum('Quantity');
            });

        $this->totalOutgoing = $totalCreditOutgoing + $totalSalesOutgoing;
    }

    public function checkLowStock()
    {
        $this->lowStockCount = Inventory::whereColumn('Current_Stock', '<=', 'Minimum_Stock')
            ->whereHas('product', function($query) {
                $query->where('Removed', 0);
            })
            ->count();
        
        $this->lowStockProducts = Inventory::with(['product' => function($query) {
                $query->where('Removed', 0);
            }])
            ->whereColumn('Current_Stock', '<=', 'Minimum_Stock')
            ->whereHas('product', function($query) {
                $query->where('Removed', 0);
            })
            ->get();
        $productsInfo = $this->lowStockProducts->map(function ($inventory) {
            return [
                'product_ID' => $inventory->product_ID,
                'product_name' => $inventory->product->Product_Name,
            ];
        })->toArray();
        
        return $productsInfo;
    }
    
    public function filteredProducts()
    {
        $query = Inventory::with(['product' => function($query) {
                $query->where('Removed', 0);
            }])
            ->whereHas('product', function($query) {
                $query->where('Removed', 0);
            });

        if (!empty(trim($this->keyWord))) {
            $keyWord = '%'.trim($this->keyWord).'%';
            
            $query->where(function($q) use ($keyWord) {
                $q->whereHas('product', function($q) use ($keyWord) {
                    $q->where('Product_ID', 'LIKE', $keyWord)
                      ->orWhere('Product_Name', 'LIKE', $keyWord)
                      ->orWhere('Description', 'LIKE', $keyWord)
                      ->orWhere('Category', 'LIKE', $keyWord);
                })
                ->orWhere('Current_Stock', 'LIKE', $keyWord)
                ->orWhere('Minimum_Stock', 'LIKE', $keyWord);
            });
        }

        return $query->orderBy('Current_Stock', 'asc')->paginate(10);
    }
    
    public function updatedSelectedDate($value)
    {
        $this->selectedDate = $value;
        $this->loadSummaryData();
    }
    
    public function openLowStockModal()
    {
        $this->showLowStockModal = true;
    }
    
    public function closeModal()
    {
        $this->showLowStockModal = false;
    }
    
    public function exportAllExcel()
    {
        $this->dispatch('exporting');
        
        return Excel::download(
            new InventoryExport(), 
            'inventario_completo_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportAllPdf()
    {
        $this->dispatch('exporting');
        
        $inventory = Inventory::with(['product' => function($query) {
                $query->where('Removed', 0);
            }])
            ->whereHas('product', function($query) {
                $query->where('Removed', 0);
            })
            ->orderBy('Current_Stock', 'asc')
            ->get();

        $pdf = Pdf::loadView('livewire.reports.report-inventory.report-inventory', [
            'inventory' => $inventory,
            'title' => 'Inventario Completo',
            'date' => now()->format('Y-m-d')
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'inventario_completo_' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportLowStockExcel()
    {
        $this->dispatch('exporting');
        
        return Excel::download(
            new LowStockExport($this->lowStockProducts), 
            'productos_bajo_stock_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportLowStockPdf()
    {
        $this->dispatch('exporting');
        
        $pdf = Pdf::loadView('livewire.reports.report-inventory.low-stock', [
            'products' => $this->lowStockProducts,
            'title' => 'Productos con Bajo Stock',
            'date' => now()->format('Y-m-d')
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'productos_bajo_stock_' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.inventory-dashboard.inventory-dashboard', [
            'inventoryItems' => $this->filteredProducts()
        ])->layout('layouts.app');
    }
}