<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Credit_ID
 * @property integer $Client_ID
 * @property string $Start_Date
 * @property string $Due_Date
 * @property float $Total_Amount
 * @property float $Interest_Rate
 * @property string $Credit_Status
 * @property integer $Payment_Type_ID
 * @property Client $client
 * @property PaymentType $paymentType
 * @property CreditDetail[] $creditDetails
 * @property Transaction[] $transactions
 * @property Payment[] $payments
 */
class Credit extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Credit_ID';

    /**
     * @var array
     */
    protected $fillable = ['Client_ID', 'Start_Date', 'Due_Date', 'Total_Amount', 'Interest_Rate', 'Credit_Status', 'Payment_Type_ID'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'Client_ID', 'Client_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentType()
    {
        return $this->belongsTo('App\Models\PaymentType', 'Payment_Type_ID', 'Payment_Type_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditDetails()
    {
        return $this->hasMany('App\Models\CreditDetail', 'Credit_ID', 'Credit_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'Credit_ID', 'Credit_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'Credit_ID', 'Credit_ID');
    }
}
