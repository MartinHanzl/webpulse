<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSource extends Model
{
    use HasFactory;

    protected $table = 'contact_sources';

    protected $fillable = [
        'name',
        'color',
        'user_id',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'contact_source_id', 'id');
    }
}
