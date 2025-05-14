<?php

namespace App\Observers;

use App\Models\Credit;

class CreditObserver
{
    public function updating(Credit $credit)
    {
       
        if ($credit->isDirty('total_amount') && $credit->Total_Amount == 0) {
            $credit->Credit_Status = 'Cancelado';
        }
    }
   
}
