<?php

namespace App\Livewire\PurchaseTransaction;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PaymentType;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class PurchaseTrasanction extends Component
{
    use WithPagination;
    
    public $selectedSupplierId = 0;
    public $purchaseDate;
    public $payment_type_id = null;
    public $dollar_amount;
    public $cordoba_amount;
    public $exchangeRate = 36.5;
    public $show_amount_fields = false;
    public $productList = [];
    public $quantities = [];
    public $unitPrices = [];
    public $showProductModal = false;
    public $received_amount = 0;
    public $change_amount = 0;
    public $searchProduct = '';
    public $perPage = 5;
    public $tax = 0.15;

    protected function rules()
    {
        $rules = [
            'selectedSupplierId' => 'required|numeric|min:1',
            'purchaseDate' => 'required|date',
            'productList' => 'required|array|min:1',
            'payment_type_id' => 'required|in:1,2',
            'productList.*.product_id' => 'required|exists:products,Product_ID',
            'productList.*.quantity' => 'required|numeric|min:1',
            'productList.*.unit_price' => 'required|numeric|min:0.01',
        ];

        if ($this->payment_type_id == 2) {
            $rules['dollar_amount'] = 'required|numeric|min:0.01';
        } else {
            $rules['cordoba_amount'] = 'required|numeric|min:0.01';
        }

        return $rules;
    }

    protected $messages = [
        'selectedSupplierId.required' => 'Debe seleccionar un proveedor',
        'selectedSupplierId.min' => 'El proveedor seleccionado no es válido',
        'purchaseDate.required' => 'La fecha de compra es obligatoria',
        'purchaseDate.date' => 'La fecha no tiene un formato válido',
        'productList.required' => 'Debe agregar al menos un producto',
        'productList.min' => 'Debe agregar al menos un producto',
        'payment_type_id.required' => 'Seleccione un tipo de pago',
        'dollar_amount.required' => 'El monto en dólares es requerido',
        'dollar_amount.numeric' => 'El monto en dólares debe ser numérico',
        'dollar_amount.min' => 'El monto en dólares debe ser mayor a 0',
        'cordoba_amount.required' => 'El monto en córdobas es requerido',
        'cordoba_amount.numeric' => 'El monto en córdobas debe ser numérico',
        'cordoba_amount.min' => 'El monto en córdobas debe ser mayor a 0',
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
        $this->purchaseDate = now()->format('Y-m-d');
    }

    public function getConvertedAmountProperty()
    {
        if ($this->payment_type_id == 2 && $this->dollar_amount > 0) {
            return floatval($this->dollar_amount) * $this->exchangeRate;
        }
        return 0;
    }

    public function getTotalAmountProperty()
    {
        return collect($this->productList)->sum('total_with_tax');
    }

    public function updatedPaymentTypeId()
    {
        $this->updatePaymentFields();
        $this->calculateChange();
    }

    public function updatedDollarAmount()
    {
        $product = Product::with('inventories')->find($productId);
        
        if (!$product) {
            $this->addError('modal_error', 'Producto no encontrado');
            return;
        }

        $quantity = $this->quantities[$productId] ?? null;
        $unitPrice = $this->unitPrices[$productId] ?? null;

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
        $this->calculateChange();
    }

    public function cancelPurchase()
    {
        $this->reset();
        $this->purchaseDate = now()->format('Y-m-d');
    }

    public function resetForm()
    {
        $this->reset([
            'selectedSupplierId',
            'payment_type_id',
            'productList',
            'dollar_amount',
            'cordoba_amount',
            'received_amount',
            'change_amount',
            'quantities',
            'unitPrices'
        ]);
        
        $this->resetValidation();
        $this->purchaseDate = now()->format('Y-m-d');
        $this->show_amount_fields = false;
    }

    public function savePurchase()
    {
        $this->validate();

        if (empty($this->productList)) {
            $this->addError('productList', 'Debe agregar al menos un producto.');
            return;
        }

        DB::transaction(function () {
            $total = collect($this->productList)->sum('total_with_tax');
            $now = Carbon::now();

            $paymentType = PaymentType::find($this->payment_type_id);
            $isDollarPayment = str_contains($paymentType->Payment_Type_Name, 'USD');

            $time = Time::create([
                'Date' => $date->format('Y-m-d'),
                'Year' => $date->year,
                'Quarter' => ceil($date->month / 3),
                'Month' => $date->month,
                'Week' => $date->weekOfYear,
                'Hour' => $date->format('H:i:s'),
                'Day_of_Week' => $date->dayOfWeekIso,
            ]);

            $totalAmount = $this->total_amount;
            $receivedAmount = ($this->payment_type_id == 2) 
                ? $this->dollar_amount * $this->exchangeRate
                : $this->cordoba_amount;

            $purchase = Purchase::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => Auth::user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Purchase_Date' => $this->purchaseDate,
                'Total_Amount' => $totalAmount,
                'Purchase_Status' => 'Completado',
                'Payment_Type_ID' => $this->payment_type_id,
            ]);

            foreach ($this->productList as $item) {
                PurchaseDetail::create([
                    'Purchase_ID' => $purchase->Purchase_ID,
                    'Product_ID' => $item['product_id'],
                    'Quantity' => $item['raw_quantity'],
                    'Unit_Price' => $item['raw_unit_price'],
                    'Subtotal' => $item['raw_unit_price'] * $item['raw_quantity'],
                    'VAT' => ($item['raw_unit_price'] * $item['raw_quantity']) * $this->tax,
                    'Total_With_VAT' => ($item['raw_unit_price'] * $item['raw_quantity']) * (1 + $this->tax),
                ]);

                Inventory::updateOrCreate(
                    ['Product_ID' => $item['product_id']],
                    [
                        'Current_Stock' => DB::raw('IFNULL(Current_Stock, 0) + ' . $item['raw_quantity']),
                        'Minimum_Stock' => DB::raw('COALESCE(Minimum_Stock, 0)'),
                        'Last_Update' => now()->format('Y-m-d')
                    ]
                );
            }

            Transaction::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => Auth::user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Total' => $totalAmount,
                'Transaction_Type' => 'Compra',
                'Purchase_ID' => $purchase->Purchase_ID,
                'Payment_Type_ID' => $this->payment_type_id,
                'Received_Amount' => $receivedAmount,
                'Exchange_Rate' => $this->exchangeRate,
                'Dollar_Amount' => ($this->payment_type_id == 2) ? $this->dollar_amount : null,
            ]);
            
            DB::commit();
            
            $this->resetForm();
            session()->flash('success', 'Compra registrada exitosamente');
            $this->dispatch('purchase-completed');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            session()->flash('error', 'Error de validación: '.implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al registrar la compra: '.$e->getMessage());
            $this->dispatch('purchase-error', message: $e->getMessage());
        }
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
            'suppliers' => Supplier::where('Removed', 0)->orderBy('Supplier_Name')->get(),
            'paymentTypes' => PaymentType::all(),
            'filteredProducts' => $this->filteredProducts
        ])->layout('layouts.app');
    }
}