<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactTask extends Model
{
    use HasFactory;

    protected $table = 'contact_tasks';

    protected $fillable = [
        'name',
        'user_id',
        'phase_id',
    ];

    /*public function contacts()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }*/

    public function phase()
    {
        return $this->belongsTo(ContactPhase::class, 'phase_id', 'id');
    }
}
