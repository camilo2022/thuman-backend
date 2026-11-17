<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ProfessionalSkill extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'professional_skills';

    protected $fillable = [
        'profile_id',
        'skill_id',
        'percentage'
    ];

    protected $auditInclude = [
        'profile_id',
        'skill_id',
        'percentage'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
