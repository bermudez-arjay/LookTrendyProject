<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Sale_ID
 * @property integer $Client_ID
 * @property string $Sale_Date
 * @property float $Sale_VAT
 * @property float $Total_Amount
 * @property SaleDetail[] $saleDetails
 * @property Client $client
 */
class Sale extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Sale_ID';
      public $timestamps = false;

    /**AC
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $fillable = ['Client_ID', 'Sale_Date', 'Sale_VAT', 'Total_Amount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetails()
    {
        return $this->hasMany('App\Models\SaleDetail', 'Sale_ID', 'Sale_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'Client_ID', 'Client_ID');
    }
}
