<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Purchase_Detail_ID
 * @property integer $Purchase_ID
 * @property integer $Product_ID
 * @property integer $Quantity
 * @property float $Unit_Price
 * @property float $Subtotal
 * @property float $VAT
 * @property float $Total_With_VAT
 * @property Purchase $purchase
 * @property Product $product
 */
class PurchaseDetail extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Purchase_Detail_ID';

    /**
     * @var array
     */
    protected $fillable = ['Purchase_ID', 'Product_ID', 'Quantity', 'Unit_Price', 'Subtotal', 'VAT', 'Total_With_VAT'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'Purchase_ID', 'Purchase_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'Product_ID', 'Product_ID');
    }
}
