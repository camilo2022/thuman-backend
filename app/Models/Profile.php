<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Profile extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'profiles';

    protected $fillable = [
        'person_id',
        'title',
        'description'
    ];

    protected $auditInclude = [
        'person_id',
        'title',
        'description'
    ];

    public function emergency_contact(): HasOne
    {
        return $this->hasOne(EmergencyContact::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function academic_qualifications(): HasMany
    {
        return $this->hasMany(AcademicQualification::class);
    }

    public function language_proficiencies(): HasMany
    {
        return $this->hasMany(LanguageProficiencie::class);
    }

    public function professional_experiences(): HasMany
    {
        return $this->hasMany(ProfessionalExperience::class);
    }

    public function professional_skills(): HasMany
    {
        return $this->hasMany(ProfessionalSkill::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }
}
