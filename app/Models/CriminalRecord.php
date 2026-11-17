<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class CriminalRecord extends Model implements Auditable
{
    use Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'criminal_records';

    protected $fillable = [
        'application_id',
        'observation',
        'status'
    ];

    protected $auditInclude = [
        'application_id',
        'observation',
        'status'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }

    public static function statuses(): array
    {
        return [
            self::REVIEW,
            self::APPROVED,
            self::REJECTED
        ];
    }
}
