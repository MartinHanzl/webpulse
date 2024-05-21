<?php

namespace App\Models\Administrator;

use Illuminate\Foundation\Auth\User as Authentication;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Administrator extends Authentication implements JWTSubject
{
    use HasApiTokens, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'firstname',
        'lastname',
        'phone_prefix',
        'phone',
        'email',
        'password',
        'active',
        'group_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = [
        'group'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function group()
    {
        return $this->hasOne(AdministratorGroup::class, 'id', 'group_id');
    }
}
