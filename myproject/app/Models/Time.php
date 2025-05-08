<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Time_ID
 * @property string $Date
 * @property integer $Year
 * @property integer $Quarter
 * @property integer $Month
 * @property integer $Week
 * @property string $Hour
 * @property integer $Day_of_Week
 * @property Purchase[] $purchases
 * @property Transaction[] $transactions
 */
class Time extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    public $timestamps = false;
    protected $primaryKey = 'Time_ID';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['Date', 'Year', 'Quarter', 'Month', 'Week', 'Hour', 'Day_of_Week'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'Time_ID', 'Time_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'Time_ID', 'Time_ID');
    }
}
