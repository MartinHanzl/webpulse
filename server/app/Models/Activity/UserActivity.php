<?php

namespace App\Models\Activity;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $table = 'users_has_activities';

    protected $fillable = [
        'user_id',
        'activity_id',
        'description',
        'duration',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
