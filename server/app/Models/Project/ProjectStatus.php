<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'status_id', 'id');
    }
}
