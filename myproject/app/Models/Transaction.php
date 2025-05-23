<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Transaction_ID
 * @property integer $Supplier_ID
 * @property integer $User_ID
 * @property integer $Time_ID
 * @property integer $Credit_ID
 * @property float $Total
 * @property string $Transaction_Type
 * @property integer $Purchase_ID
 * @property integer $Payment_Type_ID

 * @property Supplier $supplier
 * @property User $user
 * @property Time $time
 * @property Credit $credit
 * @property Purchase $purchase
 */
class Transaction extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Transaction_ID';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [ 'Supplier_ID', 'User_ID', 'Time_ID', 'Credit_ID',  'Total', 'Transaction_Type', 'Purchase_ID', 'Payment_Type_ID'];

  
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit()
    {
        return $this->belongsTo('App\Models\Credit', 'Credit_ID', 'Credit_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'Purchase_ID', 'Purchase_ID');
    }
}
