<?php
namespace App\Livewire\Payments;

use LaravelLang\Publisher\Console\Update;
use Livewire\Component;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Credit;
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

    public function deleteConfirmed()
    {
        Payment::find($this->paymentToDelete)->delete();
        $this->showDeleteConfirmation = false;
        $this->paymentToDelete = null;
        session()->flash('message', 'Abono eliminado correctamente');
        $this->updateLastPayment();
        $this->dispatch('paymentUpdated');
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
        $this->paymentToDelete = null;
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
        'dollar_amount' => 'required_if:payment_type_id,2|numeric|min:0.01',
        'cordoba_amount' => 'required|numeric|min:0.01'
    ];

    protected $messages = [
        'dollar_amount.required_if' => 'El monto en dólares es requerido cuando el tipo de pago es en dólares',
        'cordoba_amount.required' => 'El monto en córdobas es requerido',
        'credit_id.required' => 'Debe seleccionar un crédito.',
        'payment_date.required' => 'La fecha de pago es obligatoria.',
        'payment_date.date' => 'La fecha debe ser válida.',
        'payment_amount.required' => 'El monto del pago es obligatorio.',
        'payment_amount.numeric' => 'El monto debe ser un número.',
        'payment_amount.min' => 'El monto mínimo debe ser :min.'

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
        }
    } else {
        $this->selectedCreditInfo = null;
    }
    $this->checkFullPayment(); 
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
        $this->paymentId = '';
        $this->credit_id = '';
        $this->dollar_amount = '';
        $this->cordoba_amount = '';
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->payment_type_id = '';
        $this->show_amount_fields = false;
    }

public function updatedPaymentTypeId($value)
{
    $this->show_amount_fields = in_array($value, [1, 2]);
    
    
    $this->resetAmountFields();
    if ($value == 2) {
        $this->cordoba_amount = 0;
    }
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

public function updatedCordobaAmount($value)
{
    if ($this->payment_type_id == 1) {
        $this->validateAmount();
        $this->checkFullPayment();
    }
}

public function updatedDollarAmount($value)
{
    if ($this->payment_type_id == 2 && is_numeric($value) && $value > 0) {
        $this->cordoba_amount = number_format(floatval($value) * $this->exchangeRate, 2);
        $this->validateAmount();
        $this->checkFullPayment();
    }
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

public function store()
{
    if (!$this->validateAmount()) {
        return;
    }
$credit = Credit::find($this->credit_id);
    $Credit_Status = $this->Credit_Status; 

    
    if ($Credit_Status == 'Vencido' && $this->apply_late_fee) {
        $late_fee = $credit->Total_Amount * 0.02;
    } else {
        $this->apply_late_fee = false;
        $late_fee = 0;
    }

  
    if ($Credit_Status == 'Pendiente' && $this->is_full_payment && $this->apply_early_discount) {
        $early_discount = $credit->remaining_balance * 0.05;
    } else {
        $this->apply_early_discount = false;
        $early_discount = 0;
    }
    $credit = Credit::find($this->credit_id);
    $base_amount = $this->payment_type_id == 2
        ? floatval($this->dollar_amount) * $this->exchangeRate
        : floatval($this->cordoba_amount);
   
   
    $this->final_amount = $base_amount + $late_fee - $early_discount;
    
    $max_allowed = $credit->remaining_balance + $late_fee;
    if ($this->final_amount > $max_allowed) {
        $this->addError('balance', 'El monto excede el saldo pendiente');
        return;
    }

   
    $paymentData = [
        'Credit_ID' => $this->credit_id,
        'Payment_Date' => $this->payment_date,
        'Payment_Amount' => $this->final_amount,
        'Payment_Type_ID' => $this->payment_type_id
      
    ];
  
        Payment::create(
            $paymentData);
        $message = 'Abono registrado correctamente';
        
    

    session()->flash('message', $message);
    $this->closeModal();
    $this->resetInputFields();
    $this->loadCredits();
    $this->loadSelectedCreditInfo();
    $this->dispatch('paymentUpdated');
    $this->updateLastPayment();
}
   
    public function selectCredit($creditId)
    {
        $this->credit_id = $creditId;
        $this->loadSelectedCreditInfo();
    }

   



}