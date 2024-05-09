<?php

namespace App\Models\Administrator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministratorGroup extends Model
{
    protected $table = 'administrator_groups';

    protected $fillable = [
        'name',
        'permissions'
    ];

    public function administrators()
    {
        return $this->hasMany(Administrator::class, 'group_id', 'id');
    }
}
