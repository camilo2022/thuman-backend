<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class RequiredExperience extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'required_experiences';

    protected $fillable = [
        'position_id',
        'name',
        'duration_months',
        'description'
    ];

    protected $auditInclude = [
        'position_id',
        'name',
        'duration_months',
        'description'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
