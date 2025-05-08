<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Purchase_ID
 * @property integer $Supplier_ID
 * @property integer $User_ID
 * @property integer $Time_ID
 * @property float $Total_Amount
 * @property string $Purchase_Status
 * @property integer $Payment_Type_ID
 * @property PaymentType $paymentType
 * @property Supplier $supplier
 * @property User $user
 * @property Time $time
 * @property PurchaseDetail[] $purchaseDetails
 * @property Transaction[] $transactions
 */
class Purchase extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Purchase_ID';

    /**
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['Supplier_ID', 'User_ID', 'Time_ID', 'Total_Amount', 'Purchase_Status', 'Payment_Type_ID'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentType()
    {
        return $this->belongsTo('App\Models\PaymentType', 'Payment_Type_ID', 'Payment_Type_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'Supplier_ID', 'Supplier_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'User_ID', 'User_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function time()
    {
        return $this->belongsTo('App\Models\Time', 'Time_ID', 'Time_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseDetails()
    {
        return $this->hasMany('App\Models\PurchaseDetail', 'Purchase_ID', 'Purchase_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'Purchase_ID', 'Purchase_ID');
    }
}
