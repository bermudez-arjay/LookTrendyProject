<?php
namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Credit;
use App\Models\Client;
use Livewire\WithPagination;
use Carbon\Carbon;

class PaymentComponent extends Component
{
    use WithPagination;

    public $paymentId;
    public $credit_id;
    public $payment_date;
    public $payment_amount;
    public $search = '';
    public $searchCredit = '';
    public $isOpen = false;
    public $credits = [];

    protected $rules = [
        'credit_id' => 'required|exists:credits,Credit_ID',
        'payment_date' => 'required|date',
        'payment_amount' => 'required|numeric|min:0.01'
    ];

    protected $messages = [
        'credit_id.required' => 'Debe seleccionar un crédito.',
        'payment_date.required' => 'La fecha de pago es obligatoria.',
        'payment_amount.required' => 'El monto del pago es obligatorio.',
        'payment_amount.min' => 'El monto mínimo debe ser :min.'
    ];

    public function mount()
    {
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->loadCredits();
    }

    public function loadCredits()
    {
        $this->credits = Credit::query()
            ->with(['client', 'payments'])
            ->when($this->searchCredit, function($query) {
                $query->where('Credit_ID', 'like', '%'.$this->searchCredit.'%')
                    ->orWhereHas('client', function($q) {
                        $q->where('Client_FirstName', 'like', '%'.$this->searchCredit.'%')
                          ->orWhere('Client_LastName', 'like', '%'.$this->searchCredit.'%')
                          ->orWhere('Client_Identity', 'like', '%'.$this->searchCredit.'%');
                    });
            })
            ->orderBy('Credit_ID', 'desc')
            ->get()
            ->map(function($credit) {
                // Usar Total_Amount que es el nombre correcto del campo
                $credit->remaining_balance = $credit->Total_Amount - $credit->payments->sum('Payment_Amount');
                // Agregar nombre completo del cliente para fácil acceso
                $credit->client_full_name = $credit->client 
                    ? $credit->client->Client_FirstName . ' ' . $credit->client->Client_LastName 
                    : 'Cliente no encontrado';
                return $credit;
            })
            ->filter(function($credit) {
                return $credit->remaining_balance > 0;
            });
    }


    public function updatedSearchCredit()
    {
        $this->loadCredits();
    }

    public function render()
    {
        $payments = Payment::with(['credit.client'])
            ->when($this->search, function($query) {
                $query->whereHas('credit', function($q) {
                        $q->where('Credit_ID', 'like', '%'.$this->search.'%')
                          ->orWhereHas('client', function($q2) {
                              $q2->where('name', 'like', '%'.$this->search.'%');
                          });
                    })
                    ->orWhere('Payment_Amount', 'like', '%'.$this->search.'%')
                    ->orWhere('Payment_Date', 'like', '%'.$this->search.'%');
            })
            ->orderBy('Payment_Date', 'desc')
            ->paginate(10);

        return view('livewire.payments.payment-component', [
            'payments' => $payments
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
        $this->payment_amount = '';
        $this->payment_date = Carbon::now()->format('Y-m-d');
    }

    public function store()
    {
        $this->validate();

        // Validar que el monto no exceda el saldo pendiente
        $credit = Credit::find($this->credit_id);
        $totalPayments = $credit->payments->sum('Payment_Amount');
        $remainingBalance = $credit->Credit_Amount - $totalPayments;

        if ($this->payment_amount > $remainingBalance) {
            $this->addError('payment_amount', 'El monto excede el saldo pendiente del crédito ($'.number_format($remainingBalance, 2).')');
            return;
        }

        Payment::updateOrCreate(['Payment_ID' => $this->paymentId], [
            'Credit_ID' => $this->credit_id,
            'Payment_Date' => $this->payment_date,
            'Payment_Amount' => $this->payment_amount
        ]);

        session()->flash('message', 
            $this->paymentId ? 'Abono actualizado correctamente.' : 'Abono registrado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $this->paymentId = $id;
        $this->credit_id = $payment->Credit_ID;
        $this->payment_date = $payment->Payment_Date;
        $this->payment_amount = $payment->Payment_Amount;

        $this->loadCredits();
        $this->openModal();
    }

    public function delete($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        
        session()->flash('message', 'Abono eliminado correctamente.');
    }
}