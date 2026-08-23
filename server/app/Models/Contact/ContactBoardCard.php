<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Model;

class ContactBoardCard extends Model
{
    protected $table = 'contact_board_cards';

    protected $fillable = [
        'user_id',
        'contact_board_section_id',
        'contact_id',
        'title',
        'description',
        'note',
        'priority',
        'due_date',
        'position',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function section()
    {
        return $this->belongsTo(ContactBoardSection::class, 'contact_board_section_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }
}
