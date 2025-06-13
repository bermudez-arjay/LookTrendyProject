<?php

namespace App\Livewire\PurchaseTransaction;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Inventory;
use App\Models\Time;
use App\Models\Transaction;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PurchaseTrasanction extends Component
{
    public $selectedSupplierId = 0;
    public $purchaseDate;
    public $payment_type_id = null;
    public $dollar_amount;
    public $cordoba_amount;
    public $exchangeRate = 36.5;
    public $show_amount_fields = false;
    public $productList = [];
    public $quantities = [];
    public $prices = [];
    public $showProductModal = false;
    public $received_amount = 0;
    public $change_amount = 0;

    protected function rules()
    {
        $rules = [
            'selectedSupplierId' => 'required|numeric|min:1',
            'purchaseDate' => 'required|date',
            'productList' => 'required|array|min:1',
            'payment_type_id' => 'required|in:1,2',
        ];

        $totalAmount = $this->getTotalAmountProperty();

        if ($this->payment_type_id == 1) {
            $rules['cordoba_amount'] = [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($totalAmount) {
                    if ($value < $totalAmount) {
                        $fail("El monto pagado (C$".number_format($value, 2).") no puede ser menor al total de la compra (C$".number_format($totalAmount, 2).")");
                    }
                }
            ];
        }

        if ($this->payment_type_id == 2) {
            $rules['dollar_amount'] = [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($totalAmount) {
                    $convertedAmount = $value * $this->exchangeRate;
                    if ($convertedAmount < $totalAmount) {
                        $fail("El monto pagado ($".number_format($value, 2)." = C$".number_format($convertedAmount, 2).") no puede ser menor al total de la compra (C$".number_format($totalAmount, 2).")");
                    }
                }
            ];
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
        'dollar_amount.required' => 'El monto en dólares es requerido para pagos en dólares',
        'dollar_amount.numeric' => 'El monto en dólares debe ser numérico',
        'dollar_amount.min' => 'El monto en dólares debe ser mayor a 0',
        'cordoba_amount.required' => 'El monto en córdobas es requerido para pagos en córdobas',
        'cordoba_amount.numeric' => 'El monto en córdobas debe ser numérico',
        'cordoba_amount.min' => 'El monto en córdobas debe ser mayor a 0',
    ];

    public function getConvertedAmountProperty()
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

    public function updatedPaymentTypeId($value)
    {
        if ($value == 1) {
            $this->dollar_amount = null;
        } else {
            $this->cordoba_amount = null;
        }
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
            $paid = floatval($this->cordoba_amount) ?? 0;
            $this->change_amount = max(0, $paid - $total);
            if ($paid > 0 && $paid < $total) {
                $this->addError('cordoba_amount', "El monto pagado no cubre el total de la compra (C$".number_format($total, 2).")");
            } else {
                $this->resetErrorBag('cordoba_amount');
            }
        } else {
            $paid = floatval($this->dollar_amount) ?? 0;
            $converted = $paid * $this->exchangeRate;
            $this->change_amount = max(0, $converted - $total);
            $this->cordoba_amount = $converted;
            if ($paid > 0 && $converted < $total) {
                $this->addError('dollar_amount', "El monto pagado no cubre el total de la compra (C$".number_format($total, 2).")");
            } else {
                $this->resetErrorBag('dollar_amount');
            }
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
        } elseif ($this->payment_type_id == 2) {
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
        $this->purchaseDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $paymentTypes = PaymentType::all();
         $products = Product::with('inventories') // Carga la relación de inventario
        ->where('removed', 0)
        ->orderBy('Product_Name')
        ->get();
        
        return view('livewire.purchase-transaction.purchase-trasanction', [
            'suppliers' => Supplier::orderBy('Supplier_Name')->get(),
            'products' => $products,
            'paymentTypes' => $paymentTypes
        ])->layout('layouts.app');
    }

    public function addProduct($productId = null)
    {
        $this->validate([
            "quantities.$productId" => 'required|numeric|min:1|max:999',
            "prices.$productId" => 'required|numeric|min:0.01'
        ], [
            "quantities.$productId.required" => 'La cantidad es requerida',
            "quantities.$productId.numeric" => 'La cantidad debe ser un número',
            "quantities.$productId.min" => 'La cantidad mínima es 1',
            "quantities.$productId.max" => 'La cantidad máxima es 999',
            "prices.$productId.required" => 'El precio es requerido',
            "prices.$productId.numeric" => 'El precio debe ser un número',
            "prices.$productId.min" => 'El precio mínimo es 0.01'
        ]);

        $product = Product::findOrFail($productId);
        $quantity = $this->quantities[$productId] ?? 0;
        $unitPrice = $this->prices[$productId] ?? 0;

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

        $subtotal = $unitPrice * $newQuantity;
        $vat = $subtotal * 0.15;
        $total = $subtotal + $vat;

        if ($existingIndex !== null) {
            $this->productList[$existingIndex] = [
                'product_id' => $product->Product_ID,
                'product_name' => $product->Product_Name,
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_amount' => $total,
            ];
        } else {
            $this->productList[] = [
                'product_id' => $product->Product_ID,
                'product_name' => $product->Product_Name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_amount' => $total,
            ];
        }
        
        unset($this->quantities[$productId]);
        unset($this->prices[$productId]);
        $this->resetErrorBag();
        $this->showProductModal = false;
        $this->calculateChange();
    }

    public function removeProduct($index)
    {
        unset($this->productList[$index]);
        $this->productList = array_values($this->productList);
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
            'prices'
        ]);
        
        $this->resetValidation();
        $this->selectedSupplierId = '';
        $this->payment_type_id = '';
        $this->purchaseDate = now()->format('Y-m-d');
        $this->show_amount_fields = false;
    }

    public function savePurchase()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();

            foreach ($this->productList as $item) {
                if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                    throw new \Exception("Cantidad inválida para el producto ID: {$item['product_id']}");
                }
            }

            $date = Carbon::parse($this->purchaseDate);
            $subtotal = collect($this->productList)->sum('subtotal');
            $vatAmount = $subtotal * 0.15;
            $totalAmount = $subtotal + $vatAmount;

            $time = Time::create([
                'Date' => $date->format('Y-m-d'),
                'Year' => $date->year,
                'Quarter' => ceil($date->month / 3),
                'Month' => $date->month,
                'Week' => $date->weekOfYear,
                'Hour' => $date->format('H:i:s'),
                'Day_of_Week' => $date->dayOfWeekIso,
            ]);

            $purchase = Purchase::create([
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => auth()->user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Purchase_Date' => $this->purchaseDate,
                'Purchase_VAT' => $vatAmount,
                'Total_Amount' => $totalAmount,
                'Purchase_Status' => 'Completada',
                'Payment_Type_ID' => $this->payment_type_id,
            ]);

            foreach ($this->productList as $item) {
                PurchaseDetail::create([
                    'Purchase_ID' => $purchase->Purchase_ID,
                    'Product_ID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'Unit_Price' => $item['unit_price'],
                    'Subtotal' => $item['subtotal'],
                ]);

                // Incrementar el stock en lugar de decrementar
                Inventory::updateOrCreate(
                    ['Product_ID' => $item['product_id']],
                    [
                        'Current_Stock' => DB::raw("COALESCE(Current_Stock, 0) + {$item['quantity']}"),
                        'Minimum_Stock' => DB::raw("COALESCE(Minimum_Stock, 5)")
                    ]
                );
            }

            $paidAmount = $this->payment_type_id == 2
                ? ($this->dollar_amount * $this->exchangeRate)
                : $this->cordoba_amount;

            $changeAmount = $paidAmount - $totalAmount;
            $this->change_amount = max(0, $changeAmount);

            $transaction = Transaction::create([
                'Purchase_ID' => $purchase->Purchase_ID,
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => auth()->user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Credit_ID' => null,
                'Total' => $totalAmount,
                'Transaction_Type' => 'Compra',
                'Sale_ID' => null,
                'Payment_Type_ID' => $this->payment_type_id,
                'Received_Amount' => $paidAmount,
                'Exchange_Rate' => $this->exchangeRate,
                'Dollar_Amount' => $this->payment_type_id == 2 ? $this->dollar_amount : null,
            ]);

            DB::commit();

            $this->resetForm();
            $this->purchaseDate = now()->format('Y-m-d');
            session()->flash('success', 'Compra registrada exitosamente.');
            
            return $this->generatePdf(
                $purchase->Purchase_ID,
                $totalAmount,
                $paidAmount,
                $changeAmount
            );

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al registrar la compra: ' . $e->getMessage());
            return back();
        }
    }

public function generatePdf($purchaseId, $totalAmount, $paidAmount, $changeAmount)
{
    $purchase = Purchase::with(['supplier', 'purchaseDetails.product', 'transactions.paymentType'])
              ->findOrFail($purchaseId);
    
    $transaction = $purchase->transactions->first();
    $paymentMethodName = $transaction->paymentType->Payment_Type_Name ?? 'No especificado';
    $changeAmount = max(0, $changeAmount);
    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
        'default_font' => 'dejavusans',
        'tempDir' => storage_path('app/mpdf/tmp'),
    ]);

    $data = [
        'purchase' => $purchase,
        'invoice_number' => str_pad($purchase->Purchase_ID, 8, '0', STR_PAD_LEFT),
        'date' => Carbon::parse($purchase->Purchase_Date)->format('d/m/Y'),
        'payment_type' => $paymentMethodName,
        'subtotal' => $purchase->purchaseDetails->sum('Subtotal'),
        'vat' => $purchase->Purchase_VAT,
        'total' => $totalAmount,
        'paid_amount' => $paidAmount,
        'change_amount' => $changeAmount,
        'user' => auth()->user(),
    ];

   $html = view('livewire.purchase-transaction.invoice', $data)->render();
    $mpdf->WriteHTML($html);

    return response()->streamDownload(
        function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        },
        "factura-compra-{$purchase->Purchase_ID}.pdf",
        ['Content-Type' => 'application/pdf']
    );
}
}