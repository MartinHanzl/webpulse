<?php

namespace App\Models\DiscGolf;

use Illuminate\Database\Eloquent\Model;

class CourseLayout extends Model
{
    protected $table = 'course_layouts';

    protected $fillable = [
        'course_id',
        'name',
        'total_meters',
        'holes_count',
        'par',
        'position',
    ];

    protected $casts = [
        'total_meters' => 'integer',
        'holes_count' => 'integer',
        'par' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function holes()
    {
        return $this->hasMany(CourseLayoutHole::class, 'course_layout_id', 'id')->orderBy('number');
    }
}
