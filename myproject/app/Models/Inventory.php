<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Inventory_ID
 * @property integer $Product_ID
 * @property integer $Current_Stock
 * @property integer $Minimum_Stock
 * @property string $Last_Update
 * @property Product $product
 */
class Inventory extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Inventory_ID';
    public $timestamps= false;

    /**
     * @var array
     */
    protected $fillable = ['Product_ID', 'Current_Stock', 'Minimum_Stock', 'Last_Update'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'Product_ID', 'Product_ID');
    }
}
