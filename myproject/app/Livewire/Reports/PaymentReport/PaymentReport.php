<?php

namespace App\Livewire\Reports\PaymentReport;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Credit;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentReport extends Component
{
    public $start_date;
    public $end_date;
    public $credit_id;
    public $payment_type_id;
    public $min_amount;
    public $max_amount;

    public function mount()
    {
        $this->start_date = now()->subMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function exportPDF()
    {
        $payments = $this->getFilteredPayments();

        $pdf = Pdf::loadView('livewire.reports.paymentReport.payment-pdf', [
            'payments' => $payments,
            'total' => $payments->sum('Payment_Amount'),
            'filters' => [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'credit' => $this->credit_id ? 'Crédito #' . $this->credit_id : 'Todos',
                'payment_type' => $this->payment_type_id ? PaymentType::find($this->payment_type_id)?->Payment_Type_Name : 'Todos',
            ]
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "reporte-abonos-" . now()->format('Y-m-d') . ".pdf"
        );
    }

    protected function getFilteredPayments()
    {
        $query = Payment::with(['credit.client', 'paymentType']);

        if ($this->start_date) {
            $query->where('Payment_Date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->where('Payment_Date', '<=', $this->end_date);
        }

        if ($this->credit_id) {
            $query->where('Credit_ID', $this->credit_id);
        }

        if ($this->payment_type_id) {
            $query->where('Payment_Type_ID', $this->payment_type_id);
        }

        if ($this->min_amount) {
            $query->where('Payment_Amount', '>=', $this->min_amount);
        }

        if ($this->max_amount) {
            $query->where('Payment_Amount', '<=', $this->max_amount);
        }

        return $query->orderBy('Payment_Date', 'desc')->get();
    }

    public function render()
    {
        $payments = $this->getFilteredPayments();
        
        // Calcular métricas para las tarjetas
        $paymentsToday = Payment::whereDate('Payment_Date', today())->get();
        $daysPeriod = $this->start_date && $this->end_date 
            ? Carbon::parse($this->start_date)->diffInDays($this->end_date) + 1 
            : 0;
            
        $mostUsedPaymentType = Payment::select('Payment_Type_ID', DB::raw('count(*) as total'))
            ->groupBy('Payment_Type_ID')
            ->orderByDesc('total')
            ->first()
            ->paymentType->Payment_Type_Name ?? 'N/A';
            
        $mostUsedPaymentTypeCount = Payment::select('Payment_Type_ID', DB::raw('count(*) as total'))
            ->groupBy('Payment_Type_ID')
            ->orderByDesc('total')
            ->value('total') ?? 0;

        return view('livewire.reports.paymentReport.payment-report', [
            'payments' => $payments,
            'credits' => Credit::has('payments')->with('client')->get(),
            'paymentTypes' => PaymentType::all(),
            'total' => $payments->sum('Payment_Amount'),
            'paymentsToday' => $paymentsToday,
            'daysPeriod' => $daysPeriod,
            'mostUsedPaymentType' => $mostUsedPaymentType,
            'mostUsedPaymentTypeCount' => $mostUsedPaymentTypeCount,
        ])->layout('layouts.app');
    }
}