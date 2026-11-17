<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class AcademicQualification extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'academic_qualifications';

    protected $fillable = [
        'profile_id',
        'city_id',
        'academic_level_id',
        'academic_institution',
        'academic_title',
        'formation_status_id',
        'start_date',
        'end_date'
    ];

    protected $auditInclude = [
        'profile_id',
        'city_id',
        'academic_level_id',
        'academic_institution',
        'academic_title',
        'formation_status_id',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function academic_level(): BelongsTo
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function formation_status(): BelongsTo
    {
        return $this->belongsTo(FormationStatus::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }
}
