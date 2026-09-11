<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class ServiceBookingSetting extends Model
{
    protected $table = 'service_booking_settings';

    protected $fillable = [
        'service_id',
        'work_start',
        'work_end',
        'slot_duration_minutes',
        'break_minutes',
        'working_days',
        'active',
    ];

    protected $casts = [
        'working_days' => 'array',
        'active' => 'boolean',
    ];

    protected $attributes = [
        'working_days' => '[1,2,3,4,5]',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function exceptions()
    {
        return $this->hasMany(ServiceBookingSettingException::class, 'booking_setting_id', 'id');
    }
}
