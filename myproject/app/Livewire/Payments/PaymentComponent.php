<?php
namespace App\Livewire\Payments;


use Livewire\Component;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Credit;
use App\Models\Inventory;
use App\Models\Client;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Carbon\Carbon;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;


class PaymentComponent extends Component
{
    use WithPagination;
    public $payment_type_id;
    public $paymentId;
    public $credit_id;
    public $payment_date;
    public $search = '';
    public $searchCredit = '';
    public $isOpen = false;
    public $credits = [];
    public $selectedCreditInfo = null;
    public $lastPayment;
    public $lastPaymentHumanDate = 'Sin registros';
    public $totalPaymentAmount, $paymentMonth;
    public $exchangeRate = 36.5;
    public $dollar_amount = '';
    public $cordoba_amount = '';
public $show_amount_fields = false;
    public $balance_error = '';
    public $showDeleteConfirmation = false;
    public $paymentToDelete = null;
    protected $skipAmountUpdate = false;
    public $apply_late_fee = false;
public $apply_early_discount = false;
public $is_full_payment = false;
 public $statusClass = '';
    public $statusMessage = '';
    public $received_amount = 0;
public $change_amount = 0;
public $show_change_field = false;
protected $queryString = ['baseBalance'];



    protected $listeners = ['paymentUpdated' => 'updateLastPayment'];

    public function confirmDelete($paymentId)
    {
        $this->paymentToDelete = $paymentId;
        $this->showDeleteConfirmation = true;
    }
    public function getConvertedAmountProperty()
    {
        if ($this->payment_type_id == 2 && is_numeric($this->dollar_amount)) {
            return number_format($this->dollar_amount * $this->exchangeRate, 2);
        }
        return '0.00';
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


public function updatedPaymentTypeId()
{
    $this->dollar_amount = null;
    $this->cordoba_amount = null;
    $this->change_amount = 0;
}
   
    

    public function updateLastPayment()
    {
        $this->lastPayment = Payment::with(['credit', 'credit.client'])
            ->orderBy('Payment_Date', 'desc')
            ->orderBy('Payment_ID', 'desc')
            ->first();

        $this->lastPaymentHumanDate = $this->lastPayment
            ? Carbon::parse($this->lastPayment->Payment_Date)->diffForHumans()
            : 'Sin registros';

        $this->totalPaymentAmount = Payment::sum('Payment_Amount');
        $this->paymentMonth = Payment::whereMonth('Payment_Date', now()->month)
            ->sum('Payment_Amount');
    }


    protected $rules = [
    'credit_id' => 'required|exists:credits,Credit_ID',
    'payment_date' => 'required|date',
    'payment_type_id' => 'required|exists:payment_types,Payment_Type_ID',
    'dollar_amount' => 'required_if:payment_type_id,2|numeric|min:0.01|nullable',
    'cordoba_amount' => 'required_if:payment_type_id,1|numeric|min:0.01|nullable'
];


  protected $messages = [
    'credit_id.required' => 'Debe seleccionar un crédito.',
    'credit_id.exists' => 'El crédito seleccionado no existe.',
    'payment_date.required' => 'La fecha de pago es obligatoria.',
    'payment_date.date' => 'La fecha debe ser válida.',
    'payment_type_id.required' => 'El tipo de pago es obligatorio.',
    'payment_type_id.exists' => 'El tipo de pago seleccionado no existe.',
    'dollar_amount.required_if' => 'El monto en dólares es requerido cuando el tipo de pago es en dólares.',
    'dollar_amount.numeric' => 'El monto en dólares debe ser un número válido.',
    'dollar_amount.min' => 'El monto mínimo en dólares debe ser :min.',
    'cordoba_amount.required_if' => 'El monto en córdobas es requerido cuando el tipo de pago es en córdobas.',
    'cordoba_amount.numeric' => 'El monto en córdobas debe ser un número válido.',
    'cordoba_amount.min' => 'El monto mínimo en córdobas debe ser :min.'
];
    public function receipt($paymentId)
    {
        $payment = Payment::with(['credit.client', 'credit.payments'])->findOrFail($paymentId);
        $totalPayments = $payment->credit->payments->sum('Payment_Amount');
        $remainingBalance = $payment->credit->Total_Amount - $totalPayments;

        $payment->remaining_balance = $remainingBalance;

        return view('livewire.payments.receipt', compact('payment'));
    }

    public function generatePdf($paymentId)
    {
        $payment = Payment::with(['credit.client', 'credit.payments', 'credit.paymentType'])->findOrFail($paymentId);

        $totalPayments = $payment->credit->payments->sum('Payment_Amount');
        $remainingBalance = $payment->credit->Total_Amount - $totalPayments;
        $payment->remaining_balance = $remainingBalance;

        $paymentMethodName = $payment->paymentType->Payment_Type_Name ?? 'No especificado';


        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'dejavusans' => [
                    'R' => 'DejaVuSans.ttf',
                    'B' => 'DejaVuSans-Bold.ttf',
                ],
            ],
            'default_font' => 'dejavusans',
            'tempDir' => storage_path('app/mpdf/tmp'),
        ]);


        $html = view('livewire.payments.receipt', [
            'payment' => $payment,
            'paymentMethodName' => $paymentMethodName,
        ])->render();

        $mpdf->WriteHTML($html);

        return response()->streamDownload(
            function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            },
            "recibo-pago-{$payment->Payment_ID}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }
    public function mount()
    {
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->loadCredits();
        $this->updateLastPayment();
        $this->totalPaymentAmount = Payment::sum('Payment_Amount');
        $this->paymentMonth = Payment::whereMonth('Payment_Date', Carbon::now()->month)
            ->sum('Payment_Amount');
    }

    public function loadCredits()
    {
        $this->credits = Credit::query()
            ->with(['client', 'payments'])
            ->when($this->searchCredit, function ($query) {
                $query->where('Credit_ID', 'like', '%' . $this->searchCredit . '%')
                    ->orWhereHas('client', function ($q) {
                        $q->where('Client_FirstName', 'like', '%' . $this->searchCredit . '%')
                            ->orWhere('Client_LastName', 'like', '%' . $this->searchCredit . '%')
                            ->orWhere('Client_Identity', 'like', '%' . $this->searchCredit . '%');
                    });
            })
            ->orderBy('Credit_ID', 'desc')
            ->get()
            ->map(function ($credit) {
                $credit->client_full_name = $credit->client
                    ? $credit->client->Client_FirstName . ' ' . $credit->client->Client_LastName
                    : 'Cliente no encontrado';

                $totalPayments = $credit->payments->sum('Payment_Amount');
                $credit->remaining_balance = $credit->Total_Amount - $totalPayments;

                return $credit;
            })
            ->filter(function ($credit) {

                return $credit->remaining_balance > 0;
            });

        $this->loadSelectedCreditInfo();
    }

     public function loadSelectedCreditInfo()
{
    if ($this->credit_id) {
        $this->selectedCreditInfo = Credit::with(['client', 'payments'])
            ->find($this->credit_id);

        if ($this->selectedCreditInfo) {
            $totalPayments = $this->selectedCreditInfo->payments->sum('Payment_Amount');
            $this->selectedCreditInfo->remaining_balance = 
                $this->selectedCreditInfo->Total_Amount - $totalPayments;
            
            $this->apply_late_fee = false;
            $this->apply_early_discount = false;
            
            $this->determineCreditStatus();
            $this->checkFullPayment(); 
        }
    } else {
        $this->selectedCreditInfo = null;
    }
}
 protected function determineCreditStatus()
    {
        if (!$this->selectedCreditInfo) return;

      $status = $this->creditStatus;
        
        switch ($status) {
            case 'Cancelado':
                $this->statusClass = 'bg-blue-50 text-blue-800 border border-blue-200';
                $this->statusMessage = 'Cancelado (pagado completamente)';
                break;
                
            case 'Vencido':
                $this->statusClass = 'bg-red-50 text-red-800 border border-red-200';
                $this->statusMessage = 'Vencido (desde '.$this->selectedCreditInfo->Due_Date.')';
                break;
                
            case 'Pendiente':
                $this->statusClass = 'bg-yellow-50 text-yellow-800 border border-yellow-200';
                $this->statusMessage = 'Pendiente (vence '.$this->selectedCreditInfo->Due_Date.')';
                break;
                
            default:
                $this->statusClass = 'bg-gray-50 text-gray-800 border border-gray-200';
                $this->statusMessage = 'Estado desconocido';
        }
    }
   public function getCreditStatusProperty()
{
    
    if (!$this->selectedCreditInfo) return null;
    

    if ($this->selectedCreditInfo->remaining_balance <= 0) {
        return 'Cancelado';
    }

    if ($this->selectedCreditInfo->Due_Date && now()->gt($this->selectedCreditInfo->Due_Date)) {
        return 'Vencido';
    }

    return 'Pendiente';
    
}


   public function updatedCreditId($value)
{
    $this->loadSelectedCreditInfo();
    $this->resetPaymentFields();
    $this->show_amount_fields = false;
    if ($value) {
        $this->loadSelectedCreditInfo();
    }
}

    public function updatedSearchCredit()
    {
        $this->loadCredits();
    }

    public function render()
    {

        $payments = Payment::with(['credit.client'])
            ->when($this->search, function ($query) {
                $query->whereHas('credit', function ($q) {
                    $q->where('Credit_ID', 'like', '%' . $this->search . '%')
                        ->orWhereHas('client', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                    ->orWhere('Payment_Amount', 'like', '%' . $this->search . '%')
                    ->orWhere('Payment_Date', 'like', '%' . $this->search . '%');
            })
            ->orderBy('Payment_Date', 'desc')
            ->paginate(10);
  $paymentTypes = PaymentType::all();
        return view('livewire.payments.payment-component', [
            'payments' => $payments,
            'lastPayment' => $this->lastPayment,
            'lastPaymentHumanDate' => $this->lastPaymentHumanDate,
            'paymentTypes' => $paymentTypes
        ])->layout('layouts.app');

    }

    public function create()
    {
        $this->resetInputFields();
        $this->loadCredits();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetErrorBag();
    }

   private function resetInputFields()
{
    $this->reset([
        'paymentId',
        'credit_id',
        'dollar_amount',
        'cordoba_amount',
        'payment_date',
        'payment_type_id',
        'show_amount_fields',
        'show_change_field',
        'change_amount',
        'received_amount',
        'apply_late_fee',
        'apply_early_discount'
    ]);
    $this->payment_date = now()->format('Y-m-d');
}

    public function updatePaymentFields()
{
    $this->resetAmountFields();
    $this->show_amount_fields = in_array($this->payment_type_id, [1, 2]);
}

public function resetPaymentFields()
{
    $this->payment_type_id = null;
    $this->show_amount_fields = in_array($this->payment_type_id, [1, 2]);
    $this->resetAmountFields();
    $this->show_amount_fields = false;
}
public function resetAmountFields()
{
    $this->dollar_amount = null;
    $this->cordoba_amount = null;
    $this->dispatch('amounts-reset');
}


protected function checkFullPayment()
{
       if (!$this->credit_id || !$this->selectedCreditInfo) return;
  
    
    $amount = $this->payment_type_id == 2 
        ? floatval($this->dollar_amount) * $this->exchangeRate
        : floatval($this->cordoba_amount);
    
    $this->is_full_payment = abs($amount - $this->selectedCreditInfo->remaining_balance) < 0.01;
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
        if ($this->cordoba_amount > $this->selectedCreditInfo->remaining_balance) {
            $this->addError('cordoba_amount', 'El monto excede el saldo pendiente');
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
        
        $cordobaEquivalent = floatval($this->dollar_amount) * $this->exchangeRate;
        if ($cordobaEquivalent > $this->selectedCreditInfo->remaining_balance) {
            $this->addError('dollar_amount', 'El monto excede el saldo pendiente');
            return false;
        }
    }

    return true;
}

    public function selectCredit($creditId)
    {
        $this->credit_id = $creditId;
        $this->loadSelectedCreditInfo();
    }

  protected function log($message, $context = [])
{
    \Log::debug("PaymentComponent: $message", $context);
}

public function calculateChange()
{
     $this->change_amount = 0;
    $this->show_change_field = false;

    if (!is_numeric($this->dollar_amount) && !is_numeric($this->cordoba_amount)) return;
   
    if (!$this->selectedCreditInfo) return;
    $totalToPay = $this->baseBalance;
    if ($this->apply_late_fee) {
        $totalToPay += $this->baseBalance * 0.02;
    }
    if ($this->apply_early_discount) {
        $totalToPay -= $this->baseBalance * 0.05;
    }
    
    
    $received = $this->payment_type_id == 2
        ? (float)$this->dollar_amount * $this->exchangeRate
        : (float)$this->cordoba_amount;
   
    if ($received > $totalToPay) {
        $this->change_amount = $received - $totalToPay;
        $this->show_change_field = true;
    }
    
    $this->totalPaymentAmount = $totalToPay;
}


public function updatedDollarAmount($value)
{
    if ($this->payment_type_id == 2) {
        $this->cordoba_amount = $value * $this->exchangeRate;
        $this->checkFullPayment(); 
        $this->calculateChange();
    }
}

public function updatedCordobaAmount($value)
{
    if ($this->payment_type_id == 1) {
        $this->checkFullPayment(); 
        $this->calculateChange();
    }
}

public function getTotalPaymentAmountProperty()
{
    $this->log('Estado del crédito', [
    'status' => $this->creditStatus,
    'is_full_payment' => $this->is_full_payment,
    'apply_late_fee' => $this->apply_late_fee,
    'apply_early_discount' => $this->apply_early_discount
]);
    if (!$this->selectedCreditInfo) {
        return 0;
    }
    
    $baseAmount = $this->selectedCreditInfo->remaining_balance;
    if ($this->apply_late_fee && $this->creditStatus == 'Vencido') {
        $lateFee = $baseAmount * 0.02;
        $baseAmount += $lateFee;
    }

    if ($this->apply_early_discount && $this->creditStatus == 'Pendiente' && $this->is_full_payment) {
        $discount = $baseAmount * 0.05;
        $baseAmount -= $discount;
    }
    
    return $baseAmount;
}


public function getBaseBalanceProperty()
{
    if (!$this->selectedCreditInfo) {
        $this->log('No hay crédito seleccionado para obtener saldo base');
        return 0;
    }
    
    $balance = $this->selectedCreditInfo->remaining_balance;
    $this->log('Saldo base obtenido', ['balance' => $balance]);
    return $balance;
}
public function store()
{
    $this->validate();
    
    try {
       
        $amount = $this->payment_type_id == 2
            ? (float)$this->dollar_amount * $this->exchangeRate
            : (float)$this->cordoba_amount;
        $lateFee = $this->apply_late_fee ? $this->baseBalance * 0.02 : 0;
        $earlyDiscount = $this->apply_early_discount ? $this->baseBalance * 0.05 : 0;
        $finalAmount = $amount - $earlyDiscount;
        Payment::create([
            'Credit_ID' => $this->credit_id,
            'Payment_Date' => $this->payment_date,
            'Payment_Amount' => $finalAmount,
            'Payment_Type_ID' => $this->payment_type_id,
            'Late_Fee' => $lateFee,
            'Early_Discount' => $earlyDiscount,
            'Payment_Currency' => $this->payment_type_id == 2 ? 'USD' : 'NIO',
            'Exchange_Rate' => $this->payment_type_id == 2 ? $this->exchangeRate : null
        ]);

       
        $credit = Credit::find($this->credit_id);
        
    
        if ($credit->remaining_amount <= 0) {
            $credit->update([
                'credit_status' => 'Cancelado'
            ]);
            
            $this->log('Crédito marcado como Cancelado', [
                'credit_id' => $this->credit_id,
                'remaining_amount' => $credit->remaining_amount
            ]);
        }

        session()->flash('message', 'Pago registrado correctamente');
        $this->resetInputFields();
        $this->dispatch('paymentUpdated');
        $this->closeModal();

    } catch (\Exception $e) {
        $this->log('Error al guardar pago', ['error' => $e->getMessage()]);
        session()->flash('error', 'Error al registrar el pago: '.$e->getMessage());
    }
}

public function boot()
{
    if (config('logging.channels.payments')) {
        config(['logging.channels.payments' => [
            'driver' => 'single',
            'path' => storage_path('logs/payments.log'),
            'level' => 'debug',
        ]]);
    }
    
    $this->log('Componente PaymentComponent montado');
}

public function checkState()
{
    $this->log('Estado actual del componente', [
        'show_change_field' => $this->show_change_field,
        'change_amount' => $this->change_amount,
        'selectedCreditInfo' => $this->selectedCreditInfo ? $this->selectedCreditInfo->toArray() : null,
        'totalPaymentAmount' => $this->totalPaymentAmount,
        'payment_type_id' => $this->payment_type_id,
        'dollar_amount' => $this->dollar_amount,
        'cordoba_amount' => $this->cordoba_amount
    ]);
}

public function hydrate()
{
    $this->log('Componente hidratado', [
        'show_change_field' => $this->show_change_field,
        'change_amount' => $this->change_amount,
        'dollar_amount' => $this->dollar_amount,
        'cordoba_amount' => $this->cordoba_amount
    ]);
}

public function dehydrate()
{
    $this->log('Componente deshidratado', [
        'show_change_field' => $this->show_change_field,
        'change_amount' => $this->change_amount
    ]);
}

}