<?php

namespace App\Models\DiscGolf;

use App\Traits\Siteable;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use Siteable;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'position',
    ];

    public function layouts()
    {
        return $this->hasMany(CourseLayout::class, 'course_id', 'id')->orderBy('position');
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'course_id', 'id');
    }

    public function sites()
    {
        return $this->morphToMany('App\Models\Site\Site', 'siteable');
    }
}
