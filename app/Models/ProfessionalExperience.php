<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ProfessionalExperience extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'professional_experiences';

    protected $fillable = [
        'profile_id',
        'company_name',
        'supervisor_name',
        'supervisor_dialing_country_id',
        'supervisor_phone_number',
        'city_id',
        'company_address',
        'start_date',
        'end_date',
        'position',
        'work_arrangement_id',
        'position_description'
    ];

    protected $auditInclude = [
        'profile_id',
        'company_name',
        'supervisor_name',
        'supervisor_dialing_country_id',
        'supervisor_phone_number',
        'city_id',
        'company_address',
        'start_date',
        'end_date',
        'position',
        'work_arrangement_id',
        'position_description'
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function supervisor_dialing_country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'supervisor_dialing_country_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function work_arrangement(): BelongsTo
    {
        return $this->belongsTo(WorkArrangement::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }
}
