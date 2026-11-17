<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Progress extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    public const INPROGRESS = 'En curso';
    public const FINISHED = 'Finalizado';

    public const PENDING = 'Pendiente';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'progress';

    protected $fillable = [
        'person_id',
        'step_id',
        'questions',
        'answers',
        'start_at',
        'end_at',
        'status',
        'result'
    ];

    protected $auditInclude = [
        'person_id',
        'step_id',
        'questions',
        'answers',
        'start_at',
        'end_at',
        'status',
        'result'
    ];

    protected $casts = [
        'questions' => 'array',
        'answers' => 'array',
        'start_at' => 'datetime:Y-m-d H:i:s',
        'end_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }

    public static function results(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::INPROGRESS,
            self::FINISHED
        ];
    }
}
