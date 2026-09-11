<?php

namespace App\Models\Service;

use App\Traits\Siteable;
use Illuminate\Database\Eloquent\Model;

class ServiceBooking extends Model
{
    use Siteable;

    protected $table = 'service_bookings';

    protected $fillable = [
        'service_id', 'date', 'time_from', 'time_to',
        'first_name', 'last_name', 'phone', 'email',
        'note', 'status', 'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function sites()
    {
        return $this->morphToMany('App\Models\Site\Site', 'siteable');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
