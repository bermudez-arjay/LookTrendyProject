<?php
    namespace App\Livewire\Payments;
    
    use Livewire\Component;
    use App\Models\Payment;
    use App\Models\Credit;
    use App\Models\Client;
    use Livewire\WithPagination;
    use Carbon\Carbon;
    use Mpdf\Mpdf;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;
        
    
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
        public $selectedCreditInfo = null;
        public $lastPayment;
        public $lastPaymentHumanDate = 'Sin registros';

        protected $listeners = ['paymentUpdated' => 'updateLastPayment'];

    
        public function updateLastPayment()
        {
          
            $this->lastPayment = Payment::with('credit')
        ->latest('Payment_ID')              
        ->first();

    $this->lastPaymentHumanDate = optional($this->lastPayment)->Payment_Date?->diffForHumans() ?? 'Sin registros';
        }
       
    
        protected $rules = [
            'credit_id' => 'required|exists:credits,Credit_ID',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0.01'
        ];
        protected $messages = [
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
            $payment = Payment::with(['credit.client', 'credit.payments'])->findOrFail($paymentId);
            
            $totalPayments = $payment->credit->payments->sum('Payment_Amount');
            $remainingBalance = $payment->credit->Total_Amount - $totalPayments;
            $payment->remaining_balance = $remainingBalance;
            
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            
            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'fontDir' => array_merge($fontDirs, [
                    storage_path('fonts'), 
                ]),
                'fontdata' => $fontData + [
                    'dejavusans' => [
                        'R' => 'DejaVuSans.ttf',
                        'B' => 'DejaVuSans-Bold.ttf',
                    ],
                    
                ],
                'default_font' => 'dejavusans',
                'tempDir' => storage_path('app/mpdf/tmp'), 
            ]);
            
            // Renderizar la vista
            $html = view('livewire.payments.receipt', compact('payment'))->render();
            
            // Escribir el contenido en el PDF
            $mpdf->WriteHTML($html);
            
            // Descargar el PDF
            return response()->streamDownload(
                function () use ($mpdf) {
                    echo $mpdf->Output('', 'S');
                },
                "recibo-pago-{$payment->Payment_ID}.pdf",
                [
                    'Content-Type' => 'application/pdf',
                ]
            );
        }
    public function mount()
    {
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->loadCredits();
        $this->updateLastPayment(); 
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
                    $credit->client_full_name = $credit->client 
                        ? $credit->client->Client_FirstName . ' ' . $credit->client->Client_LastName 
                        : 'Cliente no encontrado';
        
                    $totalPayments = $credit->payments->sum('Payment_Amount');
                    $credit->remaining_balance = $credit->Total_Amount - $totalPayments;
        
                    return $credit;
                })
                ->filter(function($credit) {
                   
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
                }
            } else {
                $this->selectedCreditInfo = null;
            }
        }
        
   
        public function updatedCreditId($value)
        {
            $this->selectCredit($value); 
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
                        'payments' => $payments,
                        'lastPayment' => $this->lastPayment, 
                        'lastPaymentHumanDate' => $this->lastPaymentHumanDate,
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



    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        
        $this->paymentId = $id;
        $this->credit_id = $payment->Credit_ID;
        $this->payment_date = $payment->Payment_Date->format('Y-m-d');
        $this->payment_amount = $payment->Payment_Amount;
        
        $this->openModal();
    }

    public function selectCredit($creditId)
    {
        $this->credit_id = $creditId;
        $this->loadSelectedCreditInfo(); 
    }

    public function store()
{
    $this->validate();

  
    $credit = Credit::with('payments')->find($this->credit_id);
    $totalPayments = $credit->payments->sum('Payment_Amount');
    $remainingBalance = $credit->Total_Amount - $totalPayments;
    
    if ($this->payment_amount > $remainingBalance) {
        $this->addError('payment_amount', 'El monto del pago excede el saldo pendiente.');
        return;
    }

    $data = [
        'Credit_ID' => $this->credit_id,
        'Payment_Date' => $this->payment_date,
        'Payment_Amount' => $this->payment_amount,
    ];

    if ($this->paymentId) {
        Payment::find($this->paymentId)->update($data);
        $message = 'Abono actualizado correctamente';
    } else {
        Payment::create($data);
        $message = 'Abono registrado correctamente';
    }

    session()->flash('message', $message);
    $this->closeModal();
    $this->resetInputFields();
    $this->loadCredits();
    $this->loadSelectedCreditInfo();
    $this->dispatch('paymentUpdated');
    $this->updateLastPayment(); 
}


    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => 'No podrás revertir esta acción',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        Payment::find($id)->delete();
        session()->flash('message', 'Abono eliminado correctamente');
        $this->updateLastPayment(); 
    }
   
   

    
}