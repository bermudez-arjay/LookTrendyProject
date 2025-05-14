<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    
    protected $table = 'payments';


    protected $primaryKey = 'Payment_ID';

    
    public $timestamps = false;

 
    protected $fillable = [
        'Credit_ID',
        'Payment_Date',
        'Payment_Amount',
    ];


    protected $casts = [
        'Payment_Date' => 'datetime',
        'Payment_Amount' => 'decimal:2',
    ];

  
    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class, 'Credit_ID', 'Credit_ID');
    }
}
