<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Credit_Detail_ID
 * @property integer $Credit_ID
 * @property integer $Product_ID
 * @property integer $Quantity
 * @property float $Subtotal
 * @property float $VAT
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

    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['Credit_ID', 'Product_ID', 'Quantity', 'Subtotal', 'VAT'];

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
