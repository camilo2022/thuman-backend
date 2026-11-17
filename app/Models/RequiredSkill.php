<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class RequiredSkill extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'required_skills';

    protected $fillable = [
        'position_id',
        'skill_id',
        'percentage'
    ];

    protected $auditInclude = [
        'position_id',
        'skill_id',
        'percentage'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
