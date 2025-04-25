<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $Client_ID
 * @property string $Client_Identity
 * @property string $Client_FirstName
 * @property string $Client_LastName
 * @property string $Client_Address
 * @property string $Client_Phone
 * @property string $Client_Email
 * @property boolean $Active
 * @property Credit[] $credits
 */
class Client extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Client_ID';

    /**
     * @var array
     */
    protected $fillable = ['Client_Identity', 'Client_FirstName', 'Client_LastName', 'Client_Address', 'Client_Phone', 'Client_Email', 'Removed'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function credits()
    {
        return $this->hasMany('App\Models\Credit', 'Client_ID', 'Client_ID');
    }
}
