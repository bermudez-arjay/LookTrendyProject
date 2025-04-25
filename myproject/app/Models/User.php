<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * @property integer $User_ID
 * @property string $User_FirstName
 * @property string $User_LastName
 * @property string $User_Address
 * @property string $User_Phone
 * @property string $User_Email
 * @property string $Password
 * @property string $User_Role
 * @property boolean $Removed
 * @property Purchase[] $purchases
 * @property Transaction[] $transactions
 */
class User extends Authenticatable

{
    use HasApiTokens,Notifiable;
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    public $timestamps = false;
    protected $primaryKey = 'User_ID';

    /**
     * @var array
     */
    protected $fillable = ['User_FirstName', 'User_LastName', 'User_Address', 'User_Phone', 'User_Email','Password', 'User_Role', 'Removed'];


    public function getAuthPassword()
    {
        return $this->Password;
    }
    public function getAuthIdentifierName()
    {
        return 'User_Email';
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'User_ID', 'User_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'User_ID', 'User_ID');
    }
}
