<?php

namespace App\Livewire\CreditTransaction;

use App\Models\Client;
use App\Models\Product;
use App\Models\Credit;
use App\Models\Time;
use App\Models\CreditDetail;
use App\Models\PaymentType;
use App\Models\Inventory;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateCredit extends Component
{
    public $clients, $products, $paymentTypes;
    public $totalWithInterest = 0;
    public $quotaAmount = 0;

    public $client_id, $payment_type_id, $start_date, $due_date, $term, $selectedStock = 0, $selectedPrice = 0.0;
    public $total_amount = 0, $installments = 0;
    public float $interest_rate = 40.0;
    public $showProductModal = false;
    public $credit_status = 'Pendiente';
    public $quantities = [];

    public $creditDetails = [];

    // Campos temporales para agregar productos
    public $product_id, $quantity, $payment_date, $payment_amount;

    protected $listeners = ['selectProductChanged', 'selectClientChanged', 'selectPaymentTypeChanged', 'selectTermChanged', 'product-changed' => 'updateProductInfo'];
    public function showSuccessAlert($message)
{
    $this->dispatch('swal-toast', [
        'type' => 'success',
        'title' => 'Éxito',
        'message' => $message,
        'timer' => 3000
    ]);
}

public function showErrorAlert($message)
{
    $this->dispatch('swal-toast', [
        'type' => 'error',
        'title' => 'Error',
        'message' => $message,
        'timer' => 5000
    ]);
}
    public function show($creditId)
    {
        $credit = Credit::with(['client', 'payments'])->findOrFail($creditId);
        return view('credits.show', compact('credit'));
    }
    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,Client_ID',
            'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'total_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'creditDetails' => 'required|array|min:1',
        ];
    }
    protected function messages()
{
    return [
        'client_id.required' => 'Debe seleccionar un cliente',
        'client_id.exists' => 'El cliente seleccionado no es válido',
        
        'payment_type_id.required' => 'Debe seleccionar un tipo de pago',
        'payment_type_id.exists' => 'El tipo de pago seleccionado no es válido',
        
        'start_date.required' => 'La fecha de inicio es obligatoria',
        'start_date.date' => 'La fecha de inicio debe ser una fecha válida',
        
        'due_date.required' => 'La fecha de vencimiento es obligatoria',
        'due_date.date' => 'La fecha de vencimiento debe ser una fecha válida',
        'due_date.after_or_equal' => 'La fecha de vencimiento debe ser igual o posterior a la fecha de inicio',
        
        'total_amount.required' => 'El monto total es obligatorio',
        'total_amount.numeric' => 'El monto total debe ser un valor numérico',
        'total_amount.min' => 'El monto total no puede ser negativo',
        
        'interest_rate.required' => 'La tasa de interés es obligatoria',
        'interest_rate.numeric' => 'La tasa de interés debe ser un valor numérico',
        'interest_rate.min' => 'La tasa de interés no puede ser negativa',
        
        'installments.required' => 'El número de cuotas es obligatorio',
        'installments.integer' => 'El número de cuotas debe ser un número entero',
        'installments.min' => 'Debe haber al menos 1 cuota',
        
        'creditDetails.required' => 'Debe agregar al menos un producto',
        'creditDetails.array' => 'Los productos deben estar en formato válido',
        'creditDetails.min' => 'Debe agregar al menos un producto',

        'creditDetails.*.quantity.required' => 'Debe indicar una cantidad',
        'creditDetails.*.quantity.integer' => 'La cantidad debe ser un número entero',
        'creditDetails.*.quantity.min' => 'La cantidad debe ser al menos 1',
        'creditDetails.*.quantity.max_stock' => 'La cantidad solicitada supera el stock disponible.',
    ];
}
    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->due_date = date('Y-m-d', strtotime('+30 days'));
        $this->clients = Client::where('Removed',0)->whereDoesntHave('credits')
                     ->orWhereHas('credits', function($query) {
                         $query->where('credit_status', 'Cancelado');
                    })
                     ->get();
        
        $this->products = Product::whereHas('inventories', function ($query) {
            $query->where('Current_Stock', '>=', 10);
        })->get();
        
        $this->paymentTypes = PaymentType::all();
        $this->term = null;
        $this->updatedProductId(null);
        $this->addDetail();
    }

    public function updatedTerm()
    {
        $this->calculateDueDateAndInstallments();
        $this->recalculateTotalAmount();
        $this->recalculateTotalWithInterest();
        $this->recalculateQuotaAmount();
    }

    public function updateProductInfo($productId)
    {
        if (!$productId) {
            $this->selectedStock = 0;
            $this->selectedPrice = 0.0;
            return;
        }

        $inventory = Inventory::where('Product_ID', $productId)->first();
        $product = Product::find($productId);

        $this->selectedStock = $inventory ? $inventory->Current_Stock : 0;
        $this->selectedPrice = $product ? $product->Unit_Price : 0.0;
    }

    public function updatedProductId($value)
    {
        $this->addDetail();
        $this->updateProductInfo($value);
    }

    private function getInterestPerTerm()
    {
        switch ($this->term) {
            case 1:
                return 0.30;
            case 3:
                return 0.35;
            case 6:
                return 0.40;
            default:
                return 0.40;
        }
    }

    public function calculateCredit()
    {
        $tasaInteres = $this->getInterestPerTerm();

        $interes = $this->total_amount * $tasaInteres;
        $this->totalWithInterest = $this->total_amount + $interes;

        if ($this->installments > 0) {
            $this->quotaAmount = $this->totalWithInterest / $this->installments;
        } else {
            $this->quotaAmount = 0;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['start_date', 'term'])) {
            $this->calculateDueDateAndInstallments();
            $this->recalculateQuotaAmount();
        }
    }

    public function calculateDueDateAndInstallments()
    {
        if ($this->start_date && $this->term) {
            $start = Carbon::parse($this->start_date);
            $this->due_date = $start->copy()->addMonths($this->term)->addDays(7)->format('Y-m-d');
            $this->installments = $this->term;
        } else {
            $this->due_date = null;
            $this->installments = 0;
        }
    }

    public function calculateInstallments()
    {
        if ($this->start_date && $this->due_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->due_date);

            $this->installments = $end > $start ? $start->diffInMonths($end) : 0;
        }
    }

    public function updatedInstallments()
    {
        $this->recalculateQuotaAmount();
    }

    public function updatedInterestRate()
    {
        $this->recalculateTotalWithInterest();
        $this->recalculateQuotaAmount();
    }

    public function recalculateTotalWithInterest()
    {
        $interestAmount = ($this->total_amount * $this->interest_rate) / 100;
        $this->totalWithInterest = $this->total_amount + $interestAmount;
    }

    public function recalculateTotalAmount()
    {
        $this->total_amount = collect($this->creditDetails)->sum('total_with_vat');
    }

    public function recalculateQuotaAmount()
    {
        if ($this->installments > 0) {
            $this->quotaAmount = $this->totalWithInterest / $this->installments;
        } else {
            $this->quotaAmount = 0;
        }
    }
    
public function updatedSearchProduct()
{
    $this->products = Product::with('inventories')
        ->when($this->searchProduct, function($query) {
            $query->where('Product_Name', 'like', '%'.$this->searchProduct.'%');
        })
        ->get()
        ->map(function($product) {
            return [
                'Product_ID' => $product->Product_ID,
                'Product_Name' => $product->Product_Name,
                'Category' => $product->Category,
                'Unit_Price' => $product->Unit_Price,
                'inventories' => [
                    'Current_Stock' => $product->inventories->Current_Stock ?? 0,
                    'Inventory_ID' => $product->inventories->Inventory_ID ?? null,
                    'Product_ID' => $product->Product_ID
                ]
            ];
        })->toArray();
}

   public function addDetail($productId = null)
{
  
    $product = Product::find($productId);
    $quantity = $this->quantities[$productId] ?? 0;
    $availableStock = $product->inventories->Current_Stock ?? 0;

    if ($quantity > $availableStock) {
        $this->addError("quantities.$productId", "La cantidad solicitada supera el stock disponible ($availableStock unidades).");
        return;
    }

   if (!$productId || !$quantity || $quantity <= 0) {
    $this->dispatch('swal:error', [
        'title' => 'Datos incompletos',
        'message' => 'Debe seleccionar un producto y una cantidad válida.'
    ]);
    return;
     
}
    $product = Product::find($productId);
    $inventory = Inventory::where('Product_ID', $productId)->first();
    $availableStock = $inventory ? $inventory->Current_Stock : 0;

    if ($quantity > $availableStock) {
        $this->dispatch('swal:error', [
            'title' => 'Stock insuficiente',
            'message' => 'La cantidad supera el stock disponible.'
        ]);
        return;
    }

    if (!$product) {
        $this->dispatch('swal:error', [
            'title' => 'Producto no válido',
            'message' => 'El producto seleccionado no existe en la base de datos.'
        ]);
        return;
    }

    
    $existingDetailIndex = collect($this->creditDetails)->search(function ($detail) use ($productId) {
        return $detail['product_id'] == $productId;
    });

    $subtotal = $product->Unit_Price * $quantity;
    $vat = $subtotal * 0.15;
    $total = $subtotal + $vat;

    if ($existingDetailIndex !== false) {
        $this->creditDetails[$existingDetailIndex]['quantity'] = $quantity;
        $this->creditDetails[$existingDetailIndex]['subtotal'] = $subtotal;
        $this->creditDetails[$existingDetailIndex]['vat'] = $vat;
        $this->creditDetails[$existingDetailIndex]['total_with_vat'] = $total;
    } else {
        $this->creditDetails[] = [
            'product_id' => $product->Product_ID,
            'product_name' => $product->Product_Name,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'total_with_vat' => $total,
        ];
    }
    if ($productId === $this->product_id) {
     
        $this->product_id = '';
        $this->quantity = '';
        $this->selectedStock = 0;
        $this->selectedPrice = 0;
        $this->showProductModal = false;
    } else {
     
        unset($this->quantities[$productId]);
    }
        $this->recalculateTotalAmount();
        $this->recalculateTotalWithInterest();
        $this->recalculateQuotaAmount();
        $this->product_id = '';
        $this->quantity = '';
        $this->selectedStock = 0;
        $this->selectedPrice = 0;
        $this->showProductModal = false;
        $this->dispatch('resetSelect2');

    }

    public function removeDetail($index)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Eliminar producto',
            'message' => '¿Estás seguro de que deseas eliminar este producto del crédito?',
            'confirmText' => 'Sí, eliminar',
            'cancelText' => 'Cancelar',
            'method' => 'doRemoveDetail',
            'params' => [$index]
        ]);
    }

    public function doRemoveDetail($index)
    {
        if (isset($this->creditDetails[$index])) {
            unset($this->creditDetails[$index]);
            $this->creditDetails = array_values($this->creditDetails);

            $this->recalculateTotalAmount();
            $this->recalculateTotalWithInterest();
            $this->recalculateQuotaAmount();

            $this->dispatch('swal-toast', [
                'type' => 'success', 
                'title' => 'Producto eliminado',
                'message' => 'Producto eliminado correctamente',
                'timer' => 3000 
            ]);

            $this->dispatch('resetSelect2');
        }
    }

    public function save()
    {
        $this->validate();
        $date = Carbon::parse($this->start_date);
        DB::beginTransaction();
        try {
            $time = Time::create([
                'Date' => $date->format('Y-m-d'),
                'Year' => $date->year,
                'Quarter' => ceil($date->month / 3),
                'Month' => $date->month,
                'Week' => $date->weekOfYear,
                'Hour' => $date->format('H:i:s'),
                'Day_of_Week' => $date->dayOfWeekIso,
            ]);

            $tasaInteres = $this->getInterestPerTerm();
            $interes = $this->total_amount * $tasaInteres;
            $totalConInteres = $this->total_amount + $interes;

            $credit = Credit::create([
                'Client_ID' => $this->client_id,
                'Payment_Type_ID' => $this->payment_type_id,
                'Start_Date' => $this->start_date,
                'Due_Date' => $this->due_date,
                'Total_Amount' => $totalConInteres,
                'Interest_Rate' => $tasaInteres * 100,
                'Credit_Status' => $this->credit_status,
            ]);

            foreach ($this->creditDetails as $detail) {
                CreditDetail::create([
                    'Credit_ID' => $credit->Credit_ID,
                    'Product_ID' => $detail['product_id'],
                    'Quantity' => $detail['quantity'],
                    'Subtotal' => $detail['subtotal'],
                    'VAT' => $detail['vat'],
                ]);

                $inventory = DB::table('inventories')->where('Product_ID', $detail['product_id'])->first();
                if ($inventory && $inventory->Current_Stock >= $detail['quantity']) {
                    $newStock = $inventory->Current_Stock - $detail['quantity'];

                    DB::table('inventories')
                        ->where('Product_ID', $detail['product_id'])
                        ->update([
                            'Current_Stock' => $newStock,
                            'Last_Update' => now(),
                        ]);
                }
            }

            $transaction = Transaction::create([
                'Supplier_ID' => null,
                'User_ID' => auth()->user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Credit_ID' => $credit->Credit_ID,
                'Total' => $this->total_amount,
                'Transaction_Type' => 'Crédito',
                'Purchase_ID' => null,
                'Payment_Type_ID' => $this->payment_type_id,
            ]);

            DB::commit();
 
            $this->resetForm();
            $this->dispatch('credit-notify', [
                'type' => 'success',
                'title' => 'Éxito',
                'message' => 'Crédito creado exitosamente.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $this->dispatch('credit-notify', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Ocurrió un error al crear el crédito.',
                'timer' => 3000
            ]);
            return;
}
    }

    private function resetForm()
    {
      
        $this->paymentId = '';
        $this->credit_id = '';
        $this->payment_date = '';
        $this->payment_amount = '';
        $this->client_id = '';
        $this->term = '';
        $this->payment_type_id = '';
        $this->total_amount = 0;
        $this->installments = 0;
        $this->creditDetails = [];
        $this->interest_rate= 0;
        $this->credit_status = 'Pendiente';  
        $this->product_id = '';
        $this->quantity = '';
        $this->selectedStock = 0;
        $this->selectedPrice = 0.0;
        $this->showProductModal = false;
        $this->totalWithInterest = 0;
        $this->quotaAmount = 0;
        $this->start_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        
        $this->dispatch('resetSelect2');
    }

    public function render()
    {
        
        return view('livewire.credit.create-credit')
            ->layout('layouts.app');
    }
}