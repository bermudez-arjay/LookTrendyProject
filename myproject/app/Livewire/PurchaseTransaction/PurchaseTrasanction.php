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
use Livewire\WithPagination;

class PurchaseTrasanction extends Component
{
    use WithPagination;
    
    public $users, $suppliers, $paymentsType;
    public $userId;
    public $payment_type_id;
    public $selectedUserId;
    public $selectedSupplierId = 0;
    public $transactionType = 'Compra';
    public $showProductModal = false;
    public $productList = [];
    public $searchProduct = '';
    public $quantities = [];
    public $unitPrices = [];
    public $tax = 0.15;
    public $perPage = 5;
    public $exchangeRate = 1;
    public $showExchangeRate = false;

    protected $rules = [
        'selectedUserId' => 'required|exists:users,User_ID',
        'selectedSupplierId' => 'required|exists:suppliers,Supplier_ID',
        'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
        'productList' => 'required|array|min:1',
        'productList.*.product_id' => 'required|exists:products,Product_ID',
        'productList.*.quantity' => 'required|numeric|min:1',
        'productList.*.unit_price' => 'required|numeric|min:0.01',
        'exchangeRate' => 'required_if:showExchangeRate,true|numeric|min:0.0001',
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
        'exchangeRate.required_if' => 'El tipo de cambio es requerido para pagos en dólares',
        'exchangeRate.numeric' => 'El tipo de cambio debe ser un número',
        'exchangeRate.min' => 'El tipo de cambio debe ser mayor a 0',
    ];

    public function mount()
    {
        $this->userId = Auth::user()->User_ID;
        $this->users = User::where('Removed', 0)->get();
        $this->suppliers = Supplier::where('Removed', 0)->get();
        $this->paymentsType = PaymentType::all();
        $this->selectedUserId = $this->userId;
    }

    public function checkDollarPayment()
    {
        $paymentType = PaymentType::find($this->payment_type_id);
        $this->showExchangeRate = $paymentType && str_contains($paymentType->Payment_Type_Name, 'USD');
        
        if (!$this->showExchangeRate) {
            $this->exchangeRate = 1;
        }
    }

    public function addDetail($productId)
    {
        $product = Product::with('inventories')->find($productId);
        
        if (!$product) {
            $this->addError('modal_error', 'Producto no encontrado');
            return;
        }

        $quantity = $this->quantities[$productId] ?? null;
        $unitPrice = $this->unitPrices[$productId] ?? null;

        // Validar campos requeridos
        if (empty($quantity)) {
            $this->addError('quantity_'.$productId, 'La cantidad es requerida');
            return;
        }

        if (empty($unitPrice)) {
            $this->addError('unitPrice_'.$productId, 'El precio unitario es requerido');
            return;
        }

        if ($quantity < 1) {
            $this->addError('quantity_'.$productId, 'La cantidad debe ser al menos 1');
            return;
        }

        if ($unitPrice < 0.01) {
            $this->addError('unitPrice_'.$productId, 'El precio unitario debe ser mayor a 0');
            return;
        }

        // Aplicar conversión si es pago en dólares
        $originalUnitPrice = $unitPrice;
        if ($this->showExchangeRate && $this->exchangeRate > 0) {
            $unitPrice = $unitPrice * $this->exchangeRate;
        }

        $subtotal = $quantity * $unitPrice;
        $totalWithTax = $subtotal + ($subtotal * $this->tax);

        foreach ($this->productList as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $item['unit_price'] = $unitPrice;
                $item['original_unit_price'] = $originalUnitPrice;
                $item['subtotal'] = $item['quantity'] * $unitPrice;
                $item['total_with_tax'] = $item['subtotal'] + ($item['subtotal'] * $this->tax);
                
                // Limpiar campos y disparar evento
                $this->quantities[$productId] = null;
                $this->unitPrices[$productId] = null;
                $this->dispatch('productAdded', productId: $productId);
                
                $this->dispatch('notify', 
                    type: 'success',
                    title: 'Producto actualizado',
                    message: 'Se ha actualizado la cantidad del producto'
                );
                return;
            }
        }

        $this->productList[] = [
            'product_id' => $productId,
            'name' => $product->Product_Name,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'original_unit_price' => $originalUnitPrice,
            'subtotal' => $subtotal,
            'tax' => $this->tax,
            'total_with_tax' => $totalWithTax,
            'current_stock' => $product->inventories->Current_Stock ?? 0,
            'is_dollar' => $this->showExchangeRate,
            'exchange_rate' => $this->showExchangeRate ? $this->exchangeRate : null
        ];

 $this->dispatch('productAdded', productId: $productId);
        // Limpiar campos y disparar evento
        $this->quantities[$productId] = null;
        $this->unitPrices[$productId] = null;
         $this->dispatch('syncInputs');
        
        $this->dispatch('notify', 
            type: 'success',
            title: 'Producto agregado',
            message: 'El producto se ha agregado a la lista'
        );
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList);
    }

    public function cancelTransaction()
    {
        $this->selectedSupplierId = 0;
        $this->resetAll();
        $this->resetErrorBag();
        session()->flash('info', 'La transacción ha sido cancelada.');
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

            // Verificar si es pago en dólares
            $paymentType = PaymentType::find($this->payment_type_id);
            $isDollarPayment = str_contains($paymentType->Payment_Type_Name, 'USD');

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
            $this->selectedSupplierId = 0;
            
            $message = 'Compra registrada exitosamente';
            if ($isDollarPayment) {
                $message .= ' (Pago en dólares)';
            }
            
            session()->flash('success', $message);
            $this->dispatch('purchase-completed');
        });
    }

    private function resetAll()
    {
        $this->selectedUserId = $this->userId;
        $this->selectedSupplierId = null;
        $this->payment_type_id = null;
        $this->productList = [];
        $this->quantities = [];
        $this->unitPrices = [];
        $this->showProductModal = false;
        $this->searchProduct = '';
    }

    public function getFilteredProductsProperty()
    {
        return Product::with('inventories')
            ->where('Removed', 0)
            ->when($this->searchProduct, function($query) {
                $query->where('Product_Name', 'like', '%'.$this->searchProduct.'%')
                      ->orWhere('Category', 'like', '%'.$this->searchProduct.'%');
            })
            ->leftJoin('inventories', 'products.Product_ID', '=', 'inventories.Product_ID')
            ->orderByRaw('IFNULL(inventories.Current_Stock, 0) ASC')
            ->orderBy('Product_Name', 'asc')
            ->select('products.*', 'inventories.Current_Stock') 
            ->paginate($this->perPage)
            ->through(function ($product) {
                $product->current_stock = $product->Current_Stock ?? 0;
                return $product;
            });
    }

    public function render()
    {
        return view('livewire.purchase-transaction.purchase-trasanction', [
            'filteredProducts' => $this->filteredProducts
        ])->layout('layouts.app');
    }
}