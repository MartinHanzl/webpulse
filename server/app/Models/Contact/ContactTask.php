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
        'contact_phase_id',
    ];

    protected $with = ['phase'];

    public function phase()
    {
        return $this->belongsTo(ContactPhase::class, 'phase_id', 'id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contacts_has_tasks', 'contact_task_id', 'contact_id');
    }
}
