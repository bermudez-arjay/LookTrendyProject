<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Payment_ID
 * @property integer $Credit_ID
 * @property string $Payment_Date
 * @property float $Payment_Amount
 * @property Credit $credit
 */
class Payment extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Payment_ID';

    /**
     * @var array
     */
    protected $fillable = ['Credit_ID', 'Payment_Date', 'Payment_Amount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit()
    {
        return $this->belongsTo('App\Models\Credit', 'Credit_ID', 'Credit_ID');
    }
}
