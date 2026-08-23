<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Model;

class ContactBoardSection extends Model
{
    protected $table = 'contact_board_sections';

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'position',
    ];

    public function cards()
    {
        return $this->hasMany(ContactBoardCard::class, 'contact_board_section_id', 'id')->orderBy('position');
    }
}
