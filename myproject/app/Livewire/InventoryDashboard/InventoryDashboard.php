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
       $this->outgoingToday = Credit::whereDate('Start_Date', $date)
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
        
        $this->totalIncoming = PurchaseDetail::sum('Quantity');
        $this->totalOutgoing = CreditDetail::sum('Quantity');
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
        $keyWord = '%' . $this->keyWord . '%';

        return Inventory::with('product')
            ->whereHas('product', function($query) use ($keyWord) {
                $query->where('Product_ID', 'LIKE', $keyWord)
                    ->orWhere('Product_Name', 'LIKE', $keyWord)
                    ->orWhere('Description', 'LIKE', $keyWord)
                    ->orWhere('Category', 'LIKE', $keyWord);
            })
            ->orWhere('Current_Stock', 'LIKE', $keyWord)
            ->orWhere('Minimum_Stock', 'LIKE', $keyWord)
            ->orderBy('Current_Stock', 'asc')
            ->paginate(10);
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

    // Método para exportar todo a PDF
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
    // Método para exportar bajo stock a Excel
    public function exportLowStockExcel()
    {
        $this->dispatch('exporting');
        
        return Excel::download(
            new LowStockExport($this->lowStockProducts), 
            'productos_bajo_stock_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // Método para exportar bajo stock a PDF
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
    $inventoryItems = empty($this->keyWord) 
        ? Inventory::with(['product' => function($query) {
              $query->where('Removed', 0);
          }])
          ->whereHas('product', function($query) {
              $query->where('Removed', 0);
          })
          ->orderBy('Current_Stock', 'asc')
          ->paginate(10)
        : $this->filteredProducts();
        
    return view('livewire.inventory-dashboard.inventory-dashboard', [
        'inventoryItems' => $inventoryItems
    ])->layout('layouts.app');
}
}