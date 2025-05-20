<?php

namespace App\Livewire\PurchaseTransaction;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\PaymentType;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseTrasanction extends Component
{
    public $users, $suppliers, $products, $paymentsType;
    public $userId;
    public $payment_type_id;
    public $selectedUserId, $selectedSupplierId;
    public $transactionType = 'Compra';
    public $showProductModal = false;
    public $productList = [];
    public $selectedProductId, $quantity, $unitPrice, $tax = 0.15;

    protected $rules = [
        'selectedUserId' => 'required|exists:users,User_ID',
        'selectedSupplierId' => 'required|exists:suppliers,Supplier_ID',
        'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
        'productList' => 'required|array|min:1',
        'productList.*.product_id' => 'required|exists:products,Product_ID',
        'productList.*.quantity' => 'required|numeric|min:1',
        'productList.*.unit_price' => 'required|numeric|min:0.01',
    ];

    protected $messages = [
        'selectedUserId.required' => 'El usuario es requerido',
        'selectedUserId.exists' => 'El usuario seleccionado no es válido',
        'selectedSupplierId.required' => 'Debe seleccionar un proveedor',
        'selectedSupplierId.exists' => 'El proveedor seleccionado no es válido',
        'payment_type_id.required' => 'Debe seleccionar un tipo de pago',
        'payment_type_id.exists' => 'El tipo de pago seleccionado no es válido',
        'productList.required' => 'Debe agregar al menos un producto',
        'productList.min' => 'Debe agregar al menos un producto',
        'productList.*.product_id.required' => 'El producto es requerido',
        'productList.*.product_id.exists' => 'El producto seleccionado no es válido',
        'productList.*.quantity.required' => 'La cantidad es requerida',
        'productList.*.quantity.numeric' => 'La cantidad debe ser un número',
        'productList.*.quantity.min' => 'La cantidad debe ser al menos 1',
        'productList.*.unit_price.required' => 'El precio unitario es requerido',
        'productList.*.unit_price.numeric' => 'El precio unitario debe ser un número',
        'productList.*.unit_price.min' => 'El precio unitario debe ser mayor a 0',
    ];

    public function mount()
    {
        $this->userId = Auth::user()->User_ID;
        $this->users = User::where('Removed', 0)->get();
        $this->suppliers = Supplier::where('Removed', 0)->get();
        $this->products = Product::where('Removed', 0)->get();
        $this->paymentsType = PaymentType::all();
        $this->selectedUserId = $this->userId;
    }

    public function addProduct()
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,Product_ID',
            'quantity' => 'required|numeric|min:1',
            'unitPrice' => 'required|numeric|min:0.01',
            'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
        ], [
            'selectedProductId.required' => 'Seleccione un producto',
            'quantity.required' => 'La cantidad es requerida',
            'quantity.min' => 'La cantidad debe ser al menos 1',
            'unitPrice.required' => 'El precio unitario es requerido',
            'unitPrice.min' => 'El precio debe ser mayor a 0',
            'payment_type_id.required' => 'Seleccione un tipo de pago',
            'payment_type_id.exists' => 'Tipo de pago no válido',
        ]);

        $product = Product::find($this->selectedProductId);
        if (!$product) {
            $this->addError('selectedProductId', 'Producto no encontrado');
            return;
        }

        $subtotal = $this->quantity * $this->unitPrice;
        $totalWithTax = $subtotal + ($subtotal * $this->tax);

        foreach ($this->productList as &$item) {
            if ($item['product_id'] == $product->Product_ID) {
                $item['quantity'] += $this->quantity;
                $item['subtotal'] = $item['quantity'] * $item['unit_price'];
                $item['total_with_tax'] = $item['subtotal'] + ($item['subtotal'] * $this->tax);
                $this->showProductModal = false;
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
        
        $this->showProductModal = false;
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
            return;
        }
        
        $product = Product::find($productId);
        if ($product) {
            $this->unitPrice = $product->Price ?? null;
        }
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList);
    }

    public function saveTransaction()
    {
        $this->validate();

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
                'Payment_Type_ID' => $this->payment_type_id,
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

                $inventory = Inventory::firstOrNew(['Product_ID' => $item['product_id']]);
                $inventory->Current_Stock = ($inventory->Current_Stock ?? 0) + $item['quantity'];
                $inventory->Last_Update = $now->toDateString();
                $inventory->Minimum_Stock = $inventory->Minimum_Stock ?? 5;
                $inventory->save();
            }

            Transaction::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => $this->selectedUserId,
                'Time_ID' => $time->Time_ID,
                'Total' => $total,
                'Transaction_Type' => $this->transactionType,
                'Purchase_ID' => $purchase->Purchase_ID,
                'Payment_Type_ID' => $this->payment_type_id,
            ]);

            $this->resetAll();
            session()->flash('success', 'Compra registrada exitosamente.');
            $this->dispatch('purchase-completed');
        });
    }

    private function resetAll()
    {
        $this->selectedUserId = $this->userId;
        $this->selectedSupplierId = null;
        $this->payment_type_id = null;
        $this->productList = [];
    }

    public function render()
    {
        return view('livewire.purchase-transaction.purchase-trasanction')->layout('layouts.app');
    }
}