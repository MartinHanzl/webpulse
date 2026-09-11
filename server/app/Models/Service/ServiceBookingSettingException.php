<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class ServiceBookingSettingException extends Model
{
    protected $table = 'service_booking_setting_exceptions';

    protected $fillable = [
        'booking_setting_id',
        'date',
        'is_closed',
        'custom_times',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
        'custom_times' => 'array',
    ];

    public function bookingSetting()
    {
        return $this->belongsTo(ServiceBookingSetting::class, 'booking_setting_id', 'id');
    }
}
