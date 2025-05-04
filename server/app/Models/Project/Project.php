<?php

namespace App\Models\Project;

use App\Models\Contact\Contact;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
        'note',
        'image',
        'expected_price',
        'total_price',
        'total_price_vat',
        'invoice_firstname',
        'invoice_lastname',
        'invoice_ico',
        'invoice_dic',
        'invoice_email',
        'invoice_phone_prefix',
        'invoice_phone',
        'invoice_street',
        'invoice_city',
        'invoice_zip',
        'invoice_country',
        'delivery_firstname',
        'delivery_lastname',
        'delivery_email',
        'delivery_phone_prefix',
        'delivery_phone',
        'delivery_street',
        'delivery_city',
        'delivery_zip',
        'delivery_country',
        'tax_rate_id',
        'client_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'expected_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_price_vat' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id', 'id');
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Contact::class, 'client_id', 'id');
    }

    public function statuses()
    {
        return $this->belongsToMany(ProjectStatus::class, 'projects_has_statuses', 'project_id', 'project_status_id');
    }
}
