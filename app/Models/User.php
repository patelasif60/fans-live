<?php

namespace App\Models;

use App\Mail\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mail;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'type', 'provider', 'provider_id', 'is_verified','remember_token', 'device_token'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Send a password reset email to the user.
     */
    public function sendPasswordResetNotification($token)
    {
        return Mail::to($this->email)->send(new ResetPassword($token, $this->email));
    }

    /**
     * Define Eloquent Relations.
     *
     * @return [type] [Verifyuser]
     */
    public function verifyUser()
    {
        return $this->hasOne(VerifyUser::class);
    }
}
