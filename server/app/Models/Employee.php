<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authentication;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authentication implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'employees';
    protected $fillable = [
        'firstname',
        'lastname',
        'phone_prefix',
        'phone',
        'email',
        'password',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'invitation_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
}
