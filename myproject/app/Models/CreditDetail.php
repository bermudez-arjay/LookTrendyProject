<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Credit_Detail_ID
 * @property integer $Credit_ID
 * @property string $Payment_Date
 * @property float $Payment_Amount
 * @property integer $Product_ID
 * @property integer $Quantity
 * @property float $Subtotal
 * @property float $VAT
 * @property float $Total_With_VAT
 * @property Credit $credit
 * @property Product $product
 */
class CreditDetail extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Credit_Detail_ID';

    /**
     * @var array
     */
    protected $fillable = ['Credit_ID', 'Payment_Date', 'Payment_Amount', 'Product_ID', 'Quantity', 'Subtotal', 'VAT', 'Total_With_VAT'];

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
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'Product_ID', 'Product_ID');
    }
}
