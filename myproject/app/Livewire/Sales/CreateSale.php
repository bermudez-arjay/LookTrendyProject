<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Inventory;
use App\Models\Time;
use App\Models\Transaction;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateSale extends Component
{
    public $selectedClientId = 0;
    public $saleDate;
  public $payment_type_id = null; 
    public $dollar_amount;
    public $cordoba_amount;
    public $exchangeRate = 36.5; 
    public $show_amount_fields = false;
    public $productList = [];
    public $quantities = [];
    public $showProductModal = false;
    public $received_amount = 0; 
    public $change_amount = 0; 

  protected function rules()
{
    $rules = [
        'selectedClientId' => 'required|numeric|min:1',
        'saleDate' => 'required|date',
        'productList' => 'required|array|min:1',
        'payment_type_id' => 'required|in:1,2',
    ];

    if ($this->payment_type_id == 2) {
        $rules['dollar_amount'] = 'required|numeric|min:0.01';
    } else {
        $rules['cordoba_amount'] = 'required|numeric|min:0.01';
    }

    return $rules;
}
protected $messages = [
    'selectedClientId.required' => 'Debe seleccionar un cliente',
    'selectedClientId.min' => 'El cliente seleccionado no es válido',
    'saleDate.required' => 'La fecha de venta es obligatoria',
    'saleDate.date' => 'La fecha no tiene un formato válido',
    'productList.required' => 'Debe agregar al menos un producto',
    'productList.min' => 'Debe agregar al menos un producto',
    'payment_type_id.required' => 'Seleccione un tipo de pago',
    'dollar_amount.required' => 'El monto en dólares es requerido',
    'dollar_amount.numeric' => 'El monto en dólares debe ser numérico',
    'dollar_amount.min' => 'El monto en dólares debe ser mayor a 0',
    'cordoba_amount.required' => 'El monto en córdobas es requerido',
    'cordoba_amount.numeric' => 'El monto en córdobas debe ser numérico',
    'cordoba_amount.min' => 'El monto en córdobas debe ser mayor a 0',
];   public function getConvertedAmountProperty()
{
    if ($this->payment_type_id == 2 && $this->dollar_amount > 0) {
        return floatval($this->dollar_amount) * $this->exchangeRate;
    }
    return 0;
}

    public function getTotalAmountProperty()
    {
        return collect($this->productList)->sum('total_amount');
    }

   public function updatedReceivedAmount($value)
{
    if ($this->payment_type_id == 1) {
        $this->cordoba_amount = $value;
    }
    $this->calculateChange();
}

public function updatedCordobaAmount($value)
{
    if (!is_numeric($value)) {
        $this->cordoba_amount = 0;
    }
    $this->calculateChange();
}
    public function updatedPaymentTypeId()
    {
        $this->calculateChange();
    }

    public function updatedDollarAmount()
    {
        $this->calculateChange();
    }

    public function calculateChange()
{
    $total = floatval($this->total_amount) ?? 0;
    
    if ($this->payment_type_id == 1) {
        $received = floatval($this->cordoba_amount) ?? 0;
        $this->change_amount = max(0, $received - $total);
    } else {
        $received = floatval($this->dollar_amount) ?? 0;
        $this->change_amount = max(0, ($received * $this->exchangeRate) - $total);
    }
}
      public function validateAmount()
    {
        $this->resetErrorBag();

        if ($this->payment_type_id == 1) {
            if (!is_numeric($this->cordoba_amount)) {
                $this->addError('cordoba_amount', 'El monto en córdobas debe ser un número válido');
                return false;
            }
            if ($this->cordoba_amount <= 0) {
                $this->addError('cordoba_amount', 'El monto debe ser mayor que cero');
                return false;
            }
        }

        if ($this->payment_type_id == 2) {
            if (!is_numeric($this->dollar_amount)) {
                $this->addError('dollar_amount', 'El monto en dólares debe ser un número válido');
                return false;
            }
            if ($this->dollar_amount <= 0) {
                $this->addError('dollar_amount', 'El monto debe ser mayor que cero');
                return false;
            }
        }

        return true;
    }

    public function updatePaymentFields()
    {
        $this->resetAmountFields();
        $this->show_amount_fields = in_array($this->payment_type_id, [1, 2]);
    }

    public function resetPaymentFields()
    {
        $this->payment_type_id = null;
        $this->resetAmountFields();
        $this->show_amount_fields = false;
    }

    public function resetAmountFields()
    {
        $this->dollar_amount = null;
        $this->cordoba_amount = null;
    }
  
    public function updatedTotalAmount()
    {
        $this->calculateChange();
    }

   public function mount()
{
     $this->saleDate = now()->format('Y-m-d');
}

public function render()
{
    $paymentTypes = PaymentType::all();
    
    return view('livewire.sale.sales-component', [
        'clients' => Client::orderBy('Client_FirstName')->get(),
        'products' => Product::with(['inventories' => function($query) {
                        $query->where('Current_Stock', '>', 0);
                    }])
                    ->where('removed', 0)
                    ->orderBy('Product_Name')
                    ->get(),
        'paymentTypes' => $paymentTypes
    ])->layout('layouts.app');
}

    public function updatedQuantities($value, $key)
    {
        $productId = str_replace('quantities.', '', $key);
        $availableStock = Inventory::where('Product_ID', $productId)->value('Current_Stock') ?? 0;
        
        if ($value > $availableStock) {
            $this->addError('quantities.'.$productId, "No hay suficiente stock. Disponible: $availableStock");
        } else {
            $this->resetErrorBag('quantities.'.$productId);
        }
    }

    public function addProduct($productId = null)
    {
        $this->validate([
            "quantities.$productId" => 'required|numeric|min:1'
        ]);

        $product = Product::findOrFail($productId);
        $inventory = Inventory::where('Product_ID', $productId)->first();
        $availableStock = $inventory ? $inventory->Current_Stock : 0;
        $quantity = $this->quantities[$productId];

        if ($quantity > $availableStock) {
            $this->addError('quantities.'.$productId, "Stock insuficiente. Disponible: $availableStock");
            return;
        }
       
        $product = Product::find($productId);
        $inventory = Inventory::where('Product_ID', $productId)->first();
        $availableStock = $inventory ? $inventory->Current_Stock : 0;

        $existingIndex = null;
        $previousQuantity = 0;
        
        foreach ($this->productList as $index => $detail) {
            if ($detail['product_id'] == $productId) {
                $existingIndex = $index;
                $previousQuantity = $detail['quantity'];
                break;
            }
        }

        $newQuantity = $previousQuantity + $quantity;

        if ($newQuantity > $availableStock) {
            $remainingStock = $availableStock - $previousQuantity;
            $message = $previousQuantity > 0 
                ? "Ya tiene $previousQuantity unidades. Solo puede agregar $remainingStock más (stock total: $availableStock)."
                : "La cantidad solicitada ($newQuantity) supera el stock disponible ($availableStock unidades).";

            $this->addError('modal_error', $message);
            $this->addError('quantity_'.$productId, 'Stock insuficiente');
            return;
        }
  
        $subtotal = $product->Unit_Price * $newQuantity;
        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        if ($existingIndex !== null) {
            $this->productList[$existingIndex] = [
                'product_id' => $product->Product_ID,
                'product_name' => $product->Product_Name,
                'quantity' => $newQuantity,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_amount' => $total,
            ];
        } else {
            $this->productList[] = [
                'product_id' => $product->Product_ID,
                'product_name' => $product->Product_Name,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_amount' => $total,
            ];
        }
        
        unset($this->quantities[$productId]);
        $this->resetErrorBag();
        $this->showProductModal = false;
        $this->calculateChange();
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList);
    }

    public function cancelSale()
    {
        $this->reset();
        $this->saleDate = now()->format('Y-m-d');
    }
    public function resetForm()
{
    $this->reset([
        'selectedClientId',
        'payment_type_id',
        'productList',
        'dollar_amount',
        'cordoba_amount',
        'received_amount',
        'change_amount'
    ]);
    
  
    $this->resetValidation();
    
    $this->selectedClientId = '';
    $this->payment_type_id = ''; 
    
   
    $this->saleDate = now()->format('Y-m-d');
    $this->show_amount_fields = false;
}
public function saveSale()
{
    \Log::debug('Datos antes de validar:', [
        'productList' => $this->productList,
        'dollar_amount' => $this->dollar_amount,
        'payment_type_id' => $this->payment_type_id
    ]);

    try {
        $this->validate();

        $date = Carbon::parse($this->saleDate); 
        $subtotal = collect($this->productList)->sum('subtotal');
        $vatAmount = $subtotal * 0.15;
        $totalAmount = $subtotal + $vatAmount;

        DB::beginTransaction();
        
          $time = Time::create([
            'Date' => $date->format('Y-m-d'),
            'Year' => $date->year,
            'Quarter' => ceil($date->month / 3),
            'Month' => $date->month,
            'Week' => $date->weekOfYear,
            'Hour' => $date->format('H:i:s'),
            'Day_of_Week' => $date->dayOfWeekIso,
        ]);
        $saleData = [
            'Client_ID' => $this->selectedClientId,
            'Sale_Date' => $this->saleDate,
            'Sale_VAT' => $vatAmount,
            'Total_Amount' => $totalAmount,
        ];
     
        $sale = Sale::create($saleData);
        foreach ($this->productList as $item) {
            SaleDetail::create([
                'Sale_ID' => $sale->Sale_ID,
                'Product_ID' => $item['product_id'],
                'Quantity' => $item['quantity'],
                'Subtotal' => $item['subtotal'],
            ]);

            $inventory = Inventory::where('Product_ID', $item['product_id'])->first();
            if ($inventory) {
                $inventory->decrement('Current_Stock', $item['quantity']);
            }
        }
       
        $receivedAmount = ($this->payment_type_id == 2) 
            ? $this->dollar_amount * $this->exchangeRate
            : $this->cordoba_amount;

      
        $transactionData = [
            'Sale_ID' => $sale->Sale_ID,  
            'Supplier_ID' => null,
            'User_ID' => auth()->user()->User_ID,
            'Time_ID' => $time->Time_ID,
            'Credit_ID' => null,
            'Total' => $totalAmount,
            'Transaction_Type' => 'Venta',
            'Purchase_ID' => null,
            'Payment_Type_ID' => $this->payment_type_id,
            'Received_Amount' => $receivedAmount,
            'Exchange_Rate' => $this->exchangeRate,
            'Dollar_Amount' => ($this->payment_type_id == 2) ? $this->dollar_amount : null,
        ];
        
        Transaction::create($transactionData);
        
        DB::commit();
        
        \Log::info('Venta completada exitosamente', [
            'sale_id' => $sale->Sale_ID,
            'total' => $totalAmount,
            'received' => $receivedAmount,
            'dollar_amount' => $this->dollar_amount ?? null
        ]);
        
       $this->resetForm();
        $this->saleDate = now()->format('Y-m-d');
        
        session()->flash('success', 'Venta registrada exitosamente');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('Error de validación', [
            'error' => $e->getMessage(),
            'errors' => $e->validator->errors()->all()
        ]);
        session()->flash('error', 'Error de validación: '.implode(' ', $e->validator->errors()->all()));
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error general', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        session()->flash('error', 'Error al registrar la venta: '.$e->getMessage());
    }
}
}