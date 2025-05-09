<?php

namespace App\Livewire\PurchaseTransaction;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseTrasanction extends Component
{
    public $users, $suppliers, $products;
    public $userId;

    public $selectedUserId, $selectedSupplierId;
    public $transactionType = 'Compra';

    public $productList = [];
    public $selectedProductId, $quantity, $unitPrice, $tax = 0.15; // impuesto fijo

    protected $rules = [
        'selectedUserId' => 'required|exists:users,User_ID',
        'selectedSupplierId' => 'required|exists:suppliers,Supplier_ID',
    ];

    public function mount()
    {
        $this->userId = Auth::user()->User_ID;  // CORREGIDO
        $this->users = User::where('Removed', 0)->get();
        $this->suppliers = Supplier::where('Removed', 0)->get();
        $this->products = Product::where('Removed', 0)->get();
        $this->selectedUserId = $this->userId; // Usuario logueado por defecto
    }

    public function addProduct()
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,Product_ID',
            'quantity' => 'required|numeric|min:1',
            'unitPrice' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->selectedProductId);
        if (!$product) return;

        $subtotal = $this->quantity * $this->unitPrice;
        $totalWithTax = $subtotal + ($subtotal * $this->tax);

        foreach ($this->productList as &$item) {
            if ($item['product_id'] == $product->Product_ID) {
                $item['quantity'] += $this->quantity;
                $item['subtotal'] = $item['quantity'] * $item['unit_price'];
                $item['total_with_tax'] = $item['subtotal'] + ($item['subtotal'] * $this->tax);
                $this->resetInputs();
                return;
            }
        }

        $this->productList[] = [
            'product_id' => $product->Product_ID,
            'name' => $product->Product_Name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'subtotal' => $subtotal,
            'tax' => $this->tax,
            'total_with_tax' => $totalWithTax
        ];

        $this->resetInputs();
    }

    private function resetInputs()
    {
        $this->selectedProductId = null;
        $this->quantity = null;
        $this->unitPrice = null;
    }
    public function updatedSelectedProductId($productId)
    {
        if (!$productId) {
            $this->unitPrice = null;
        }
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList); // Reindexar
    }

    public function saveTransaction()
    {
        if (empty($this->productList)) {
            $this->addError('productList', 'Debe agregar al menos un producto.');
            return;
        }

        DB::transaction(function () {
            $total = collect($this->productList)->sum('total_with_tax');

            $now = Carbon::now();
            $time = Time::create([
                'Date' => $now->toDateString(),
                'Year' => $now->year,
                'Quarter' => ceil($now->month / 3),
                'Month' => $now->month,
                'Week' => $now->weekOfYear,
                'Hour' => $now->toTimeString(),
                'Day_of_Week' => $now->dayOfWeekIso,
            ]);

            $purchase = Purchase::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => $this->selectedUserId,
                'Time_ID' => $time->Time_ID,
                'Total_Amount' => $total,
                'Purchase_Status' => 'Completado',
            ]);

            foreach ($this->productList as $item) {
                PurchaseDetail::create([
                    'Purchase_ID' => $purchase->Purchase_ID,
                    'Product_ID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Unit_Price' => $item['unit_price'],
                    'Subtotal' => $item['subtotal'],
                    'VAT' => $item['tax'],
                    'Total_With_VAT' => $item['total_with_tax'],
                ]);

                Transaction::create([
                    'Supplier_ID' => $this->selectedSupplierId,
                    'User_ID' => $this->selectedUserId,
                    'Time_ID' => $time->Time_ID,
                    'Total' => $item['total_with_tax'],
                    'Transaction_Type' => $this->transactionType,
                    'Purchase_ID' => $purchase->Purchase_ID
                ]);

                $inventory = Inventory::firstOrNew(['Product_ID' => $item['product_id']]);
                $inventory->Current_Stock = ($inventory->Current_Stock ?? 0) + $item['quantity'];
                $inventory->Last_Update = $now->toDateString();
                $inventory->save();
            }

            $this->resetAll();
            session()->flash('success', 'Compra registrada exitosamente.');
        });
    }

    private function resetAll()
    {
        $this->selectedUserId = $this->userId;
        $this->selectedSupplierId = null;
        $this->productList = [];
    }

    public function render()
    {
        return view('livewire.purchase-transaction.purchase-trasanction')->layout('layouts.app');
    }
}
