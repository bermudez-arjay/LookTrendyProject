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
use Illuminate\Support\Facades\Http;

class PurchaseTrasanction extends Component
{
    public $selectedSupplierId = 0;
    public $purchaseDate;
    public $payment_type_id = null;
    public $dollar_amount;
    public $cordoba_amount;
    public $exchangeRate;
    public $show_amount_fields = false;
    public $productList = [];
    public $quantities = [];
    public $prices = [];
    public $showProductModal = false;
    public $received_amount = 0;
    public $change_amount = 0;
    public $productSearch = '';

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
        'payment_type_id.in' => 'Tipo de pago no válido',
        'dollar_amount.required' => 'El monto en dólares es requerido para pagos en dólares',
        'dollar_amount.numeric' => 'El monto en dólares debe ser numérico',
        'dollar_amount.min' => 'El monto en dólares debe ser mayor a 0',
        'cordoba_amount.required' => 'El monto en córdobas es requerido para pagos en córdobas',
        'cordoba_amount.numeric' => 'El monto en córdobas debe ser numérico',
        'cordoba_amount.min' => 'El monto en córdobas debe ser mayor a 0',
        'quantities.*.required' => 'La cantidad es requerida',
        'quantities.*.numeric' => 'La cantidad debe ser numérica',
        'quantities.*.min' => 'La cantidad mínima es 1',
        'quantities.*.max' => 'La cantidad máxima es 999',
        'prices.*.required' => 'El precio es requerido',
        'prices.*.numeric' => 'El precio debe ser numérico',
        'prices.*.min' => 'El precio mínimo es 0.01',
        'prices.*.decimal' => 'El precio debe tener máximo 2 decimales',
    ];

    public function mount()
    {
        $this->purchaseDate = now()->format('Y-m-d');
        $this->fetchExchangeRate();
    }

    public function fetchExchangeRate()
    {
        try {
            // Intenta obtener la tasa de cambio de una API
            $response = Http::get('https://api.bcn.gob.ni/tc');
            if ($response->successful()) {
                $data = $response->json();
                $this->exchangeRate = $data['valor'] ?? 36.5;
            } else {
                $this->exchangeRate = 36.5; // Valor por defecto si falla la API
            }
        } catch (\Exception $e) {
            $this->exchangeRate = 36.5; // Valor por defecto si hay error
        }
    }

    public function getConvertedAmountProperty()
    {
        if ($this->payment_type_id != 2 || empty($this->dollar_amount)) {
            return 0;
        }
        
        return round(floatval($this->dollar_amount) * $this->exchangeRate, 2);
    }

    public function getTotalAmountProperty()
    {
        return collect($this->productList)->sum('total_amount');
    }

    public function updatedPaymentTypeId($value)
    {
        $this->show_amount_fields = !empty($value);
        $this->reset(['dollar_amount', 'cordoba_amount', 'received_amount', 'change_amount']);
        $this->calculateChange();
    }

    public function updatedDollarAmount($value)
    {
        $this->calculateChange();
    }

    public function updatedCordobaAmount($value)
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if ($this->payment_type_id == 2 && !empty($this->dollar_amount)) {
            $this->received_amount = $this->dollar_amount * $this->exchangeRate;
        } elseif ($this->payment_type_id == 1 && !empty($this->cordoba_amount)) {
            $this->received_amount = $this->cordoba_amount;
        } else {
            $this->received_amount = 0;
        }
        
        $this->change_amount = max(0, $this->received_amount - $this->getTotalAmountProperty());
    }

    public function render()
    {
        $paymentTypes = PaymentType::all();
        $products = Product::with(['inventories'])
            ->where('Removed', 0)
            ->when($this->productSearch, function($query) {
                $query->where('Product_Name', 'like', '%'.$this->productSearch.'%');
            })
            ->orderBy('Product_Name')
            ->get();

        return view('livewire.purchase-transaction.purchase-trasanction', [
            'suppliers' => Supplier::orderBy('Supplier_Name')->get(),
            'products' => $products,
            'paymentTypes' => $paymentTypes,
            'exchangeRate' => $this->exchangeRate,
        ])->layout('layouts.app');
    }

    public function updatedQuantities($value, $key)
    {
        $productId = str_replace('quantities.', '', $key);
        if ($value < 1) {
            $this->addError('quantities.'.$productId, "La cantidad mínima es 1.");
        } elseif ($value > 999) {
            $this->addError('quantities.'.$productId, "La cantidad máxima es 999.");
        } else {
            $this->resetErrorBag('quantities.'.$productId);
        }
    }

    public function updatedPrices($value, $key)
    {
        $productId = str_replace('prices.', '', $key);
        if (!is_numeric($value) || $value < 0.01) {
            $this->addError('prices.'.$productId, "El precio mínimo es 0.01.");
        } else {
            $this->resetErrorBag('prices.'.$productId);
        }
    }

    public function addProduct($productId = null)
    {
        $this->validate([
            "quantities.$productId" => 'required|numeric|min:1|max:999',
            "prices.$productId" => 'required|numeric|min:0.01|decimal:0,2'
        ]);

        $product = Product::findOrFail($productId);
        $quantity = $this->quantities[$productId] ?? 0;
        $unitPrice = $this->prices[$productId];

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
        $this->calculateChange();
    }

    public function cancelPurchase()
    {
        $this->reset();
        $this->purchaseDate = now()->format('Y-m-d');
        $this->fetchExchangeRate();
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
        $this->purchaseDate = now()->format('Y-m-d');
        $this->show_amount_fields = false;
        $this->fetchExchangeRate();
    }

    public function savePurchase()
    {
        $this->validate();

        // Validación adicional antes de la transacción
        if (empty($this->productList)) {
            session()->flash('error', 'Debe agregar al menos un producto');
            return;
        }

        foreach ($this->productList as $item) {
            if ($item['quantity'] <= 0 || $item['unit_price'] <= 0) {
                session()->flash('error', 'Cantidades y precios deben ser mayores a cero');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $date = Carbon::parse($this->purchaseDate);
            $subtotal = collect($this->productList)->sum('subtotal');
            $vatAmount = $subtotal * 0.15;
            $totalAmount = $subtotal + $vatAmount;

            $time = Time::firstOrCreate([
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
                'User_ID' => Auth::id(),
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
                    'Quantity' => $item['quantity'],
                    'Unit_Price' => $item['unit_price'],
                    'Subtotal' => $item['subtotal'],
                    'VAT' => $item['vat'],
                    'Total_With_VAT' => $item['total_amount'],
                ]);

                Inventory::updateOrCreate(
                    ['Product_ID' => $item['product_id']],
                    [
                        'Current_Stock' => DB::raw('IFNULL(Current_Stock, 0) + ' . $item['quantity']),
                        'Last_Update' => $date->format('Y-m-d')
                    ]
                );
            }

            $receivedAmount = $this->payment_type_id == 2
                ? ($this->dollar_amount * $this->exchangeRate)
                : $this->cordoba_amount;

            $changeAmount = $receivedAmount - $totalAmount;
            $this->change_amount = max(0, $changeAmount);

            Transaction::create([
                'Purchase_ID' => $purchase->Purchase_ID,
                'Supplier_ID' => $this->selectedSupplierId,
                'User_ID' => auth()->user()->User_ID,
                'Time_ID' => $time->Time_ID,
                'Total' => $totalAmount,
                'Transaction_Type' => 'Compra',
                'Payment_Type_ID' => $this->payment_type_id,
                'Received_Amount' => $receivedAmount,
                'Exchange_Rate' => $this->exchangeRate,
                'Dollar_Amount' => $this->payment_type_id == 2 ? $this->dollar_amount : null,
            ]);

            DB::commit();

            $this->resetForm();
            session()->flash('success', 'Compra registrada exitosamente.');

            return $this->generatePdf(
                $purchase->Purchase_ID,
                $totalAmount,
                $receivedAmount,
                $changeAmount
            );

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al registrar la compra: ' . $e->getMessage());
            return;
        }
    }

    public function generatePdf($purchaseId, $totalAmount, $receivedAmount, $changeAmount)
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
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $data = [
            'purchase' => $purchase,
            'invoice_number' => str_pad($purchase->Purchase_ID, 8, '0', STR_PAD_LEFT),
            'date' => Carbon::parse($purchase->Purchase_Date)->format('d/m/Y'),
            'payment_type' => $paymentMethodName,
            'subtotal' => $purchase->purchaseDetails->sum('Subtotal'),
            'vat' => $purchase->purchaseDetails->sum('VAT'),
            'total' => $totalAmount,
            'received_amount' => $receivedAmount,
            'change_amount' => $changeAmount,
            'user' => auth()->user(),
            'exchange_rate' => $this->exchangeRate,
        ];

        $html = view('livewire.purchase-transaction.invoice', $data)->render();
        
        $mpdf->SetTitle("Factura de Compra #{$purchase->Purchase_ID}");
        $mpdf->SetAuthor(auth()->user()->name);
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