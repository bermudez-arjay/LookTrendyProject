<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Credit extends Model
{
    protected $primaryKey = 'Credit_ID';
    public $timestamps = false;

    protected $fillable = [
        'Client_ID',
        'Start_Date',
        'Due_Date',
        'Total_Amount',
        'Interest_Rate',
        'Credit_Status',
        'Payment_Type_ID'
    ];

   
    protected $casts = [
        'Start_Date' => 'datetime',
        'Due_Date' => 'datetime',
        'Total_Amount' => 'float',
        'Interest_Rate' => 'float',
    ];

 

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'Client_ID', 'Client_ID');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'Payment_Type_ID', 'Payment_Type_ID');
    }

    public function creditDetails(): HasMany
    {
        return $this->hasMany(CreditDetail::class, 'Credit_ID', 'Credit_ID');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'Credit_ID', 'Credit_ID');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'Credit_ID', 'Credit_ID');
    }


    public function getRemainingBalanceAttribute(): float
    {
        return $this->Total_Amount - $this->payments->sum('Payment_Amount');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->remaining_balance <= 0;
    }

    public function getIsExpiredAttribute(): bool
    {
        return !$this->is_paid && $this->Due_Date && now()->gt($this->Due_Date);
    }

    public function getComputedStatusAttribute(): string
    {
        if ($this->is_paid) {
            return 'Cancelado';
        }

        if ($this->is_expired) {
            return 'Vencido';
        }

        return 'Pendiente';
    }

    public function getFormattedDueDateAttribute(): string
    {
        return $this->Due_Date ? $this->Due_Date->format('d/m/Y') : 'No definida';
    }
}
