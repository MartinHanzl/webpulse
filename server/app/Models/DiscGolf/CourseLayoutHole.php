<?php

namespace App\Models\DiscGolf;

use Illuminate\Database\Eloquent\Model;

class CourseLayoutHole extends Model
{
    protected $table = 'course_layout_holes';

    protected $fillable = [
        'course_layout_id',
        'number',
        'par',
        'meters',
    ];

    protected $casts = [
        'number' => 'integer',
        'par' => 'integer',
        'meters' => 'integer',
    ];

    public function layout()
    {
        return $this->belongsTo(CourseLayout::class, 'course_layout_id', 'id');
    }
}
