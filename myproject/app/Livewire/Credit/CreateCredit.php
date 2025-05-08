<?php

namespace App\Livewire\Credit;

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

    public $creditDetails = [];

    // Campos temporales para agregar productos
    public $product_id, $quantity, $payment_date, $payment_amount;

    protected $listeners = ['selectProductChanged', 'selectClientChanged', 'selectPaymentTypeChanged', 'selectTermChanged', 'product-changed' => 'updateProductInfo'];

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->due_date = date('Y-m-d', strtotime('+30 days'));
        $this->clients = Client::all();
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

    private function obtenerInteresPorPlazo()
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

    public function calcularCredito()
    {
        $tasaInteres = $this->obtenerInteresPorPlazo();

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

    public function addDetail()
    {

        if (!$this->product_id || !$this->quantity) {
            $this->dispatch('swal:error', [
                'title' => 'Datos incompletos',
                'message' => 'Debe seleccionar un producto y una cantidad.'
            ]);
            return;
        }

        if ($this->quantity > $this->selectedStock) {
            $this->dispatch('swal:error', [
                'title' => 'Stock insuficiente',
                'message' => 'La cantidad supera el stock disponible.'
            ]);
            return;
        }

        $product = Product::find($this->product_id);

        if (!$product) {
            $this->dispatch('swal:error', [
                'title' => 'Producto no válido',
                'message' => 'El producto seleccionado no existe en la base de datos.'
            ]);
            return;
        }

        $existingDetailIndex = collect($this->creditDetails)->search(function ($detail) {
            return $detail['product_id'] == $this->product_id;
        });

        if ($existingDetailIndex !== false) {

            $newQuantity = $this->creditDetails[$existingDetailIndex]['quantity'] + $this->quantity;
            if ($newQuantity > $this->selectedStock) {
                $this->dispatch('swal:error', ['message' => 'La cantidad total supera el stock disponible.']);
                return;
            }

            $this->creditDetails[$existingDetailIndex]['quantity'] = $newQuantity;
            $subtotal = $product->Unit_Price * $newQuantity;
            $vat = $subtotal * 0.15;
            $total = $subtotal + $vat;

            $this->creditDetails[$existingDetailIndex]['subtotal'] = $subtotal;
            $this->creditDetails[$existingDetailIndex]['vat'] = $vat;
            $this->creditDetails[$existingDetailIndex]['total_with_vat'] = $total;
        } else {

            $subtotal = $product->Unit_Price * $this->quantity;
            $vat = $subtotal * 0.15;
            $total = $subtotal + $vat;

            $this->creditDetails[] = [
                'product_id' => $product->Product_ID,
                'product_name' => $product->Product_Name,
                'quantity' => $this->quantity,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_with_vat' => $total,
            ];
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

            $this->dispatch('swal:success', [
                'title' => 'Producto eliminado',
                'message' => 'El producto fue eliminado correctamente.',
                'timer' => 2000
            ]);

            $this->dispatch('resetSelect2');
        }
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,Client_ID',
            'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'total_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'creditDetails' => 'required|array|min:1',
        ]);

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

            $tasaInteres = $this->obtenerInteresPorPlazo();
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

            $this->dispatch('swal:success', [
                'title' => 'Crédito creado',
                'message' => 'El crédito se ha registrado correctamente.',
                'timer' => 3000,
                'callback' => 'Livewire.emit("resetForm")'
            ]);

            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal:error', [
                'title' => 'Error al guardar',
                'message' => 'Ocurrió un error al intentar guardar el crédito: ' . $e->getMessage()
            ]);
        }
    }

    private function resetForm()
    {
        $this->reset([
            'client_id',
            'payment_type_id',
            'start_date',
            'due_date',
            'term',
            'total_amount',
            'interest_rate',
            'installments',
            'creditDetails',
            'product_id',
            'quantity',
            'payment_date',
            'payment_amount',
            'showProductModal'
        ]);


        $this->totalWithInterest = 0;
        $this->quotaAmount = 0;
        $this->selectedStock = 0;
        $this->selectedPrice = 0;
        $this->credit_status = 'Pendiente';

        $this->start_date = date('Y-m-d');
        $this->due_date = date('Y-m-d', strtotime('+30 days'));

        $this->dispatch('resetSelect2');

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.credit.create-credit')
            ->layout('layouts.app');
    }
}