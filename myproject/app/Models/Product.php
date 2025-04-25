<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Product_ID
 * @property string $Product_Name
 * @property string $Description
 * @property string $Category
 * @property float $Unit_Price
 * @property boolean $Active
 * @property PurchaseDetail[] $purchaseDetails
 * @property CreditDetail[] $creditDetails
 * @property Inventory[] $inventories
 * @property Transaction[] $transactions
 */
class Product extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Product_ID';

    /**
     * @var array
     */
    protected $fillable = ['Product_Name', 'Description', 'Category', 'Unit_Price', 'Removed'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseDetails()
    {
        return $this->hasMany('App\Models\PurchaseDetail', 'Product_ID', 'Product_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditDetails()
    {
        return $this->hasMany('App\Models\CreditDetail', 'Product_ID', 'Product_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany('App\Models\Inventory', 'Product_ID', 'Product_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'Product_ID', 'Product_ID');
    }
}
