<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class AcademicRequirement extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'academic_requirements';

    protected $fillable = [
        'position_id',
        'academic_level_id',
        'academic_title',
        'formation_status_id'
    ];

    protected $auditInclude = [
        'position_id',
        'academic_level_id',
        'academic_title',
        'formation_status_id'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function academic_level(): BelongsTo
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function formation_status(): BelongsTo
    {
        return $this->belongsTo(FormationStatus::class);
    }
}
