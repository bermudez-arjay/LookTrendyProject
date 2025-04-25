<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Payment_Type_ID
 * @property string $Payment_Type_Name
 * @property Purchase[] $purchases
 * @property Credit[] $credits
 */
class PaymentType extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Payment_Type_ID';

    /**
     * @var array
     */
    protected $fillable = ['Payment_Type_Name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'Payment_Type_ID', 'Payment_Type_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function credits()
    {
        return $this->hasMany('App\Models\Credit', 'Payment_Type_ID', 'Payment_Type_ID');
    }
}
