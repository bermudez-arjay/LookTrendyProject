<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;


        
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
    use HasApiTokens,Notifiable, CanResetPasswordTrait;
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

    public function getEmailForPasswordReset()
    {
        return $this->User_Email;
    }    

    public function routeNotificationForMail($notification)
    {
        return $this->User_Email;
    }

    public function getAuthIdentifierName()
    {
        return 'User_Email';
    }

    public function getEmailAttribute()
    {
        return $this->User_Email;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['User_Email'] = $value;
    }


    // public function sendPasswordResetNotification($token)
    // {
    //     $this->notify(new \App\Notifications\ResetPasswordCustom($token));
    // }



   
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
    // protected $email = 'User_Email';
    
}
