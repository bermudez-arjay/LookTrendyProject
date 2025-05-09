<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Supplier_ID
 * @property string $Supplier_Identity
 * @property string $Supplier_Name
 * @property string $Supplier_Address
 * @property string $Supplier_Phone
 * @property string $Supplier_Email
 * @property integer $Supplier_RUC
 * @property boolean $Active
 * @property Purchase[] $purchases
 * @property Transaction[] $transactions
 */
class Supplier extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Supplier_ID';

    /**
     * @var array
     */
    public $timestamps = false;
    protected $fillable = ['Supplier_Identity', 'Supplier_Name', 'Supplier_Address', 'Supplier_Phone', 'Supplier_Email', 'Supplier_RUC', 'Removed'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'Supplier_ID', 'Supplier_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'Supplier_ID', 'Supplier_ID');
    }
}
