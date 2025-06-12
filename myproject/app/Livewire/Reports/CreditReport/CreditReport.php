<?php

namespace App\Livewire\Reports\CreditReport;

use Livewire\Component;
use App\Models\Credit;
use App\Models\Client;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CreditReport extends Component
{
    public $start_date;
    public $due_date;
    public $client_id;
    public $credit_status;
    public $min_amount;
    public $max_amount;
   

    public function exportPDF()
    {
        $credits = $this->getFilteredCredits();
        
        $pdf = Pdf::loadView('livewire.reports.creditReport.credit-pdf', [
            'credits' => $credits,
            'filters' => [
                'start_date' => $this->start_date,
                'due_date' => $this->due_date,
                'client' => $this->client_id ? Client::find($this->client_id)->Client_FirstName : 'Todos',
                'credit_status' => $this->credit_status ? ucfirst($this->credit_status) : 'Todos',
               
            ]
        ])->setPaper('a4', 'landscape');
        
        return response()->streamDownload(
            fn () => print($pdf->output()),
            "reporte-creditos-".now()->format('Y-m-d').".pdf"
        );
    }

   

    protected function getFilteredCredits()
    {
        $query = Credit::with(['client', 'payments']);

        if ($this->start_date) {
            $query->where('Start_Date', '>=', $this->start_date);
        }

        if ($this->due_date) {
            $query->where('Due_Date', '<=', $this->due_date);
        }

        if ($this->client_id) {
            $query->where('Client_ID', $this->client_id);
        }

        if ($this->min_amount) {
            $query->where('Total_Amount', '>=', $this->min_amount);
        }

        if ($this->max_amount) {
            $query->where('Total_Amount', '<=', $this->max_amount);
        }

      
        $credits = $query->get();

        if ($this->credit_status === 'cancelado') {
            $credits = $credits->filter->is_paid;
        } elseif ($this->credit_status === 'vencido') {
            $credits = $credits->filter->is_expired;
        } elseif ($this->credit_status === 'pendiente') {
            $credits = $credits->reject->is_paid->reject->is_expired;
        }

        return $credits;
    }

    public function render()
    {
        $credits = $this->getFilteredCredits();

        return view('livewire.reports.creditReport.credit-report', [
            'creditos' => $credits,
            'clientes' => Client::orderBy('Client_FirstName')->get(),
          
        ])->layout('layouts.app');
    }
}