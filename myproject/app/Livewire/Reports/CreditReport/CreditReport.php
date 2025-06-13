<?php

namespace App\Livewire\Reports\CreditReport;

use Livewire\Component;
use App\Models\Credit;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CreditReport extends Component
{
    public $start_date;
    public $due_date;
    public $client_id;
    public $credit_status;
    public $min_amount;
    public $max_amount;

    public function mount()
    {
        $this->start_date = now()->subMonth()->format('Y-m-d');
        $this->due_date = now()->addMonth()->format('Y-m-d');
    }

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

    if ($this->credit_status === 'cancelado') {
        $query->whereHas('payments', function($q) {
            $q->selectRaw('Credit_ID, SUM(Payment_Amount) as total_paid')
              ->groupBy('Credit_ID')
              ->havingRaw('total_paid >= Total_Amount');
        });
    } elseif ($this->credit_status === 'vencido') {
        $query->where('Due_Date', '<', now())
              ->whereDoesntHave('payments', function($q) {
                  $q->selectRaw('Credit_ID, SUM(Payment_Amount) as total_paid')
                    ->groupBy('Credit_ID')
                    ->havingRaw('total_paid >= Total_Amount');
              });
    } elseif ($this->credit_status === 'pendiente') {
        $query->where('Due_Date', '>=', now())
              ->whereDoesntHave('payments', function($q) {
                  $q->selectRaw('Credit_ID, SUM(Payment_Amount) as total_paid')
                    ->groupBy('Credit_ID')
                    ->havingRaw('total_paid >= Total_Amount');
              });
    }

    return $query->orderBy('Start_Date', 'desc')->get();
}
    public function render()
    {
        $credits = $this->getFilteredCredits();
        $totalCredits = $credits->count();
        $totalAmount = $credits->sum('Total_Amount');
        $averageCredit = $totalCredits > 0 ? $totalAmount / $totalCredits : 0;
        
        $paidCredits = $credits->filter->is_paid->count();
        $expiredCredits = $credits->filter->is_expired->count();
        $pendingCredits = $credits->reject->is_paid->reject->is_expired->count();
        
        $daysPeriod = $this->start_date && $this->due_date 
            ? Carbon::parse($this->start_date)->diffInDays($this->due_date) 
            : 0;

        return view('livewire.reports.creditReport.credit-report', [
            'credits' => $credits,
            'clients' => Client::orderBy('Client_FirstName')->get(),
            'totalCredits' => $totalCredits,
            'totalAmount' => $totalAmount,
            'averageCredit' => $averageCredit,
            'paidCredits' => $paidCredits,
            'expiredCredits' => $expiredCredits,
            'pendingCredits' => $pendingCredits,
            'daysPeriod' => $daysPeriod,
        ])->layout('layouts.app');
    }
}