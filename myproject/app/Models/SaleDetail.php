<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Sale_Detail_ID
 * @property integer $Sale_ID
 * @property integer $Product_ID
 * @property integer $Quantity
 * @property float $Subtotal
 * @property Sale $sale
 */
class SaleDetail extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Sale_Detail_ID';
      public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $fillable = ['Sale_ID', 'Product_ID', 'Quantity', 'Subtotal'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'Sale_ID', 'Sale_ID');
    }
}
