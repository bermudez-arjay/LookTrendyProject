<?php

namespace App\Livewire\PurchaseTransaction;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Time;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseTrasanction extends Component
{

    public $users;
    public $suppliers;
    public $products;

    public $selectedUserId;
    public $selectedSupplierId;
    public $transactionType = 'Compra';

    public $productList = [];
    public $selectedProductId;
    public $quantity;
    public $unitPrice;

    public function mount()
    {
        $this->users = User::where('Active', 1)->get();
        $this->suppliers = Supplier::where('Active', 1)->get();
        $this->products = Product::where('Active', 1)->get();
    }

    public function addProduct()
    {
        $product = Product::find($this->selectedProductId);
        if (!$product || !$this->quantity || !$this->unitPrice) return;

        $this->productList[] = [
            'product_id' => $product->Product_ID,
            'name' => $product->Product_Name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'subtotal' => $this->quantity * $this->unitPrice
        ];

        $this->selectedProductId = null;
        $this->quantity = null;
        $this->unitPrice = null;
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList); // reindex
    }

    public function saveTransaction()
    {
        DB::transaction(function () {
            $total = array_sum(array_column($this->productList, 'subtotal'));

            // Insert into times
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

            // Insert purchase
            $purchase = Purchase::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => $this->selectedUserId,
                'Time_ID' => $time->Time_ID,
                'Total_Amount' => $total,
                'Purchase_Status' => 'Completed',
            ]);

            foreach ($this->productList as $item) {
                // Insert purchase_detail
                PurchaseDetail::create([
                    'Purchase_ID' => $purchase->Purchase_ID,
                    'Product_ID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Unit_Price' => $item['unit_price'],
                    'Subtotal' => $item['subtotal'],
                    'VAT' => 0,
                    'Total_With_VAT' => $item['subtotal'],
                ]);

                // Insert into transactions
                Transaction::create([
                    'Product_ID' => $item['product_id'],
                    'Supplier_ID' => $this->selectedSupplierId,
                    'User_ID' => $this->selectedUserId,
                    'Time_ID' => $time->Time_ID,
                    'Quantity' => $item['quantity'],
                    'Unit_Price' => $item['unit_price'],
                    'Total' => $item['subtotal'],
                    'Transaction_Type' => $this->transactionType,
                    'Purchase_ID' => $purchase->Purchase_ID
                ]);

                // Update inventory
                $inventory = Inventory::firstOrNew(['Product_ID' => $item['product_id']]);
                $inventory->Current_Stock = ($inventory->Current_Stock ?? 0) + $item['quantity'];
                $inventory->Last_Update = $now->toDateString();
                $inventory->save();
            }

            // Reset
            $this->productList = [];
            $this->selectedSupplierId = null;
            $this->selectedUserId = null;
        });
    }

    public function render()
    {
        return view('livewire.purchase-transaction.purchase-trasanction')->layout('layouts.app');
    }
}
