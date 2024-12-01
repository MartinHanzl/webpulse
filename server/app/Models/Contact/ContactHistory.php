<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
    use HasFactory;

    protected $table = 'contact_histories';

    protected $fillable = [
        'contact_id',
        'contact_phase_id',
        'note',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function phase()
    {
        return $this->belongsTo(ContactPhase::class, 'contact_phase_id', 'id');
    }
}
